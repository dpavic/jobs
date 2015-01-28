<?php

namespace Dpavic\JobsBundle\Controller;

use Dpavic\JobsBundle\Entity\Affiliate;
use Dpavic\JobsBundle\Form\AffiliateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/affiliate")
 */
class AffiliateController extends Controller
{

    /**
     * @Route("/new", name="affiliate_new")
     * @Template("DpavicJobsBundle:Affiliate:affiliate_new.html.twig");
     * 
     */
    public function newAction()
    {
        $entity = new Affiliate();
        $form = $this->createForm(new AffiliateType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/create", name="affiliate_create")
     * @Method({"POST"})
     * 
     */
    public function createAction(Request $request)
    {
        $affiliate = new Affiliate();
        $form = $this->createForm(new AffiliateType(), $affiliate);

        $form->bind($request);
        $em = $this->getDoctrine()->getManager();

        if ($form->isValid()) {
            $formData = $request->get('affiliate');
            $affiliate->setUrl($formData['url']);
            $affiliate->setEmail($formData['email']);
            $affiliate->setIsActive(false);

            $em->persist($affiliate);
            $em->flush();

            return $this->redirect($this->generateUrl('affiliate_wait'));
        }

        return $this->render('DpavicJobsBundle:Affiliate:affiliate_new.html.twig', array(
                    'entity' => $affiliate,
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/wait", name="affiliate_wait")
     * @Template()
     */
    public function waitAction(Request $request)
    {
        //TODO: The affiliate Backend
//        var_dump($request);die();
    }
    
}
