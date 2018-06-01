<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $location;

    /**
     * @ORM\Column(type="array")
     */
    private $role;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CustomRequest", mappedBy="user")
     */
    private $customRequestsCreated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CustomRequest", mappedBy="userAssignedTo")
     */
    private $customRequestsAssigned;

    public function __construct()
    {
        $this->customRequestsCreated = new ArrayCollection();
        $this->customRequestsAssigned = new ArrayCollection();
    }


// ******** getters & setters *********

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }
    public function getRoles()
    {
        return $this->role;
    }
    public function getRole(): ?array
    {
        return $this->role;
    }

    public function setRole(array $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

     public function getSalt()
    {

    }
 
     /**
      * @return Collection|CustomRequest[]
      */
     public function getCustomRequestsCreated(): Collection
     {
         return $this->customRequestsCreated;
     }
 
     public function addCustomRequestsCreated(CustomRequest $customRequestsCreated): self
     {
         if (!$this->customRequestsCreated->contains($customRequestsCreated)) {
             $this->customRequestsCreated[] = $customRequestsCreated;
             $customRequestsCreated->setUser($this);
         }
 
         return $this;
     }
 
     public function removeCustomRequestsCreated(CustomRequest $customRequestsCreated): self
     {
         if ($this->customRequestsCreated->contains($customRequestsCreated)) {
             $this->customRequestsCreated->removeElement($customRequestsCreated);
             // set the owning side to null (unless already changed)
             if ($customRequestsCreated->getUser() === $this) {
                 $customRequestsCreated->setUser(null);
             }
         }
 
         return $this;
     }
 
     /**
      * @return Collection|CustomRequest[]
      */
     public function getCustomRequestsAssigned(): Collection
     {
         return $this->customRequestsAssigned;
     }
 
     public function addCustomRequestsAssigned(CustomRequest $customRequestsAssigned): self
     {
         if (!$this->customRequestsAssigned->contains($customRequestsAssigned)) {
             $this->customRequestsAssigned[] = $customRequestsAssigned;
             $customRequestsAssigned->setUserAssignedTo($this);
         }
 
         return $this;
     }
 
     public function removeCustomRequestsAssigned(CustomRequest $customRequestsAssigned): self
     {
         if ($this->customRequestsAssigned->contains($customRequestsAssigned)) {
             $this->customRequestsAssigned->removeElement($customRequestsAssigned);
             // set the owning side to null (unless already changed)
             if ($customRequestsAssigned->getUserAssignedTo() === $this) {
                 $customRequestsAssigned->setUserAssignedTo(null);
             }
         }
 
         return $this;
     }
}
