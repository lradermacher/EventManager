<?php

namespace App\Dto\Response\Transformer;

use App\Dto\Response\TicketResponseDto;
use App\Entity\Ticket;

class TicketResponseDtoTransformer extends AbstractResponseDtoTransformer {

    /**
     * @param Ticket $ticket
     * 
     * @return @return TicketResponseDto
     */
    public function transformFromObject($ticket): TicketResponseDto {
        $dto = new TicketResponseDto();
        $dto->id = $ticket->getId();
        $dto->barcode = $ticket->getBarcode();
        $dto->firstName = $ticket->getFirstName();
        $dto->lastName = $ticket->getLastName();
        $dto->createdAt = $ticket->getCreatedAt();

        return $dto;
    }
}