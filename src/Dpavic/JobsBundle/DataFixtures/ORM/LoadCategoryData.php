<?php
namespace Dpavic\JobsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dpavic\JobsBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $em)
    {
        $design = new Category();
        $design->setName('Design');
        $design->setSlug('design');
        
        $programming = new Category();
        $programming->setName('Programming');
        $programming->setSlug('programming');
        
        $manager = new Category();
        $manager->setName('Manager');
        $manager->setSlug('manager');
        
        $administrator = new Category();
        $administrator->setName('Administrator');
        $administrator->setSlug('administrator');

        $em->persist($design);
        $em->persist($programming);
        $em->persist($manager);
        $em->persist($administrator);
        $em->flush();
        
        $this->addReference('category-design', $design);
        $this->addReference('category-programming', $programming);
        $this->addReference('category-manager', $manager);
        $this->addReference('category-administrator', $administrator);
    }

    public function getOrder()
    {
        return 1; // The order in which fixtures will be loaded
    }

}

