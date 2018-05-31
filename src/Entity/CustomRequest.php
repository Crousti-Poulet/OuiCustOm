<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomRequestRepository")
 */
class CustomRequest
{
    // constantes pour le status de la demande (on peut aussi définir les valeurs à 0, 1, 2...)
    const STATUS_A_VALIDER = 'A valider';
    const STATUS_EN_ATTENTE = 'Créée';
    const STATUS_ASSIGNE = 'Assignée';
    const STATUS_EN_COURS = 'En cours';
    const STATUS_TERMINE = 'Terminée';

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
     *     choices = { CustomRequest::STATUS_A_VALIDER, CustomRequest::STATUS_EN_ATTENTE, CustomRequest::STATUS_ASSIGNE, CustomRequest::STATUS_EN_COURS, CustomRequest::STATUS_TERMINE },
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