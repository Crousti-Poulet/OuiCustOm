<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CustomRequest", mappedBy="category")
     */
    private $customRequests;

    public function __construct()
    {
        $this->customRequests = new ArrayCollection();
    }

    public function __toString() : string
    {
        return $this->name;
    }

    // ******** getters & setters *********

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCustomRequests()
    {
        return $this->customRequests;
    }

    public function setCustomRequests($customRequests): void
    {
        $this->customRequests = $customRequests;
    }
}
