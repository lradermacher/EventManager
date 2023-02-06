<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\EventResponseDto;
use App\Entity\Event;

class EventResponseDtoTransformer extends AbstractResponseDtoTransformer {

    /**
     * @param Event $event
     * 
     * @return @return EventResponseDto
     */
    public function transformFromObject($event): EventResponseDto {
        $dto = new EventResponseDto();
        $dto->id = $event->getId();
        $dto->title = $event->getTitle();
        $dto->date = $event->getDate();
        $dto->city = $event->getCity();
        $dto->createdAt = $event->getCreatedAt();

        return $dto;
    }
}
