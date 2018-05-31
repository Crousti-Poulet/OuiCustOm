<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomRequestRepository")
 */
class CustomRequest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;
    /**
     * @ORM\Column(type="text")
     */
    private $description;
    /**
     * @ORM\Column(type="string")
     */
    private $category;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photoPath;
    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;
// to do default dans DB : now()
    /**
     * @ORM\Column(type="string", length=255)
     */
    // todo ajouter relation vers User
    private $user;
    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\Choice(
     *     choices = { "A_VALIDER", "EN_ATTENTE", "ASSIGNE", "EN_COURS", "TERMINE" },
     *     message = "Veuillez sélectionner un statut valide"
     * )
     */
    private $status;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userAssignedTo;
    public function getId()
    {
        return $this->id;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
    public function getPhotoPath(): ?string
    {
        return $this->photoPath;
    }
    public function setPhotoPath(?string $photoPath): self
    {
        $this->photoPath = $photoPath;
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
    public function getUser(): ?string
    {
        return $this->user;
    }
    public function setUser(string $user): self
    {
        $this->user = $user;
        return $this;
    }
    public function getUserAssignedTo(): ?string
    {
        return $this->userAssignedTo;
    }
    public function setUserAssignedTo(?string $userAssignedTo): self
    {
        $this->userAssignedTo = $userAssignedTo;
        return $this;
    }
    public function getCategory()
    {
        return $this->category;
    }
    public function setCategory($category): void
    {
        $this->category = $category;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status): void
    {
        $this->status = $status;
    }
}