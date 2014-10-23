<?php

namespace Dpavic\JobsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ORM\Entity
 * ORM\Table(name="affiliate")
 */
class Affiliate
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $url;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $token;

    /**
     * @ORM\Column(type="boolean", name="is_active, nullable=true")
     */
    protected $isActive;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

}
