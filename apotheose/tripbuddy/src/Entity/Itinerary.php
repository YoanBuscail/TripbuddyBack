<?php

namespace App\Entity;

use App\Repository\ItineraryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ItineraryRepository::class)
 */
class Itinerary
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"itinerary", "step"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"itinerary", "step"})
     * @Assert\NotBlank(message="Le titre est obligatoire")
     */
    private $title;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"itinerary", "step"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"itinerary", "step"})
     */
    private $endDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"itinerary", "step"})
     */
    private $favorite;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="itinerary")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Step::class, inversedBy="itineraries", cascade={"persist"})
     * @Groups({"itinerary", "step"})
     */
    private $step;

    public function __construct()
    {
        $this->step = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function isFavorite(): ?bool
    {
        return $this->favorite;
    }

    public function setFavorite(?bool $favorite): self
    {
        $this->favorite = $favorite;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Step>
     */
    public function getStep(): Collection
    {
        return $this->step;
    }

    public function addStep(Step $step): self
    {
        if (!$this->step->contains($step)) {
            $this->step[] = $step;
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        $this->step->removeElement($step);

        return $this;
    }
}
