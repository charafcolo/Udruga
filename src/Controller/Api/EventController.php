<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Entity\Association;
use App\Entity\User;
use App\Models\CustomJsonError;
use OpenApi\Annotations as OA;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
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
     * Show all events
     * 
     * @Route("", name="browse", methods={"GET"})
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
     * Show event by id
     * 
     * @Route("/{id}", name="read", methods={"GET"}, requirements={"id":"\d+"})
     *
     * 
     */
    public function event(Event $event = null): Response 
    {
        // if param converter = null, return json404 response
        if ($event === null) {
            //return JSON 404
            return $this->json404("il n'existe pas d'event avec cet ID");
        }
        // if all ok return JSON 200
        return $this->json200($event, ["api_event"]);
    }

    /**
     * Update an event
     * 
     * @Route("/edit/{id}", name="edit", methods={"PUT"}, requirements={"id":"\d+"})
     * 
     * @OA\RequestBody(
     * @Model(type=Event::class)
     * )
     *
     * @param Event $event
     * @param Request $request
     * @param integer $id
     * @param EntityManagerInterface $em
     * @param EventRepository $repo
     * @return JsonResponse
     */
    public function update(Event $event, Request $request, int $id, EntityManagerInterface $em, EventRepository $repo) : JsonResponse 
    {
        // recover the content
        $jsonContent = $request->getContent();

        // decode it
        $updatedEvent = json_decode($jsonContent);
        //dd($updatedEvent);

        /* 
        title
        type
        description
        date
        maxMember
        price
        status
        image
        slug
        users
        association 
        */
        $event = $repo->find($id);
        $event->setTitle($updatedEvent->title);
        $event->setType($updatedEvent->type);
        $event->setDescription($updatedEvent->description);
        $event->setDate($updatedEvent->date);
        $event->setMaxMember($updatedEvent->maxMember);
        $event->setPrice($updatedEvent->price);
        $event->setStatus($updatedEvent->status);
        $event->setImage($updatedEvent->image);
        $event->setSlug($updatedEvent->slug);
        
        $em->flush();
        return $this->json200($event, ["api_event"]);

    }

    /**
     * 
     * For create an event
     * 
     * @Route("", name="add", methods={"POST"})
     *
     * @OA\RequestBody(
     * @Model(type=Event::class)
     * ) 
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em ): JsonResponse
    {
        // Recover the content
        $jsonContent = $request->getContent();

        // Deserialize the JSON 
        $event = $serializer->deserialize($jsonContent, Event::class, 'json');
        
        // save on DB
        $em->persist($event);
        $em->flush();

        // TODO : return 201
        return $this->json(
            $event,
            // je précise que tout est OK de mon coté en précisant que la création c'est bien passé
            // 201
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
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * 
     * @OA\RequestBody(
     *     @Model(type=Event::class)
     * )
     * 
     * @OA\Response(
     *     response=201,
     *     description="delete event",
     *     @OA\JsonContent(
     *          ref=@Model(type=Event::class, groups={"api_event"})
     *      )
     * )
     * 
     */
    public function delete($id, Request $request, Event $event, EventRepository $eventRepository, EntityManagerInterface $em): Response
    {

        if($event = $eventRepository->find($id)){

            $eventRepository->remove($event);
            // Surtout ne pas faire un persist si non cela modifie juste l'id
            $em->flush();
        }
        return $this->json200($event, ["api_event"]);
    }
}

