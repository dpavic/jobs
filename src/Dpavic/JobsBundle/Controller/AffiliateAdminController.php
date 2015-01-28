<?php

namespace Dpavic\JobsBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AffiliateAdminController extends CRUDController
{

    public function batchActionActivate(ProxyQuery $selectedModelQuery)
    {
        if ($this->admin->isGranted('EDIT') === false ||
                $this->admin->isGranted('DELETE') === false) {
            throw new AccessDeniedException;
        }

        $request = $this->get('request');
        $modelManager = $this->admin->getModelManager();

        $selectedModels = $selectedModelQuery->execute();

        try {
            foreach ($selectedModels as $selectedModel) {
                $selectedModel->activate();
                $modelManager->update($selectedModel);

                $message = \Swift_Message::newInstance()
                        ->setSubject('Jobs affiliate token')
                        ->setFrom('dp01091985@gmail.com')
                        ->setTo($affiliate->getEmail())
                        ->setBody($this->renderView('DpavicJobsBundle:Affiliate:email.txt.twig', array(
                            'affiliate' => $affiliate->getToken())
                        )
                );

                $this->get('mailer')->send($message);
            }
        }
        catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->add('sonata_flash_error', $ex->getMessage());

            return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
        }

        $this->get('session')
                ->getFlashBag()
                ->add('sonata_flash_success', sprintf('The selected accounts have been activated')
        );

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function batchActionDeactivate(ProxyQuery $selectedModelQuery)
    {
        if ($this->admin->isGranted('EDIT') === false ||
                $this->admin->isGranted('DELETE') === false) {
            throw new AccessDeniedException;
        }

        $request = $this->get('request');
        $modelManager = $this->admin->getModelManager();

        $selectedModels = $selectedModelQuery->execute();

        try {
            foreach ($selectedModels as $selectedModel) {
                $selectedModel->deactivate();
                $modelManager->update($selectedModel);
            }
        }
        catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->add('sonata_flash_error', $ex->getMessage());

            return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
        }

        $this->get('session')
                ->getFlashBag()
                ->add('sonata_flash_success', sprintf('The selected accounts have been deactivated')
        );

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function activateAction($id)
    {
        if ($this->admin->isGranted('EDIT') === FALSE) {
            throw new AccessDeniedException;
        }

        $em = $this->getDoctrine()->getManager();

        /* @var $affiliate \Dpavic\JobsBundle\Entity\Affiliate */
        $affiliate = $em->getRepository('DpavicJobsBundle:Affiliate')->findOneById($id);

        try {
            $affiliate->setIsActive(true);
            $em->flush();

            $message = \Swift_Message::newInstance()
                    ->setSubject('Jobs affiliate token')
                    ->setFrom('dp01091985@gmail.com')
                    ->setTo($affiliate->getEmail())
                    ->setBody($this->renderView('DpavicJobsBundle:Affiliate:email.txt.twig', array(
                        'affiliate' => $affiliate->getToken())
                    )
            );

            $this->get('mailer')->send($message);
        }
        catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->add('sonata_flash_error', $ex->getMessage());
            return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
        }

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    public function deactivateAction($id)
    {
        if ($this->admin->isGranted('EDIT') === FALSE) {
            throw new AccessDeniedException;
        }

        $em = $this->getDoctrine()->getManager();

        /* @var $affiliate \Dpavic\JobsBundle\Entity\Affiliate */
        $affiliate = $em->getRepository('DpavicJobsBundle:Affiliate')->findOneById($id);

        try {
            $affiliate->setIsActive(false);
            $em->flush();
        }
        catch (\Exception $ex) {
            $this->get('session')->getFlashBag()->add('sonata_flash_error', $ex->getMessage());
            return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
        }

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

}
