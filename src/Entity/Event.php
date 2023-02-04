<?php
namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Event {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Column(type: 'datetime')]
    private ?DateTimeImmutable $date = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(targetEntity: 'Ticket', mappedBy: 'event')]
    private $tickets;

    public function __construct() {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function getDate(): ?DateTimeImmutable {
        return $this->date;
    }

    public function getCity(): ?string {
        return $this->city;
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
        $this->createdAt = new \DateTimeImmutable();
    }
}