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
        
        $em->persist($job_mali_zeleni);
        $em->persist($job_mali_plavi);
        $em->flush();
    }

    public function getOrder()
    {
        return 2; //the order in which fixtures will be loaded 
    }

}

