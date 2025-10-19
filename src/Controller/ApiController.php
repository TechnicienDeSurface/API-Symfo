<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use OpenApi\Attributes as OA;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Nelmio\ApiDocBundle\Annotation\Security;

class ApiController extends AbstractController
{
    #[Route('/api/v1/users', name: 'users', methods: ['GET'])]
    #[OA\Get(
        path: '/api/v1/users',
        summary: 'Récupère la liste paginée des utilisateurs',
        description: 'Retourne la liste des utilisateurs avec pagination. Accessible uniquement aux administrateurs.',
        tags: ['Users']
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'Numéro de la page à récupérer',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 1, minimum: 1)
    )]
    #[OA\Parameter(
        name: 'limit',
        in: 'query',
        description: 'Nombre d\'utilisateurs par page',
        required: false,
        schema: new OA\Schema(type: 'integer', default: 3, minimum: 1, maximum: 100)
    )]
    #[OA\Response(
        response: 200,
        description: 'Liste des utilisateurs récupérée avec succès',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                type: 'object',
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                    new OA\Property(
                        property: 'roles',
                        type: 'array',
                        items: new OA\Items(type: 'string'),
                        example: ['ROLE_USER', 'ROLE_ADMIN']
                    )
                ]
            )
        )
    )]
    #[OA\Response(
        response: 403,
        description: 'Accès refusé - Réservé aux administrateurs',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'error'),
                new OA\Property(property: 'message', type: 'string', example: 'Access denied')
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Non authentifié - Token JWT manquant ou invalide'
    )]
    public function getUsers(UserRepository $userRepository, Request $request, TagAwareCacheInterface $cachePool): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(["status" => "error", "message" => "Access denied"], status: Response::HTTP_FORBIDDEN);
        }

        $page = $request->get(key: 'page', default: 1);
        $limit = $request->get(key: 'limit', default: 3);
        $cacheIdentifier = "getAllUsers-" . $page . "-" . $limit;
        $userList = $cachePool->get(
            $cacheIdentifier,
            function (ItemInterface $item) use ($userRepository, $page, $limit) {
                $item->tag(tags: "userCache");
                return $userRepository->findAllWithPagination($page, $limit);
            }
        );
        return $this->json(data: $userList, status: Response::HTTP_OK, headers: [], context: ['groups' => 'getUsers']);
    }


    #[Route('/api/v1/user/delete/{id}', name: 'deleteUser', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/v1/user/delete/{id}',
        summary: 'Supprime un utilisateur',
        description: 'Supprime définitivement un utilisateur de la base de données. Accessible uniquement aux administrateurs.',
        tags: ['Users']
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID de l\'utilisateur à supprimer',
        required: true,
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\Response(
        response: 200,
        description: 'Utilisateur supprimé avec succès',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success')
            ]
        )
    )]
    #[OA\Response(
        response: 403,
        description: 'Accès refusé - Réservé aux administrateurs',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'error'),
                new OA\Property(property: 'message', type: 'string', example: 'Access denied')
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Utilisateur non trouvé'
    )]
    #[OA\Response(
        response: 401,
        description: 'Non authentifié - Token JWT manquant ou invalide'
    )]
    public function deleteUser(User $user, EntityManagerInterface $em): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(["status" => "error", "message" => "Access denied"], status: Response::HTTP_FORBIDDEN);
        }

        $em->remove($user);
        $em->flush();

        return new JsonResponse(["status" => "success"], status: Response::HTTP_OK);
    }

    #[Route('/api/v1/user/add', name: 'addUser', methods: ['POST'])]
    #[OA\Post(
        path: '/api/v1/user/add',
        summary: 'Crée un nouvel utilisateur',
        description: 'Crée un nouvel utilisateur dans la base de données',
        tags: ['Users']
    )]
    #[OA\RequestBody(
        required: true,
        description: 'Données du nouvel utilisateur',
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'newuser@example.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'SecurePass123!'),
                new OA\Property(
                    property: 'roles',
                    type: 'array',
                    items: new OA\Items(type: 'string'),
                    example: ['ROLE_USER']
                ),
                new OA\Property(
                    property: 'subscription_to_newsletter',
                    type: 'boolean',
                    example: true
                )
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Utilisateur créé avec succès',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 5),
                new OA\Property(property: 'email', type: 'string', example: 'newuser@example.com'),
                new OA\Property(
                    property: 'roles',
                    type: 'array',
                    items: new OA\Items(type: 'string'),
                    example: ['ROLE_USER']
                ),
                new OA\Property(
                    property: 'subscription_to_newsletter',
                    type: 'boolean',
                    example: true
                )
            ]
        ),
        headers: [
            new OA\Header(
                header: 'Location',
                description: 'URL de l\'utilisateur créé',
                schema: new OA\Schema(type: 'string', example: 'http://localhost:8000/api/user/5')
            )
        ]
    )]
    #[OA\Response(
        response: 400,
        description: 'Données invalides'
    )]
    #[OA\Response(
        response: 401,
        description: 'Non authentifié - Token JWT manquant ou invalide'
    )]
    public function createUser(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setPassword(
            $passwordHasher->hashPassword($user, $user->getPassword())
        );

        $em->persist($user);
        $em->flush();

        $location = $urlGenerator->generate(
            name: 'user',
            parameters: ['id' => $user->getId()],
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $this->json(
            data: $user,
            status: Response::HTTP_CREATED,
            headers: ['Location' => $location],
            context: ['groups' => 'getUser']
        );
    }

    #[Route('/api/v1/user/edit/{id}', name: 'editUser', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/v1/user/edit/{id}',
        summary: 'Modifie un utilisateur existant',
        description: 'Met à jour les informations d\'un utilisateur existant',
        tags: ['Users']
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'ID de l\'utilisateur à modifier',
        required: true,
        schema: new OA\Schema(type: 'integer', example: 1)
    )]
    #[OA\RequestBody(
        required: true,
        description: 'Nouvelles données de l\'utilisateur',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'updated@example.com'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'NewSecurePass123!'),
                new OA\Property(
                    property: 'roles',
                    type: 'array',
                    items: new OA\Items(type: 'string'),
                    example: ['ROLE_USER', 'ROLE_ADMIN']
                ),
                new OA\Property(
                    property: 'subscription_to_newsletter',
                    type: 'boolean',
                    example: true
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Utilisateur modifié avec succès',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'success')
            ]
        ),
        headers: [
            new OA\Header(
                header: 'Location',
                description: 'URL de l\'utilisateur modifié',
                schema: new OA\Schema(type: 'string', example: 'http://localhost:8000/api/user/1')
            )
        ]
    )]
    #[OA\Response(
        response: 400,
        description: 'Données invalides'
    )]
    #[OA\Response(
        response: 404,
        description: 'Utilisateur non trouvé'
    )]
    #[OA\Response(
        response: 401,
        description: 'Non authentifié - Token JWT manquant ou invalide'
    )]
    public function updateProject(
        Request $request,
        SerializerInterface $serializer,
        User $currentUser,
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse {
        $updatedUser = $serializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentUser]
        );

        $em->persist($updatedUser);
        $em->flush();

        $location = $urlGenerator->generate(
            name: 'user',
            parameters: ['id' => $updatedUser->getId()],
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $this->json(
            ['status' => 'success'],
            Response::HTTP_OK,
            ['Location' => $location]
        );
    }
}
