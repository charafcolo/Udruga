<?php

namespace App\Controller\Api;

use App\Entity\Association;
use App\Models\CustomJsonError;
use OpenApi\Annotations as OA;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssociationController extends JsonController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     * 
     */
    public function index(UserRepository $repo): Response
    {
        $allUser = $repo->findAll();

        return $this->json200($allUser,
        ["api_association"]
    
    );
    }
}
