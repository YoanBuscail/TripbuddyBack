<?php

namespace App\Entity;

use App\Repository\ItineraryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItineraryRepository::class)
 */
class Itinerary
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $startdate;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $enddate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $favorite;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="itineraries")
     */
    private $user;
    /**
     * @ORM\ManyToMany(targetEntity=Step::class, inversedBy="itineraries")
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

    public function getStartdate(): ?\DateTimeImmutable
    {
        return $this->startdate;
    }

    public function setStartdate(?\DateTimeImmutable $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getEnddate(): ?\DateTimeImmutable
    {
        return $this->enddate;
    }

    public function setEnddate(?\DateTimeImmutable $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function isFavorite(): ?bool
    {
        return $this->favorite;
    }

    public function setFavorite(bool $favorite): self
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
