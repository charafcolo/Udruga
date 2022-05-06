<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Models\CustomJsonError;
use OpenApi\Annotations as OA;
use App\Repository\UserRepository;
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
 * @Route("/api/users", name="api_users_")
 * 
 */
class UserController extends JsonController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     * 
     */
    public function index(UserRepository $repo): Response
    {
        $allUser = $repo->findAll();

        return $this->json200($allUser,
        ["api_user"]
    
    );
    }
}
