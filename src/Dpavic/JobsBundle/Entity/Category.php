<?php

namespace Dpavic\JobsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Dpavic\JobsBundle\Repository\CategoryRepository;
use Dpavic\JobsBundle\Utils\Jobs as Jobs;

/**
 * @ORM\Entity(repositoryClass="Dpavic\JobsBundle\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="category")
 */
class Category
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Job", mappedBy="category")
     */
    private $jobs;

    /**
     * @ORM\ManyToMany(targetEntity="Affiliate", mappedBy="categories")
     */
    private $affiliates;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    private $slug;
    private $activeJobs;
    private $moreJobs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->affiliates = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName() ? $this->getName() : "";
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add jobs
     *
     * @param Job $jobs
     * @return Category
     */
    public function addJob(Job $jobs)
    {
        $this->jobs[] = $jobs;

        return $this;
    }

    /**
     * Remove jobs
     *
     * @param Job $jobs
     */
    public function removeJob(Job $jobs)
    {
        $this->jobs->removeElement($jobs);
    }

    /**
     * Get jobs
     *
     * @return Collection 
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * Add affiliates
     *
     * @param Affiliate $affiliates
     * @return Category
     */
    public function addAffiliate(Affiliate $affiliates)
    {
        $this->affiliates[] = $affiliates;

        return $this;
    }

    /**
     * Remove affiliates
     *
     * @param Affiliate $affiliates
     */
    public function removeAffiliate(Affiliate $affiliates)
    {
        $this->affiliates->removeElement($affiliates);
    }

    /**
     * Get affiliates
     *
     * @return Collection 
     */
    public function getAffiliates()
    {
        return $this->affiliates;
    }

    public function getActiveJobs()
    {
        return $this->activeJobs;
    }

    public function setActiveJobs($activeJobs)
    {
        $this->activeJobs = $activeJobs;
    }

    function getMoreJobs()
    {
        return $this->moreJobs;
    }

    function setMoreJobs($moreJobs)
    {
        $this->moreJobs = $moreJobs >= 0 ? $moreJobs : 0;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlugValue()
    {
        $this->slug = \Jobs::slugify($this->getName());
    }

}
