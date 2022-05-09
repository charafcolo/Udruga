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
 */
class UserController extends JsonController
{
    /**
     * List users
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

        /**
     * Read user by ID
     * 
     * @Route("/{id}", name="read", methods={"GET"}, requirements={"id":"\d+"})
     *
     * 
     */
    public function user(User $user = null): Response 
    {
        /* si le parm converter n'a rien avec l'id $user est null */
        if ($user === null) {
            //on renvoi du JSON 404
            return $this->json404("il n'existe pas d'user avec cet ID");
        }
        return $this->json200($user, ["api_user"]);
    }

    /**
     * Create user
     *
     * @Route("", name="add", methods={"POST"})
     * 
     * @OA\RequestBody(
     * @Model(type=User::class)
     * )
     * 
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em 
        ): JsonResponse
    {
        // Récupérer le contenu JSON
        $jsonContent = $request->getContent();

        // Désérialiser (convertir) le JSON en entité Doctrine User
        $user = $serializer->deserialize($jsonContent, User::class, 'json');
         
        // On sauvegarde l'entité
        $em->persist($user);
        $em->flush();

        // TODO : return 201
        return $this->json(
            $user,
            // je précise que tout est OK de mon coté en précisant que la création c'est bien passé
            // 201
            Response::HTTP_CREATED,
            [],
            [
                "groups" => 
                [
                    "api_user"
                ]
            ]
        );
    }

    /**
     * Delete user
     * @Route("/{id}/delete", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        $em->persist($user);
        $em->flush();

        return $this->json200($user, ["api_user"]);
    }
}
