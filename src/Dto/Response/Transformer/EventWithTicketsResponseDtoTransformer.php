<?php

namespace App\Dto\Response\Transformer;

use App\Entity\Event;
use App\Dto\Response\Transformer\EventResponseDtoTransformer;
use App\Dto\Response\Transformer\TicketResponseDtoTransformer;
use App\Dto\Response\EventWithTicketsResponseDto;

class EventWithTicketsResponseDtoTransformer extends AbstractResponseDtoTransformer {

    private EventResponseDtoTransformer $eventResponseDtoTransformer;
    private TicketResponseDtoTransformer $ticketResponseDtoTransformer;

    public function __construct(
        EventResponseDtoTransformer $eventResponseDtoTransformer,
        TicketResponseDtoTransformer $ticketResponseDtoTransformer
    ) {
        $this->eventResponseDtoTransformer = $eventResponseDtoTransformer;
        $this->ticketResponseDtoTransformer = $ticketResponseDtoTransformer;
    }

    /**
     * @param Event $event
     * 
     * @return @return EventWithTicketsResponseDto
     */
    public function transformFromObject($event): EventWithTicketsResponseDto {
        $dto = new EventWithTicketsResponseDto();
        $dto->event = $this->eventResponseDtoTransformer->transformFromObject($event);
        $dto->tickets = $this->ticketResponseDtoTransformer->transformFromObjects($event->getTickets());

        return $dto;
    }
}