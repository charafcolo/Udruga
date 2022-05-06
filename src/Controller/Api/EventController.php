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
}
