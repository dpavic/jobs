<?php

namespace Dpavic\JobsBundle\Controller;

use Dpavic\JobsBundle\Entity\Job;
use Dpavic\JobsBundle\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{

    public function listAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();

        $jobs = array();

        $repo = $em->getRepository('DpavicJobsBundle:Affiliate');
        $affiliate = $repo->getForToken($token);

        if (!$affiliate) {
            throw $this->createNotFoundException('This affiliate account does not exist!');
        }

        $repo = $em->getRepository('DpavicJobsBundle:Job');
        $activeJobs = $repo->getActiveJobs(null, null, null, $affiliate->getId());

        foreach ($activeJobs as $job) {
            $jobs[$this->get('router')->generate('job_show', array(
                        'company' => $job->getCompanySlug(),
                        'location' => $job->getLocationSlug(),
                        'id' => $job->getId(),
                        'position' => $job->getPositionSlug()), true)
                    ] = $job->asArray($request->getHost());
        }

        $format = $request->getRequestFormat();
        $jsonData = json_encode($jobs);

        if ($format == "json") {
            $headers = array('Content-Type' => 'application/json');
            $response = new Response($jsonData, 200, $headers);

            return $response;
        }
        return $this->render('DpavicJobsBundle:Api:jobs.' . $format . '.twig', 
                array(
                    'jobs' => $jobs)
                );
    }

}
