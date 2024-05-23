<?php

namespace App\Entity;

use App\Repository\ReservationHebergementRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationHebergementRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ReservationHebergement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message :'this field should not be blank')]
    private  ?string $dateDeb = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message :'this field should not be blank')]
    private  ?string $dateFin = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'reservationHebergements')]
    private ?Hebergement $hebergement = null;

    #[ORM\Column]
    private ?int $nbrAdulte = null;

    #[ORM\Column]

    private ?int $nbrJeune = null;

    #[ORM\Column]
    private ?int $nbrEnfant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDeb(): ?string
    {
        return $this->dateDeb;
    }

    public function setDateDeb(string $dateDeb): static
    {
        $this->dateDeb = $dateDeb;

        return $this;
    }

    public function getDateFin(): ?string
    {
        return $this->dateFin;
    }

    public function setDateFin(string $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt =new DateTimeImmutable();
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getHebergement(): ?Hebergement
    {
        return $this->hebergement;
    }

    public function setHebergement(?Hebergement $hebergement): static
    {
        $this->hebergement = $hebergement;

        return $this;
    }

    public function getNbrAdulte(): ?int
    {
        return $this->nbrAdulte;
    }

    public function setNbrAdulte(int $nbrAdulte): static
    {
        $this->nbrAdulte = $nbrAdulte;

        return $this;
    }

    public function getNbrJeune(): ?int
    {
        return $this->nbrJeune;
    }

    public function setNbrJeune(int $nbrJeune): static
    {
        $this->nbrJeune = $nbrJeune;

        return $this;
    }

    public function getNbrEnfant(): ?int
    {
        return $this->nbrEnfant;
    }

    public function setNbrEnfant(int $nbrEnfant): static
    {
        $this->nbrEnfant = $nbrEnfant;

        return $this;
    }
    public function getTotalPrice(): ?float
    {
        // Assurez-vous que $hebergement est défini, sinon, ajustez la logique en conséquence
        $hebergement = $this->getHebergement();

        if ($hebergement !== null) {
            $prixAdulte = $hebergement->getPrixAdulte();
            $prixJeune = $hebergement->getPrixJeune();
            $prixEnfant = $hebergement->getPrixEnfant();
            $nbrAdulte = $this->getNbrAdulte();
            $nbrJeune=$this->getNbrJeune();
            $nbrEnfant=$this->getNbrEnfant();

            return $nbrAdulte * $prixAdulte + $nbrJeune * $prixJeune + $nbrEnfant * $prixEnfant + 5;
        }
        return null;
    }
}
