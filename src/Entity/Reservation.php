<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id] 
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'Date should not be blank')]
    #[Assert\GreaterThanOrEqual(
        value: 'today',
        message: 'Date should be greater than or equal to today'
    )] 
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Number of tickets should not be blank')]
    #[Assert\Type(type: 'integer', message: 'Number of tickets should be an integer')]
    #[Assert\GreaterThan(value: 0, message: 'Number of tickets should be greater than 0')]
    private ?int $nbrTickets = 0;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Email should not be blank')]
    #[Assert\Email(message: 'Invalid email address')]
    private ?string $userEmail = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Activity $activity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = 'Pending';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getNbrTickets(): ?int
    {
        return $this->nbrTickets;
    }

    public function setNbrTickets(?int $nbrTickets): static
    {
        $this->nbrTickets = $nbrTickets;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(?string $userEmail): static
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
