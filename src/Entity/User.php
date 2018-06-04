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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */

    private $profilPicture;

    /**
     * @ORM\Column(type="text", nullable=true)
     */

    private $description;

    /**
     * @ORM\column(type="string", length=255, nullable=true)
     */
    private  $category;


    public function __sleep()
    {
        return ['id', 'username', 'email', 'password', 'role', 'creationDate'];
    }
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
        $roles = $this->role;

        if (!in_array('ROLE_USER', $roles)){ // pour faire en sorte que l'utilisateur est au minimum le role user
            $roles[] = 'ROLE_USER';
        }

        return $roles;
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

    public function getProfilPicture()
    {
        return $this->profilPicture;
    }

    public function setProfilPicture($profilPicture)
    {
       $this->profilPicture = $profilPicture;

       return $this;
    }

    public function  getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
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
