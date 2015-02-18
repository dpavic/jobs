<?php

namespace Dpavic\JobsBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Dpavic\JobsBundle\Repository\AffiliateRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="affiliate")
 * @UniqueEntity("email")
 */
class Affiliate
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="affiliates")
     * @ORM\JoinTable(name="category_affiliate", 
     *      joinColumns={@ORM\JoinColumn(name="affiliate_id", referencedColumnName="id")}, 
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")})
     */
    private $categories;

    /**
     * @Assert\Url()
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="boolean", name="is_active", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
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
     * Set url
     *
     * @param string $url
     * @return Affiliate
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Affiliate
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set token
     * Only used in fixtures, for reallife use setTokenValue() instead
     *
     * @param string $token
     * @return Affiliate
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Set Token Value
     * Generate and set Token when User applies for an account
     * 
     * @ORM\PrePersist
     * 
     * @return Affiliate
     */
    public function setTokenValue()
    {
        if (!$this->getToken()) {
            $token = sha1($this->getEmail() . rand(11111, 99999));
            $this->token = $token;
        }
        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Affiliate
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set createdAt
     * @ORM\PrePersist
     * @return Affiliate
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Get createdAt
     *
     * @return DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Add categories
     *
     * @param Category $categories
     * @return Affiliate
     */
    public function addCategory(Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param Category $categories
     */
    public function removeCategory(Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Activate Affiliate in Admin Backend
     * 
     * @return type boolean
     */
    public function activate()
    {
        if (!$this->getIsActive()) {
            $this->setIsActive(true);
        }
        return $this->isActive;
    }

    /**
     * Deactivate Affiliate in Admin Backend
     * 
     * @return type boolean
     */
    public function deactivate()
    {
        if ($this->getIsActive()) {
            $this->setIsActive(false);
        }
        return $this->isActive;
    }

}
