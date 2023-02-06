<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\Type\EventType;
use App\Repository\EventRepository;
use App\Controller\AbstractApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Dto\Response\Transformer\EventResponseDtoTransformer;

class EventController extends AbstractApiController {

    private EventResponseDtoTransformer $eventResponseDtoTransformer;
    private EventRepository $eventRepository;

    public function __construct(
        EventResponseDtoTransformer $eventResponseDtoTransformer,
        EventRepository $eventRepository
    ) {
        $this->eventResponseDtoTransformer = $eventResponseDtoTransformer;
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

        $dto = $this->eventResponseDtoTransformer->transformFromObject($event);

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

        return $this->respond(null);
    }
}