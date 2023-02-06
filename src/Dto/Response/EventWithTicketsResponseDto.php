<?php

namespace App\Dto\Response;

use JMS\Serializer\Annotation as Serialization;
use App\Dto\Response\TicketResponseDto;

class EventWithTicketsResponseDto {

    /**
     * @Serialization\Type("App\Dto\Response\EventResponseDto")
     */
    public EventResponseDto $event;

    /**
     * @Serialization\Type("array<App\Dto\Response\TicketResponseDto>")
     */
    public array $tickets;
}