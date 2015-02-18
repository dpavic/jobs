<?php

namespace Dpavic\JobsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dpavic\JobsBundle\Entity\Job;

class JobsCleanupCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('dpavic:jobs:cleanup')
                ->setDescription('Cleanup Jobeet database')
                ->addArgument('days', InputArgument::OPTIONAL, 'The email', 90);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $days = $input->getArgument('days');
        $em = $this->getContainer()->get('doctrine')->getManager();

        // cleanup Lucene index 
        $index = Job::getLuceneIndex();

        $q = $em->getRepository('DpavicJobsBundle:Job')->createQueryBuilder('j')
                ->where('j.expiresAt < :date')
                ->setParameter('date', date('Y-m-d'))
                ->getQuery();

        $jobs = $q->getResult();

        foreach ($jobs as $job) {
            if ($hit = $index->find('pk:' . $job->getId())) {
                $index->delete($hit->id);
            }
        }

        $index->optimize();
        $output->writeln('Cleaned up and optimized the job index');

        //remove stale jobs
        $nb = $em->getRepository('DpavicJobsBundle:Job')->cleanup($days);
        $output->writeln(sprintf('Removed %d stale jobs', $nb));
    }

}
