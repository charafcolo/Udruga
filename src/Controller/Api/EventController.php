<?php

namespace App\Controller\Api;

use App\Entity\Event;
use App\Entity\Association;
use App\Models\CustomJsonError;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
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

}
