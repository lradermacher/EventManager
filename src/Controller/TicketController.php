<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\Type\TicketType;
use App\Repository\TicketRepository;
use App\Controller\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Dto\Response\Transformer\TicketResponseDtoTransformer;

class TicketController extends AbstractApiController {

    private TicketResponseDtoTransformer $ticketResponseDtoTransformer;
    private TicketRepository $ticketRepository;

    public function __construct(
        TicketResponseDtoTransformer $ticketResponseDtoTransformer,
        TicketRepository $ticketRepository
    ) {
        $this->ticketResponseDtoTransformer = $ticketResponseDtoTransformer;
        $this->ticketRepository = $ticketRepository;
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

        return $this->respond(null);
    }
}