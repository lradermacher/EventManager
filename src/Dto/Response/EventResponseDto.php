<?php

namespace App\Dto\Response;

use JMS\Serializer\Annotation as Serialization;

class EventResponseDto {

    /**
     * @Serialization\Type("int")
     */
    public int $id;

    /**
     * @Serialization\Type("string")
     */
    public string $title;

    /**
     * @Serialization\Type("DateTime<'Y-m-d H:i:s'>")
     */
    public \DateTime $date;

    /**
     * @Serialization\Type("string")
     */
    public string $city;

    /**
     * @Serialization\Type("DateTime<'Y-m-d H:i:s'>")
     */
    public \DateTime $createdAt;
}