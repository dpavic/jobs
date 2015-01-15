<?php

namespace Dpavic\JobsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Dpavic\JobsBundle\Entity\Job;
use Dpavic\JobsBundle\Form\JobType;

/**
 * Job controller.
 *
 * @Route("/job")
 */
class JobController extends Controller
{

    /**
     * Lists all Job entities.
     *
     * @Route("/", name="job")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('DpavicJobsBundle:Category')
                ->getWithJobs();

        foreach ($categories as $category) {
            /* @var $category \Dpavic\JobsBundle\Entity\Category */
            $category->setActiveJobs($em->getRepository('DpavicJobsBundle:Job')
                            ->getActiveJobs($category->getId(), $this->container
                                    ->getParameter('max_jobs_on_homepage')));

            $category->setMoreJobs($em->getRepository('DpavicJobsBundle:Job')
                            ->countActiveJobs($category->getId()) - $this->container
                            ->getParameter('max_jobs_on_homepage'));
        }

        //get lastUpdated and feedId to send to template index.atom.twig
        /* @var $latestJob Job */
        $latestJob = $em->getRepository('DpavicJobsBundle:Job')->getLatestPost();

        if ($latestJob) {
            $lastUpdated = $latestJob->getCreatedAt()->format(DATE_ATOM);
        } else {
            $lastUpdated = new \DateTime();
            $lastUpdated = $lastUpdated->format(DATE_ATOM);
        }

        $format = $this->getRequest()->getRequestFormat();
        //render selected format, default is HTML
        return $this->render('DpavicJobsBundle:Job:index.' . $format . '.twig', array(
                    'categories' => $categories,
                    'lastUpdated' => $lastUpdated,
                    'feedId' => sha1($this->get('router')->generate('job', array('_format' => 'atom'), true)),
        ));
    }

    /**
     * Creates a new Job entity.
     *
     * @Route("/", name="job_create")
     * @Method("POST")
     * @Template("DpavicJobsBundle:Job:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Job();
        $form = $this->createForm(new JobType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('job_preview', array(
                                'company' => $entity->getCompanySlug(),
                                'location' => $entity->getLocationSlug(),
                                'token' => $entity->getToken(),
                                'position' => $entity->getPositionSlug()
            )));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Job entity.
     *
     * @param Job $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Job $entity)
    {
        $form = $this->createForm(new JobType(), $entity, array(
            'action' => $this->generateUrl('job_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Job entity.
     *
     * @Route("/new", name="job_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Job();
        $entity->setType('full-time');
        $form = $this->createForm(new JobType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Job entity.
     *
     * @Route("/{company}/{location}/{id}/{position}", requirements={"id" = "\d+"}, name="job_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DpavicJobsBundle:Job')->getActiveJob($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $session = $this->getRequest()->getSession();

        //fetch all jobs already stored in the job history
        $jobs = $session->get('job_history', array());

        //store the job as an array so we can put it in the session and avoid entity serialize errors
        $job = array(
            'id' => $entity->getId(),
            'position' => $entity->getPosition(),
            'company' => $entity->getCompany(),
            'companySlug' => $entity->getCompanySlug(),
            'locationSlug' => $entity->getLocationSlug(),
            'positionSlug' => $entity->getPositionSlug()
        );

        if (!in_array($job, $jobs)) {
            //add curent job at the beginning of the array
            array_unshift($jobs, $job);

            //store the new job history back into the session
            $session->set('job_history', array_slice($jobs, 0, 3));
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Job entity.
     *
     * @Route("/{token}/edit", name="job_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($token)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DpavicJobsBundle:Job')->findOneByToken($token);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $editForm = $this->createForm(new JobType(), $entity);
        $deleteForm = $this->createDeleteForm($token);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Job entity.
     *
     * @param Job $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Job $entity)
    {
        $form = $this->createForm(new JobType(), $entity, array(
            'action' => $this->generateUrl('job_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Job entity.
     *
     * @Route("/{token}/update", name="job_update")
     * @Method("POST")
     * @Template("DpavicJobsBundle:Job:edit.html.twig")
     */
    public function updateAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DpavicJobsBundle:Job')->findOneByToken($token);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $deleteForm = $this->createDeleteForm($token);
        $editForm = $this->createForm(new JobType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('job_preview', array(
                                'company' => $entity->getCompanySlug(),
                                'location' => $entity->getLocationSlug(),
                                'token' => $entity->getToken(),
                                'position' => $entity->getPositionSlug()
            )));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Job entity.
     *
     * @Route("/{token}/delete", name="job_delete")
     * @Method("DELETE|POST")
     */
    public function deleteAction(Request $request, $token)
    {
        $form = $this->createDeleteForm($token);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DpavicJobsBundle:Job')->findOneByToken($token);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Job entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('job'));
    }

    /**
     * Creates a form to delete a Job entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($token)
    {
        return $this->createFormBuilder(array('token' => $token))
                        ->add('token', 'hidden')
                        ->getForm();
    }

    /**
     *  @Route("/{company}/{location}/{token}/{position}", requirements={"id" = "\w+"}, name="job_preview")
     *  @Template("DpavicJobsBundle:Job:show.html.twig")
     */
    public function previewAction($token)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DpavicJobsBundle:Job')->findOneByToken($token);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $deleteForm = $this->createDeleteForm($entity->getToken());
        $publishForm = $this->createPublishForm($entity->getToken());

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
            'publish_form' => $publishForm->createView(),
        );
    }

    /**
     * @Route("/{token}/publish", name="job_publish")
     * @Method("POST")
     * @Template("DpavicJobsBundle:Job:edit.html.twig")
     * @param type $token
     */
    public function publishAction(Request $request, $token)
    {
        $form = $this->createPublishForm($token);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DpavicJobsBundle:Job')->findOneByToken($token);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Job entity.');
            }

            $entity->publish();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Your job is now online for 30 days.');
        }

        return $this->redirect($this->generateUrl('job_preview', array(
                            'company' => $entity->getCompanySlug(),
                            'location' => $entity->getLocationSlug(),
                            'token' => $entity->getToken(),
                            'position' => $entity->getPositionSlug()
        )));
    }

    private function createPublishForm($token)
    {
        return $this->createFormBuilder(array('token' => $token))
                        ->add('token', 'hidden')
                        ->getForm()
        ;
    }

}
