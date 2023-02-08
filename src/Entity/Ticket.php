<?php
namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ORM\Table(name: "tickets")]
#[ORM\UniqueConstraint(name: "barcode_per_event", columns: ["event_id", "barcode"])]
#[ORM\HasLifecycleCallbacks]
class Ticket {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 8)]
    private string $barcode;

    #[ORM\Column(length: 50)]
    private string $firstName;

    #[ORM\Column(length: 50)]
    private string $lastName;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $updatedAt;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Event', inversedBy: 'tickets')]
    #[ORM\JoinColumn(name: 'event_id', nullable: false, referencedColumnName: 'id')]
    private $event;

    public function __construct() {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getBarcode(): string {
        return $this->barcode;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function getCreatedAt(): \DateTime {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime {
        return $this->updatedAt;
    }

    public function getEvent(): ?Event {
        return $this->event;
    }

    public function setBarcode($barcode): self {
        $this->barcode = $barcode;

        return $this;
    }

    public function setFirstName($firstName): self {
        $this->firstName = $firstName;

        return $this;
    }

    public function setLastName($lastName): self {
        $this->lastName = $lastName;

        return $this;
    }

    public function setEvent(Event $event): self {
        $this->event = $event;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void {
        $this->createdAt = new \DateTime();
    }
}