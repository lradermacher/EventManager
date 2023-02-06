<?php

namespace App\Dto\Response;

use JMS\Serializer\Annotation as Serialization;

class EventWithTicketsResponseDto {

    /**
     * @Serialization\Type("App\Dto\Response\EventResponseDto")
     */
    public EventResponseDto $event;

    /**
     * @Serialization\Type("Array<App\Dto\Response\TicketResponseDto>")
     */
    public array $tickets;
}