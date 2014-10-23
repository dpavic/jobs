<?php

namespace Dpavic\JobsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="job")
 */
class Job
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $company;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $location;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="text", name="how_to_apply")
     */
    protected $howToApply;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $token;

    /**
     * @ORM\Column(type="boolean", name="is_public", nullable=true)
     */
    protected $isPublic;

    /**
     * @ORM\Column(type="boolean", name="is_activated", nullable="true")
     */
    protected $isActivated;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @ORM\Column(type="datetime", name="expires_at")
     */
    protected $expiresAt;

    /**
     * @ORM\Column(type="datetime", name"created_at")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", name"updated_at", nullable=true)
     */
    protected $updatedAt;

}
