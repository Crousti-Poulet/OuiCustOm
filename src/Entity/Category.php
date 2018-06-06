<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="category")
     */
    private $users;

    public function __construct()
    {
        $this->customRequests = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    // pour afficher un attribut de l'utilisateur dans la liste des demandes par exemple
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCategory($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCategory() === $this) {
                $user->setCategory(null);
            }
        }

        return $this;
    }
}
