<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\Type\EventType;
use App\Repository\EventRepository;
use App\Controller\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Dto\Response\Transformer\EventResponseDtoTransformer;
use App\Dto\Response\Transformer\EventWithTicketsResponseDtoTransformer;

class EventController extends AbstractApiController {

    private EventResponseDtoTransformer $eventResponseDtoTransformer;
    private EventWithTicketsResponseDtoTransformer $eventWithTicketsResponseDtoTransformer;
    private EventRepository $eventRepository;

    public function __construct(
        EventResponseDtoTransformer $eventResponseDtoTransformer,
        EventWithTicketsResponseDtoTransformer $eventWithTicketsResponseDtoTransformer,
        EventRepository $eventRepository
    ) {
        $this->eventResponseDtoTransformer = $eventResponseDtoTransformer;
        $this->eventWithTicketsResponseDtoTransformer = $eventWithTicketsResponseDtoTransformer;
        $this->eventRepository = $eventRepository;
    }

    public function listAction(Request $request): Response {
        $events = $this->eventRepository->findAll();
        $dtos = $this->eventResponseDtoTransformer->transformFromObjects($events);

        return $this->respond($dtos);
    }

    public function createAction(Request $request): Response {
        $event = new Event();

        $form = $this->buildForm(EventType::class, $event);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        $this->eventRepository->save($event);

        $dto = $this->eventResponseDtoTransformer->transformFromObject($event);

        return $this->respond($dto, Response::HTTP_CREATED);
    }

    public function getAction(Request $request): Response {
        $eventId = $request->get('eventId');
        $event = $this->eventRepository->findOneBy(['id' => $eventId]);

        if (!$event) {
            return $this->respond('Event not found!', Response::HTTP_NOT_FOUND);
        }

        $dto = $this->eventWithTicketsResponseDtoTransformer->transformFromObject($event);

        return $this->respond($dto);
    }

    public function updateAction(Request $request): Response {
        $eventId = $request->get('eventId');
        $event = $this->eventRepository->findOneBy(['id' => $eventId]);

        if (!$event) {
            return $this->respond('Event not found!', Response::HTTP_NOT_FOUND);
        }

        $form = $this->buildForm(EventType::class, $event, [
            'method' => $request->getMethod(),
        ]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        $this->eventRepository->save($event);

        $dto = $this->eventResponseDtoTransformer->transformFromObject($event);

        return $this->respond($dto);
    }

    public function deleteAction(Request $request): Response {
        $eventId = $request->get('eventId');
        $event = $this->eventRepository->findOneBy(['id' => $eventId]);

        if (!$event) {
            return $this->respond('Event not found!', Response::HTTP_NOT_FOUND);
        }

        $this->eventRepository->remove($event);

        return $this->respond('Deleted');
    }

    public function overview(Request $request): Response {
        $events = $this->eventRepository->findAll();
        $dtos = $this->eventResponseDtoTransformer->transformFromObjects($events);

        return $this->render('events/index.html.twig', [
            "events" => $dtos
        ]);
    }
    
    public function editView(Request $request): Response {
        $eventId = $request->get('eventId');
        $event = $this->eventRepository->findOneBy(['id' => $eventId]);

        if (!$event) {
            return $this->respond('Event not found!', Response::HTTP_NOT_FOUND);
        }

        $dto = $this->eventResponseDtoTransformer->transformFromObject($event);

        return $this->render('events/edit.html.twig', [
            "event" => $dto
        ]);
    }
}