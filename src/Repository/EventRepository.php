<?php
namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventRepository extends ServiceEntityRepository {
    
    private $entityManager;

    public function __construct(ManagerRegistry $registry) {
        $this->entityManager = $registry->getManager();
        parent::__construct($registry, Event::class);
    }

    /**
     * @return Event
     */
    public function save(Event $event): Event {
        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $event;
    }

    /**
     * @return Event
     */
    public function remove(Event $event): Event {
        $this->entityManager->remove($event);
        $this->entityManager->flush();

        return $event;
    }
}