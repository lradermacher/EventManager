<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\Type\TicketType;
use App\Repository\EventRepository;
use App\Repository\TicketRepository;
use App\Controller\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Dto\Response\Transformer\TicketResponseDtoTransformer;
use App\Dto\Response\Transformer\EventWithTicketsResponseDtoTransformer;

class TicketController extends AbstractApiController {

    private TicketResponseDtoTransformer $ticketResponseDtoTransformer;
    private EventWithTicketsResponseDtoTransformer $eventWithTicketsResponseDtoTransformer;
    private TicketRepository $ticketRepository;
    private EventRepository $eventRepository;

    public function __construct(
        TicketResponseDtoTransformer $ticketResponseDtoTransformer,
        EventWithTicketsResponseDtoTransformer $eventWithTicketsResponseDtoTransformer,
        TicketRepository $ticketRepository,
        EventRepository $eventRepository
    ) {
        $this->ticketResponseDtoTransformer = $ticketResponseDtoTransformer;
        $this->eventWithTicketsResponseDtoTransformer = $eventWithTicketsResponseDtoTransformer;
        $this->ticketRepository = $ticketRepository;
        $this->eventRepository = $eventRepository;
    }

    public function listAction(Request $request): Response {
        $tickets = $this->ticketRepository->findAll();
        $dtos = $this->ticketResponseDtoTransformer->transformFromObjects($tickets);

        return $this->respond($dtos);
    }

    public function createAction(Request $request): Response {
        $ticket = new Ticket();

        $form = $this->buildForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        $this->ticketRepository->save($ticket);

        $dto = $this->ticketResponseDtoTransformer->transformFromObject($ticket);

        return $this->respond($dto, Response::HTTP_CREATED);
    }

    public function getAction(Request $request): Response {
        $ticketId = $request->get('ticketId');
        $ticket = $this->ticketRepository->findOneBy(['id' => $ticketId]);

        if (!$ticket) {
            return $this->respond('Ticket not found!', Response::HTTP_NOT_FOUND);
        }

        $dto = $this->ticketResponseDtoTransformer->transformFromObject($ticket);

        return $this->respond($dto);
    }

    public function updateAction(Request $request): Response {
        $ticketId = $request->get('ticketId');
        $ticket = $this->ticketRepository->findOneBy(['id' => $ticketId]);

        if (!$ticket) {
            return $this->respond('Ticket not found!', Response::HTTP_NOT_FOUND);
        }

        $form = $this->buildForm(TicketType::class, $ticket, [
            'method' => $request->getMethod(),
        ]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        $this->ticketRepository->save($ticket);

        $dto = $this->ticketResponseDtoTransformer->transformFromObject($ticket);

        return $this->respond($dto);
    }

    public function deleteAction(Request $request): Response {
        $ticketId = $request->get('ticketId');
        $ticket = $this->ticketRepository->findOneBy(['id' => $ticketId]);

        if (!$ticket) {
            return $this->respond('Ticket not found!', Response::HTTP_NOT_FOUND);
        }

        $this->ticketRepository->remove($ticket);

        return $this->respond('Deleted');
    }

    public function overview(Request $request): Response {
        $eventId = $request->get('eventId');
        $event = $this->eventRepository->findOneBy(['id' => $eventId]);

        if (!$event) {
            return $this->respond('Event not found!', Response::HTTP_NOT_FOUND);
        }

        $dto = $this->eventWithTicketsResponseDtoTransformer->transformFromObject($event);

        return $this->render('tickets/index.html.twig', [
            "data" => $dto
        ]);
    }

    public function editView(Request $request): Response {
        $eventId = $request->get('eventId');
        $ticketId = $request->get('ticketId');
        $ticket = $this->ticketRepository->findOneBy(['id' => $ticketId, 'event' => $eventId]);

        if (!$ticket) {
            return $this->respond('Ticket not found!', Response::HTTP_NOT_FOUND);
        }

        $dto = $this->ticketResponseDtoTransformer->transformFromObject($ticket);

        return $this->render('tickets/edit.html.twig', [
            "ticket" => $dto,
            "eventId" => $eventId
        ]);
    }
}