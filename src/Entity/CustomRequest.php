<?php

namespace App\Entity;

use DateTime;
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="$customRequests")
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
    // todo default dans DB : now()

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\Choice(
     *     choices = { CustomRequest::STATUS_A_VALIDER, CustomRequest::STATUS_EN_ATTENTE, CustomRequest::STATUS_ASSIGNE, CustomRequest::STATUS_EN_COURS, CustomRequest::STATUS_TERMINE },
     *     message = "Veuillez sélectionner un statut valide"
     * )
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="customRequestsCreated")
     * @ORM\JoinColumn(nullable=false)
     * utilisateur qui a fait la demande
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="customRequestsAssigned")
     * utilisateur artisan qui a répondu à la demande
     */
    private $userAssignedTo;

    /**
     * CustomRequest constructor.
     */
    public function __construct()
    {
        // à défaut de pouvoir mettre now() par défaut sur la colonne de la BD !
        $this->creationDate = new DateTime();
        $this->status  = CustomRequest::STATUS_A_VALIDER;
//        $this->user = app.user;
    }

    /****************** getters & setters ****************************/

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
    public function getPhotoPath()
    {
        return $this->photoPath;
    }
    public function setPhotoPath( $photoPath): self
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
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
    public function getUserAssignedTo(): ?User
    {
        return $this->userAssignedTo;
    }
    public function setUserAssignedTo(?User $userAssignedTo): self
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