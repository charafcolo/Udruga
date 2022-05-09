<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Entity\Association;
use App\Entity\User;
use App\Models\CustomJsonError;
use OpenApi\Annotations as OA;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;



/**
 * 
 * @Route("/api/events", name="api_events_")
 * 
 */
class EventController extends JsonController
{
    /**
     * Route qui affiche tout les events
     * 
     * @Route("", name="browse", methods={"GET"})
     * 
     * @OA\RequestBody(
     *     @Model(type=Event::class)
     * )
     * 
     * @OA\Response(
     *     response=201,
     *     description="Show all events",
     *     @OA\JsonContent(
     *          ref=@Model(type=Event::class, groups={"api_event"})
     *      )
     * )
     * 
     */
    public function index(EventRepository $repo): Response
    {
        $allEvents = $repo->findAll();

        return $this->json200($allEvents,
            ["api_event"]
    );
    }

    /**
     * Route qui affiche l'event par son ID
     * 
     * @Route("/{id}", name="read", methods={"GET"}, requirements={"id":"\d+"})
     *
     * @OA\RequestBody(
     *     @Model(type=Event::class)
     * )
     * 
     * @OA\Response(
     *     response=201,
     *     description="Shwo one event by id",
     *     @OA\JsonContent(
     *          ref=@Model(type=Event::class, groups={"api_event"})
     *      )
     * )
     * 
     */
    public function event(Event $event = null): Response 
    {
        /* si le parm converter n'a rien avec l'id $event est null */
        if ($event === null) {
            //on renvoi du JSON 404
            return $this->json404("il n'existe pas d'event avec cet ID");
        }
        return $this->json200($event, ["api_event"]);
    }

    /**
     * Edit method for one event
     * 
     * @Route("/edit/{id}", name="edit", methods={"PUT"})
     *
     * @OA\RequestBody(
     *     @Model(type=Event::class)
     * )
     * 
     * @OA\Response(
     *     response=201,
     *     description="update an event",
     *     @OA\JsonContent(
     *          ref=@Model(type=Event::class, groups={"api_event"})
     *      )
     * )
     * 
     * @param Request $request
     * @param Event $event
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function edit($id, Request $request, EventRepository $eventRepository, EntityManagerInterface $em): JsonResponse
    {
        $event = $this->eventRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['title']) ? true : $event->setTitle($data['title']);
        empty($data['type']) ? true : $event->setType($data['type']);
        empty($data['description']) ? true : $event->setDescription($data['description']);
        empty($data['date']) ? true : $event->setDate($data['date']);
        empty($data['maxMember']) ? true : $event->setMaxMember($data['maxMember']);
        empty($data['price']) ? true : $event->setPrice($data['price']);
        empty($data['status']) ? true : $event->setStatus($data['status']);
        empty($data['image']) ? true : $event->setImage($data['image']);

        $updatedEvent = $this->eventRepository->updateEvent($event);

        return new JsonResponse($updatedEvent->toArray(), Response::HTTP_OK);
    }

    /**
     * road for create an event
     * 
     * @Route("", name="add", methods={"POST"})
     *
     * @param reques $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function create (Request $request, SerializerInterface $serializer, EntityManagerInterface $em) : JsonResponse
    {
        // Recover jsonContent
        $jsonContent = $request->getContent();

        // Desrializer and convert to JSON to entity DOctrine Event
        $event = $serializer->deserialize($jsonContent, Event::class, 'json');
        
        // save to entity
        $em->persist($event);
        $em->flush();

        //return 201
        return $this->json(
            $event,
            // inform all ok with 201 HTTP response 201
            Response::HTTP_CREATED,
            [],
            [
                "groups" =>
                [
                    "api_event"
                ]
            ]

        );
    }

    /**
     * Delete an event
     * 
     * @Route("")
     *
     * @param Request $request
     * @param Event $event
     * @param EventRepository $eventRepository
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function delete()
    {

    }

}
