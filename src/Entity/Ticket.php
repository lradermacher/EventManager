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
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    private ?string $barcode;

    #[ORM\Column(length: 50)]
    private ?string $firstName;

    #[ORM\Column(length: 50)]
    private ?string $lastName;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: 'Event', inversedBy: 'tickets')]
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

    public function getCreatedAt(): ?\DateTime {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime {
        return $this->updatedAt;
    }

    public function getEvent(): ?Event {
        return $this->event;
    }

    public function setBarcode($pBarcode): Ticket {
        $this->barcode = $pBarcode;

        return $this;
    }

    public function setFirstName($pFirstName): Ticket {
        $this->firstName = $pFirstName;

        return $this;
    }

    public function setLastName($pLastName): Ticket {
        $this->lastName = $pLastName;

        return $this;
    }

    public function setEvent(Event $event): Event {
        $this->event = $event;

        return $event;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void {
        $this->createdAt = new \DateTime();
    }
}