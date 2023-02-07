<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Ticket;
#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table(name: "events")]
#[ORM\HasLifecycleCallbacks]
class Event {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $date;

    #[ORM\Column(length: 255)]
    private string $city;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $updatedAt;

    #[ORM\OneToMany(targetEntity: 'App\Entity\Ticket', mappedBy: 'event', cascade: ["persist", "remove"])]
    private Collection $tickets;

    public function __construct() {
        $this->updatedAt = new \DateTime();
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDate(): \DateTime {
        return $this->date;
    }

    public function getCity(): string {
        return $this->city;
    }

    public function getCreatedAt(): \DateTime {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime {
        return $this->updatedAt;
    }

    public function getTickets(): Collection {
        return $this->tickets;
    }

    public function setTitle($title): self {
        $this->title = $title;

        return $this;
    }

    public function setDate($date): self {
        $this->date = $date;

        return $this;
    }

    public function setCity($city): self {
        $this->city = $city;

        return $this;
    }

    public function addTicket(Ticket $ticket): self {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets[] = $ticket;
            $ticket->setEvent($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
            if ($ticket->getEvent() === $this) {
                $ticket->setEvent(null);
            }
        }

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void {
        $this->createdAt = new \DateTime();
    }
}