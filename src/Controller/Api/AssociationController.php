<?php

namespace App\Controller\Api;

use App\Entity\Association;
use App\Entity\Event;
use App\Entity\User;
use App\Models\CustomJsonError;
use OpenApi\Annotations as OA;
use App\Repository\AssociationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;





/**
 * @Route("/api/associations", name="api_associations_")
 * 
 * @OA\Tag(name="Udruga API : Associations")
 */
class AssociationController extends JsonController
{
    /**
     * List associations
     * @Route("", name="browse", methods={"GET"})
     * 
     */
    public function index(AssociationRepository $repo): Response
    {
        $allAssociations = $repo->findAll();

        return $this->json200($allAssociations,
        ["api_association"]
    
    );
    }

    /**
     *Read association by ID
     *
     * @Route("/{id}", name="read", methods={"GET"}, requirements={"id":"\d+"})
     * 
     */
    public function association(Association $association=null): Response
    {
        /* si le parm converter n'a rien avec l'id $association est null */
        if ($association === null){
            //on renvoie du JSON 404
            return $this->json404("il n'existe pas d'association avec cet ID");
        }
        return $this->json200($association, ["api_association"]);
    }

      /**
     * Mies à jour d'une association
     * 
     * @Route("/edit/{id}", name="edit", methods={"PUT"}, requirements={"id":"\d+"})
     * 
     * @OA\RequestBody(
     * @Model(type=User::class)
     * )
     * 
     * @param integer $id
     * @param EntityManagerInterface $em
     * @param AssociationRepository $repo
     * @return Response
     */
    public function update(Association $association,Request $request, int $id, EntityManagerInterface $em, AssociationRepository $repo): JsonResponse
    {
     
        
        $jsonContent = $request->getContent();
    

        $updatedAssociation = json_decode($jsonContent);
        // dd($updatedUser);
       

        $association = $repo->find($id);
        $association->setName($updatedAssociation->name);
        $association->setDescription($updatedAssociation->description);
        $association->setSiren($updatedAssociation->siren);
        $association->setEmail($updatedAssociation->email);
        $association->setRegistrationCode($updatedAssociation->password);
        $association->setAdmin($updatedAssociation->admin);
    

        $em->flush();
        return $this->json200($association, ["api_association"]);
    }

    /**
     * Create association
     *
     * @Route("", name="add", methods={"POST"})
     * 
     * @OA\RequestBody(
     * @Model(type=Association::class)
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
        $association = $serializer->deserialize($jsonContent, Association::class, 'json');
         
        // On sauvegarde l'entité
        $em->persist($association);
        $em->flush();

        // TODO : return 201
        return $this->json(
            $association,
            // je précise que tout est OK de mon coté en précisant que la création s'est bien passée
            // 201
            Response::HTTP_CREATED,
            [],
            [
                "groups" => 
                [
                    "api_association"
                ]
            ]
        );
    }

    /**
     * Delete association
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * 
     * @OA\RequestBody(
     *     @Model(type=Association::class)
     * )
     * 
     * @OA\Response(
     *     response=201,
     *     description="delete association",
     *     @OA\JsonContent(
     *          ref=@Model(type=Association::class, groups={"api_association"})
     *      )
     * )
     * 
     */
    public function delete($id, Request $request, Association $association, AssociationRepository $associationRepository, EntityManagerInterface $em): Response
    {
    
        if($association = $associationRepository->find($id)){

            $associationRepository->remove($association);
            // Surtout ne pas faire un persist si non cela modifie juste l'id
            $em->flush();
        }
        return $this->json200($association, ["api_association"]);
    }
}  











