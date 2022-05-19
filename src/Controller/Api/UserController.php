<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Association;
use App\Models\CustomJsonError;
use OpenApi\Annotations as OA;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\Api\JsonController;
use App\Repository\AssociationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
/**
 * 
 * @Route("/api/users", name="api_users_")
 * @OA\Tag(name="Udruga API : Users")
 * 
 */
class UserController extends AbstractController
{
    /**
     * List users
     * @Route("", name="browse", methods={"GET"})
     * 
     */
    public function index(UserRepository $repo): Response
    {
        $allUser = $repo->findAll();

        return $this->json($allUser,
        Response::HTTP_OK, [], ["groups" => ["api_user"]]
    
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
            return $this->json("il n'existe pas d'user avec cet ID");
        }
        return $this->json($user, Response::HTTP_OK, [], ["groups" => ["api_user"]]);
    }

    /**
     * Mise à jour d'un user
     * 
     * @Route("/edit/{id}", name="edit-user", methods={"PUT"}, requirements={"id":"\d+"})
     * 
     * @OA\RequestBody(
     * @Model(type=User::class)
     * )
     * 
     * @param integer $id
     * @param EntityManagerInterface $em
     * @param UserRepository $repo
     * @param User $user
     * @return Response
     */
    public function updateUser(User $user,Request $request, int $id, EntityManagerInterface $em, UserRepository $repo): JsonResponse
    {
     
        
        $jsonContent = $request->getContent();
    

        $updatedUser = json_decode($jsonContent);
        // dd($updatedUser);
       
        // $userString = strval($jsonContent);


        $user = $repo->find($id);
        $user->setFirstName($updatedUser->firstName);
        $user->setLastName($updatedUser->lastName);
        $user->setEmail($updatedUser->email);
        $user->setPassword($updatedUser->password);
        $user->setRoles($updatedUser->roles);
        
        $em->flush();
        return $this->json($user, Response::HTTP_OK, [], ["groups" => ["api_user"]]);
    }


        /**
     * Mise à jour partielle d'un user
     * 
     * @Route("/edit/{id}", name="join-asso", methods={"PATCH"}, requirements={"id":"\d+"})
     * 
     * 
     * @OA\RequestBody(
     * @Model(type=User::class)
     * )
     * 
     * @param integer $id
     * @param EntityManagerInterface $em
     * @param UserRepository $repo
     * @param AssociationRepository $assorepo
     * @return Response
     */
    public function updateJoinAsso(Request $request, int $id, EntityManagerInterface $em, UserRepository $repo, AssociationRepository $associationRepository): JsonResponse
    {
     
        
        $jsonContent = $request->getContent();
    

        $updatedUser = json_decode($jsonContent);
        // dd($updatedUser);
       

        $user = $repo->find($id);
      

        $association = $associationRepository->find($updatedUser->associationMember);

        $user->setAssociation($association);
        
        
        $em->flush();
        return $this->json($user, Response::HTTP_OK, [], ["groups" => ["api_user"]]);
    }

    /**
     * Registre to an event
     * 
     * @Route("/event/{id}", name="join-event", methods={"PATCH"}, requirements={"id":"\d+"})
     * 
     * 
     * @OA\RequestBody(
     * @Model(type=User::class)
     * )
     * 
     * @param integer $id
     * @param EntityManagerInterface $em
     * @param UserRepository $repo
     * @param EventRepository $eventRepo
     * @return Response
     */
    public function updateJoinEvent(Request $request, int $id, EntityManagerInterface $em, UserRepository $repo, EventRepository $eventRepo): JsonResponse
    {
     
        
        $jsonContent = $request->getContent();
    

        $updatedUser = json_decode($jsonContent);
        //dd($updatedUser);
       

        $user = $repo->find($id);
      

        $event = $eventRepo->find($updatedUser->users);

        $user->addEvent($event);
        
        
        $em->flush();
        return $this->json($user, Response::HTTP_OK, [], ["groups" => ["api_user"]]);
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
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
        ): JsonResponse
    {
        // Récupérer le contenu JSON
        $jsonContent = $request->getContent();

        // Désérialiser (convertir) le JSON en entité Doctrine User
        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        // get the no hashed password
        $plaintextPassword = $user->getPassword();

        // hashed the password with hashPassword()
        $hashedPassword = $hasher->hashPassword($user, $plaintextPassword); 

        // set the user hashed password 
        $user->setPassword($hashedPassword);
        // dd($user);
         
        // On sauvegarde l'entité
        $em->persist($user, $hashedPassword);
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
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * 
     * @OA\RequestBody(
     *     @Model(type=User::class)
     * )
     * 
     * @OA\Response(
     *     response=201,
     *     description="delete user",
     *     @OA\JsonContent(
     *          ref=@Model(type=User::class, groups={"api_user"})
     *      )
     * )
     * 
     */
    public function delete($id, Request $request, User $user, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
    
        if($user = $userRepository->find($id)){

            $userRepository->remove($user);
   
            $em->flush();
        }
        return $this->json($user, Response::HTTP_OK, [], ["groups" => ["api_user"]]);
    }
}
