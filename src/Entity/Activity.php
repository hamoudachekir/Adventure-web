<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Name should not be blank')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Price should not be blank')]
    #[Assert\Positive(message: 'Price should be a positive number')]
    private ?float $price = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'duration should not be blank')]
    #[Assert\Positive(message: 'duration should be a positive number')]
    private ?int $duration = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Type should not be blank')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: 'Type should contain only letters'
    )]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Description should not be blank')]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Max Guests should not be blank')]
    #[Assert\Positive(message: 'Max Guests should be a positive number')]
    private ?int $maxGuests = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Min Age should not be blank')]
    #[Assert\Positive(message: 'Min Age should be a positive number')]
    private ?int $minAge = null;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: ActivityImages::class, cascade: ['persist', 'remove'])]
    private Collection $activityImages;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: Comment::class,cascade: ['persist', 'remove'])]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: Reservation::class,cascade: ['persist', 'remove'])]
    private Collection $reservations;

    #[ORM\OneToOne(mappedBy: 'activity', cascade: ['persist', 'remove'])]
    private ?FavActivities $favActivities = null;

    public function __construct()
    {
        $this->activityImages = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxGuests(): ?int
    {
        return $this->maxGuests;
    }

    public function setMaxGuests(?int $maxGuests): static
    {
        $this->maxGuests = $maxGuests;

        return $this;
    }

    public function getMinAge(): ?int
    {
        return $this->minAge;
    }

    public function setMinAge(?int $minAge): static
    {
        $this->minAge = $minAge;

        return $this;
    }

    /**
     * @return Collection<int, ActivityImages>
     */
    public function getActivityImages(): Collection
    {
        return $this->activityImages;
    }

    public function addActivityImage(ActivityImages $activityImage): static
    {
        if (!$this->activityImages->contains($activityImage)) {
            $this->activityImages->add($activityImage);
            $activityImage->setActivity($this);
        }

        return $this;
    }

    public function removeActivityImage(ActivityImages $activityImage): static
    {
        if ($this->activityImages->removeElement($activityImage)) {
            // set the owning side to null (unless already changed)
            if ($activityImage->getActivity() === $this) {
                $activityImage->setActivity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setActivity($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getActivity() === $this) {
                $comment->setActivity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setActivity($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getActivity() === $this) {
                $reservation->setActivity(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PreRemove
     */
    public function removeImages(string $imageDirectory): void
    {
        foreach ($this->activityImages as $image) {
            $filename = $image->getUrl();
            if ($filename) {
                $filePath = $imageDirectory . $filename;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }
    }

    public function getFavActivities(): ?FavActivities
    {
        return $this->favActivities;
    }

    public function setFavActivities(FavActivities $favActivities): static
    {
        // set the owning side of the relation if necessary
        if ($favActivities->getActivity() !== $this) {
            $favActivities->setActivity($this);
        }

        $this->favActivities = $favActivities;

        return $this;
    }
}
