<?php

namespace App\Dto\Response;

use App\Entity\Event;
use JMS\Serializer\Annotation as Serialization;

class TicketResponseDto {

    /**
     * @Serialization\Type("int")
     */
    public int $id;

    /**
     * @Serialization\Type("string")
     */
    public string $barcode;

    /**
     * @Serialization\Type("string")
     */
    public string $firstName;

    /**
     * @Serialization\Type("string")
     */
    public string $lastName;

    /**
     * @Serialization\Type("string")
     */
    public string $createdAt;
}