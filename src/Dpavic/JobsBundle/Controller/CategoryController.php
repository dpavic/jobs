<?php

namespace Dpavic\JobsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Dpavic\JobsBundle\Entity\Category;

class CategoryController extends Controller
{

    /**
     * @Route("/category/{slug}/{page}", defaults={"page" = 1}, name="category_show")
     * @Template()
     */
    public function showAction($slug, $page)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $category Category */
        $category = $em->getRepository('DpavicJobsBundle:Category')
                ->findOneBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $latestJob = $em->getRepository('DpavicJobsBundle:Job')->getLatestPost($category->getId());

        if ($latestJob) {
            $lastUpdated = $latestJob->getCreatedAt()->format(DATE_ATOM);
        } else {
            $lastUpdated = new \DateTime();
            $lastUpdated = $lastUpdated->format(DATE_ATOM);
        }

        $totalJobs = $em->getRepository('DpavicJobsBundle:Job')->countActiveJobs($category->getId());
        $jobsPerPage = $this->container->getParameter('max_jobs_on_category');
        $lastPage = ceil($totalJobs / $jobsPerPage);
        $previousPage = $page > 1 ? $page - 1 : 1;
        $nextPage = $page < $lastPage ? $page + 1 : $lastPage;

        $category->setActiveJobs($em->getRepository('DpavicJobsBundle:Job')
                        ->getActiveJobs($category->getId(), $jobsPerPage, ($page - 1) * $jobsPerPage)
        );

        return array(
            'category' => $category,
            'lastPage' => $lastPage,
            'previousPage' => $previousPage,
            'currentPage' => $page,
            'nextPage' => $nextPage,
            'totalJobs' => $totalJobs,
            'feedId' => sha1($this->get('router')->generate('category_show', array(
                        'slug' => $category->getSlug(), 'format' => 'atom'), true)),
            'lastUpdated' => $lastUpdated,
        );
    }

}
