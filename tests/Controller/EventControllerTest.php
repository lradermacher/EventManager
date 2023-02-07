<?php

namespace App\tests\Controller;

use App\Entity\Event;
use App\Entity\Ticket;
use App\Dto\Response\EventResponseDto;
use App\Dto\Response\TicketResponseDto;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;

class EventControllerTest extends WebTestCase {

    private $entityManager;
    private $client;
    private Event $event;
    private Ticket $ticket;

    private function getPropertyInfo(string $class): iterable {
        $reflectionExtractor = new ReflectionExtractor();
        $accessExtractors = [$reflectionExtractor];
        $propertyInfo = new PropertyInfoExtractor(
            $accessExtractors,
        );

        return $propertyInfo->getProperties($class);
    }

    private function validateResponse($responseDtoKeys, $entity, $response): void {
        foreach ($responseDtoKeys as $responseDtoKey) {
            $getterUnicode = new UnicodeString('get_' . $responseDtoKey);
            $getter = (string)$getterUnicode->camel();
            $unicodeKey = new UnicodeString($responseDtoKey);

            $getValue = $entity->{$getter}();
            if (is_a($getValue, \DateTime::class)) {
                $getValue = $getValue->format('Y-m-d H:i:s');
            }

            $responseValue = $response->{$unicodeKey->snake()};
            $this->assertEquals($getValue, $responseValue);
        }
    }

    protected function setUp(): void {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()
            ->get('doctrine')
            ->getManager();

        $event = new Event();
        $event
            ->setTitle('Test Event 1')
            ->setDate(new \DateTime('2022-10-31T09:00:00Z'))
            ->setCity('Test City');

        $ticket = new Ticket();
        $ticket
            ->setBarcode('ABCD1234')
            ->setFirstName('Test First Name')
            ->setLastName('Test Last Name');
        $event->addTicket($ticket);

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        $this->event = $event;
        $this->ticket = $ticket;
    }

    public function testListEvents(): void {
        $this->client->request('GET', '/api/v1/events');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $eventResponseDtoKeys = $this->getPropertyInfo(EventResponseDto::class);

        foreach ($responseContent as $eventData) {
            foreach ($eventResponseDtoKeys as $eventResponseDtoKey) {
                $unicodeKey = new UnicodeString($eventResponseDtoKey);
                $this->assertArrayHasKey($unicodeKey->snake(), $eventData);
            }
        }
    }

    public function testGetEvent(): void {
        $this->client->request('GET', '/api/v1/events/0');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        $this->client->request('GET', '/api/v1/events/' . $this->event->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseContent = json_decode($this->client->getResponse()->getContent());
        $eventResponseDtoKeys = $this->getPropertyInfo(EventResponseDto::class);
        $ticketResponseDtoKeys = $this->getPropertyInfo(TicketResponseDto::class);

        $this->validateResponse($eventResponseDtoKeys, $this->event, $responseContent->event);

        foreach ($responseContent->tickets as $ticket) {
            $this->validateResponse($ticketResponseDtoKeys, $this->ticket, $ticket);
        }
    }

    public function testCreateEvent(): void {
        $this->client->request('POST', '/api/v1/events', []);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->client->request('POST', '/api/v1/events', [
            "title" => "Test Event 2",
            "date"  => "2022-10-31T09:00:00Z",
            "city"  => "Test City"
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $responseContent = json_decode($this->client->getResponse()->getContent());
        $createdEvent = $this->entityManager
            ->getRepository(Event::class)
            ->findOneById($responseContent->id);

        $eventResponseDtoKeys = $this->getPropertyInfo(EventResponseDto::class);
        $this->validateResponse($eventResponseDtoKeys, $createdEvent, $responseContent);
    }

    public function testUpdateEvent(): void {
        $this->client->request('PATCH', '/api/v1/events/0', []);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        $this->client->request('PATCH', '/api/v1/events/' . $this->event->getId(), []);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->client->request('PATCH', '/api/v1/events/' . $this->event->getId(), [
            "title" => "Test Event 3"
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseContent = json_decode($this->client->getResponse()->getContent());
        $updatedEvent = $this->entityManager
            ->getRepository(Event::class)
            ->findOneById($responseContent->id);

        $eventResponseDtoKeys = $this->getPropertyInfo(EventResponseDto::class);
        $this->validateResponse($eventResponseDtoKeys, $updatedEvent, $responseContent);
    }

    public function testDeleteEvent(): void {
        $this->client->request('DELETE', '/api/v1/events/0');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        $this->client->request('DELETE', '/api/v1/events/' . $this->event->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $events = $this->entityManager->getRepository(Event::class)->findAll();
        $this->assertEmpty($events);

        $tickets = $this->entityManager->getRepository(Ticket::class)->findAll();
        $this->assertEmpty($tickets);
    }

    protected function tearDown(): void {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
        $this->client = null;
        $this->event = null;
        $this->ticket = null;
    }
}