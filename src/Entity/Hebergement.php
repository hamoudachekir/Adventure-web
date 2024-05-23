<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HebergementRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: HebergementRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Hebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'The name should not be blank')]
    #[Assert\Length(min: 3, max: 255, minMessage: 'The name should be at least {{ limit }} characters', maxMessage: 'The name should not be longer than {{ limit }} characters')]
    private ?string $nom = null;

    #[ORM\Column(length: 2000)]
    #[Assert\NotBlank(message: 'The description should not be blank')]
    #[Assert\Length(min: 10, max: 2000, minMessage: 'The description should be at least {{ limit }} characters', maxMessage: 'The description should not be longer than {{ limit }} characters')]
    private ?string $description = null;
    #[ORM\Column]
    #[Assert\NotNull(message: 'the price should not be null')]
    #[Assert\NotBlank(message: 'The price should not be blank')]
    private ?float $prixAdulte = null;
    #[ORM\Column]
    #[Assert\NotNull(message: 'the price should not be null')]
    #[Assert\NotBlank(message: 'The price should not be blank')]
    private ?float $prixJeune = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'the price should not be null')]
    #[Assert\NotBlank(message: 'The price should not be blank')]
    private ?float $prixEnfant = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;
    #[ORM\Column]
    #[Assert\NotBlank(message: 'This field should not be blank')]
    #[Assert\Positive(message :'This value should be positive')]
    private ?int $nbrPlace = null;
    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity:TypeHebergement::class,inversedBy: 'hebergements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeHebergement $TypeHebergement = null;

    #[ORM\ManyToOne(targetEntity:HostService::class,inversedBy: 'hebergements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?HostService $hostService = null;

    #[ORM\OneToMany(mappedBy: 'hebergement', targetEntity: ReservationHebergement::class)]
    private Collection $reservationHebergements;


    public function __construct()
    {
        $this->reservationHebergements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixAdulte(): ?float
    {
        return $this->prixAdulte;
    }

    public function setPrixAdulte(float $prixAdulte): static
    {
        $this->prixAdulte = $prixAdulte;

        return $this;
    }


    public function getNbrPlace(): ?int
    {
        return $this->nbrPlace;
    }

    public function setNbrPlace(int $nbrPlace): static
    {
        $this->nbrPlace = $nbrPlace;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }
    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getTypeHebergement(): ?TypeHebergement
    {
        return $this->TypeHebergement;
    }

    public function setTypeHebergement(?TypeHebergement $TypeHebergement): static
    {
        $this->TypeHebergement = $TypeHebergement;

        return $this;
    }

    public function getHostService(): ?HostService
    {
        return $this->hostService;
    }

    public function setHostService(?HostService $hostService): static
    {
        $this->hostService = $hostService;

        return $this;
    }

    /**
     * @return Collection<int, ReservationHebergement>
     */
    public function getReservationHebergements(): Collection
    {
        return $this->reservationHebergements;
    }

    public function addReservationHebergement(ReservationHebergement $reservationHebergement): static
    {
        if (!$this->reservationHebergements->contains($reservationHebergement)) {
            $this->reservationHebergements->add($reservationHebergement);
            $reservationHebergement->setHebergement($this);
        }

        return $this;
    }

    public function removeReservationHebergement(ReservationHebergement $reservationHebergement): static
    {
        if ($this->reservationHebergements->removeElement($reservationHebergement)) {
            // set the owning side to null (unless already changed)
            if ($reservationHebergement->getHebergement() === $this) {
                $reservationHebergement->setHebergement(null);
            }
        }

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPrixJeune(): ?float
    {
        return $this->prixJeune;
    }

    public function setPrixJeune(float $prixJeune): static
    {
        $this->prixJeune = $prixJeune;

        return $this;
    }

    public function getPrixEnfant(): ?float
    {
        return $this->prixEnfant;
    }

    public function setPrixEnfant(float $prixEnfant): static
    {
        $this->prixEnfant = $prixEnfant;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
