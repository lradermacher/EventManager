<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: "events")]
#[ORM\HasLifecycleCallbacks]
class Event {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $updatedAt = null;

    #[ORM\OneToMany(targetEntity: 'Ticket', mappedBy: 'event')]
    private Collection $tickets;

    public function __construct() {
        $this->updatedAt = new \DateTime();
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function getDate(): ?\DateTime {
        return $this->date;
    }

    public function getCity(): ?string {
        return $this->city;
    }

    public function getCreatedAt(): ?\DateTime {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime {
        return $this->updatedAt;
    }

    public function getTickets(): Collection {
        return $this->tickets;
    }

    public function setTitle($pTitle): Event {
        $this->title = $pTitle;

        return $this;
    }

    public function setDate($pDate): Event {
        $this->date = $pDate;

        return $this;
    }

    public function setCity($pCity): Event {
        $this->city = $pCity;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void {
        $this->createdAt = new \DateTime();
    }
}