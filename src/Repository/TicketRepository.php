<?php
namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TicketRepository extends ServiceEntityRepository {
    
    private $entityManager;

    public function __construct(ManagerRegistry $registry) {
        $this->entityManager = $registry->getManager();
        parent::__construct($registry, Ticket::class);
    }

    /**
     * @return Ticket
     */
    public function save(Ticket $ticket): Ticket {
        $this->entityManager->persist($ticket);
        $this->entityManager->flush();

        return $ticket;
    }

    /**
     * @return void
     */
    public function remove(Ticket $ticket): void {
        $this->entityManager->remove($ticket);
        $this->entityManager->flush();
    }
}