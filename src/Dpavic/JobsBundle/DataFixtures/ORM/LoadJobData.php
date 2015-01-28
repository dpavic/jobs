<?php

namespace Dpavic\JobsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dpavic\JobsBundle\Entity\Job;

class LoadJobData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $em)
    {
        $job_mali_zeleni = new Job();
        $job_mali_zeleni->setCategory($em->merge($this->getReference('category-programming')));
        $job_mali_zeleni->setType('full-time');
        $job_mali_zeleni->setCompany('Mali Zeleni');
        $job_mali_zeleni->setLogo('mali-zeleni-logo.png');
        $job_mali_zeleni->setUrl('http://www.mali-zeleni.hr');
        $job_mali_zeleni->setPosition('Web Developer');
        $job_mali_zeleni->setLocation('Rijeka, Croatia');
        $job_mali_zeleni->setDescription('Lorem ipsum dolor sit amet, 
            consectetur adipiscing elit. Pellentesque non finibus nisi, ac malesuada mi. 
            Nulla vel dignissim elit. Suspendisse potenti. 
            Suspendisse non eros eget mi fringilla aliquet. 
            Phasellus maximus felis libero, quis sagittis felis ornare accumsan. 
            Nulla diam lacus, volutpat nec nulla at, ullamcorper tristique enim. 
            Quisque feugiat mattis condimentum.');
        $job_mali_zeleni->setHowToApply('Send yoour resume to drazen.pavic [at] gmail.com');
        $job_mali_zeleni->setIsPublic(true);
        $job_mali_zeleni->setIsActivated(true);
        $job_mali_zeleni->setToken('job_mali_zeleni');
        $job_mali_zeleni->setEmail('drazen.pavic@gmail.com');
        $job_mali_zeleni->setExpiresAt(new \DateTime('+30 days'));
        
        $job_mali_plavi = new Job();
        $job_mali_plavi->setCategory($em->merge($this->getReference('category-design')));
        $job_mali_plavi->setType('part-time');
        $job_mali_plavi->setCompany('Mali Plavi');
        $job_mali_plavi->setLogo('mali-plavi-logo.png');
        $job_mali_plavi->setUrl('http://www.mali-plavi.hr');
        $job_mali_plavi->setPosition('Web Designer');
        $job_mali_plavi->setLocation('frankfur A.M., Germany');
        $job_mali_plavi->setDescription('Lorem ipsum dolor sit amet, 
            consectetur adipiscing elit. Pellentesque non finibus nisi, ac malesuada mi. 
            Nulla vel dignissim elit. Suspendisse potenti. 
            Suspendisse non eros eget mi fringilla aliquet. 
            Phasellus maximus felis libero, quis sagittis felis ornare accumsan. 
            Nulla diam lacus, volutpat nec nulla at, ullamcorper tristique enim. 
            Quisque feugiat mattis condimentum.');
        $job_mali_plavi->setHowToApply('Send yoour resume to drazen.pavic [at] gmail.com');
        $job_mali_plavi->setIsPublic(true);
        $job_mali_plavi->setIsActivated(true);
        $job_mali_plavi->setToken('job_mali_plaiv');
        $job_mali_plavi->setEmail('drazen.pavic@gmail.com');
        $job_mali_plavi->setExpiresAt(new \DateTime('+30 days'));
        
        $job_expired = new Job();
        $job_expired->setCategory($em->merge($this->getReference('category-programming')));
        $job_expired->setType('full-time');
        $job_expired->setCompany('Sensio Labs');
        $job_expired->setLogo('sensio-labs.gif');
        $job_expired->setUrl('http://www.sensiolabs.com/');
        $job_expired->setPosition('Web Developer Expired');
        $job_expired->setLocation('Paris, France');
        $job_expired->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
        $job_expired->setHowToApply('Send your resume to lorem.ipsum [at] dolor.sit');
        $job_expired->setIsPublic(true);
        $job_expired->setIsActivated(true);
        $job_expired->setToken('job_expired');
        $job_expired->setEmail('job@example.com');
        $job_expired->setCreatedAt(new \DateTime('2005-12-01'));
 
        $em->persist($job_expired);
        $em->persist($job_mali_zeleni);
        $em->persist($job_mali_plavi);

        for ($i = 100; $i <= 130; $i++) {
            $job = new Job();
            $job->setCategory($em->merge($this->getReference('category-programming')));
            $job->setType('full-time');
            $job->setCompany('Company ' . $i);
            $job->setPosition('Web Developer');
            $job->setLocation('Paris, France');
            $job->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $job->setHowToApply('Send your resume to lorem.ipsum [at] dolor.sit');
            $job->setIsPublic(true);
            $job->setIsActivated(true);
            $job->setToken('developer_' . $i);
            $job->setEmail('job@example.com');

            $em->persist($job);
        }
        
        for ($i = 100; $i <= 112; $i++) {
            $manager = new Job();
            $manager->setCategory($em->merge($this->getReference('category-manager')));
            $manager->setType('full-time');
            $manager->setCompany('Company ' . $i);
            $manager->setPosition('Manger');
            $manager->setLocation('Rijeka, Croatia');
            $manager->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $manager->setHowToApply('Send your resume to lorem.ipsum [at] dolor.sit');
            $manager->setIsPublic(true);
            $manager->setIsActivated(true);
            $manager->setToken('manager_' . $i);
            $manager->setEmail('job@example.com');

            $em->persist($manager);
        }
        
        for ($i = 100; $i <= 120; $i++) {
            $administrator = new Job();
            $administrator->setCategory($em->merge($this->getReference('category-administrator')));
            $administrator->setType('full-time');
            $administrator->setCompany('Company ' . $i);
            $administrator->setPosition('Administrator');
            $administrator->setLocation('Frankfurt am Main, Germany');
            $administrator->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $administrator->setHowToApply('Send your resume to lorem.ipsum [at] dolor.sit');
            $administrator->setIsPublic(true);
            $administrator->setIsActivated(true);
            $administrator->setToken('administrator_' . $i);
            $administrator->setEmail('job@example.com');

            $em->persist($administrator);
        }

        $em->flush();
    }

    public function getOrder()
    {
        return 2; //the order in which fixtures will be loaded 
    }

}
