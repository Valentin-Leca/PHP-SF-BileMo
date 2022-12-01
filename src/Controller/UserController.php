<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Route('/api')]
class UserController extends AbstractController {

    #[Route('/users/{page}', name: 'get_users', methods: ['GET'])]
    #[IsGranted('ROLE_CUSTOMER', message: 'Vous n\'avez pas les droits suffisants pour consulter tous vos utilisateurs.')]
    public function getAllUsers(UserRepository $userRepository, SerializerInterface $serializer, PaginatorInterface $paginator,
                                Request $request, TagAwareCacheInterface $cache): JsonResponse {

        $this->isGranted('VIEW', User::class);

        $allUsers = $userRepository->findBy(['customer' => $this->getUser()]);

        $page = $request->get('page', 1);

        $idCache = "getAllUsers-page=".$page."nbUser=10";

        $usersList = $cache->get($idCache, function (ItemInterface $item) use ($allUsers, $paginator, $page) {
            echo ("Création du cache !\n");
            $item->tag("usersCache");
            return $paginator->paginate(
                $allUsers,
                $page,
                10
            );
        });

        $context = SerializationContext::create()->setGroups(["getUser"]);
        $jsonAllUsers = $serializer->serialize($usersList->getItems(), 'json', $context);

        return new JsonResponse(
            $jsonAllUsers,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/user/{id}', name: 'get_user', methods: ['GET'])]
    #[IsGranted('ROLE_CUSTOMER', message: 'Vous n\'avez pas les droits suffisants pour consulter un utilisateur.')]
    public function getOneUser(User $user, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse {

        $this->denyAccessUnlessGranted('VIEW', $user);

        $idCache = "getOneUser-".$user->getId();

        $user = $cache->get($idCache, function (ItemInterface $item) use ($user) {
            echo ("Création du cache !\n");
            $item->tag("userCache");
            return $user;
        });

        $context = SerializationContext::create()->setGroups(["getUser", "getCustomer"]);
        $jsonUser = $serializer->serialize($user, 'json', $context);

        return new JsonResponse(
            $jsonUser,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/user', name: 'create_user', methods: ['POST'])]
    #[IsGranted('ROLE_CUSTOMER', message: 'Vous n\'avez pas les droits suffisants pour créer un utilisateur.')]
    public function createUser(SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManager,
                               UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator): JsonResponse {

        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setCreatedAt(date_create_immutable());
        $user->setCustomer($this->getUser());

        // On vérifie les erreurs
        $errors = $validator->validate($user);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        $context = SerializationContext::create()->setGroups(["getUser"]);
        $jsonUser = $serializer->serialize($user, 'json', $context);

        $location = $urlGenerator->generate('get_user', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse(
            $jsonUser,
            Response::HTTP_CREATED,
            ['location' => $location],
            true
        );
    }

    #[Route('/user/update/{id}', name: 'update_user', methods: ['PUT'])]
    #[IsGranted('ROLE_CUSTOMER', message: 'Vous n\'avez pas les droits suffisants pour mettre à jour un utilisateur.')]
    public function updateUser(SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManager,
                               User $currentUser, UrlGeneratorInterface $urlGenerator, TagAwareCacheInterface $cache): JsonResponse {

        $this->denyAccessUnlessGranted('UPDATE', $currentUser);

        $cache->invalidateTags(['usersCache', 'userCache']);

        $newUser = $serializer->deserialize($request->getContent(), User::class, 'json');

        $currentUser->setName($newUser->getName());
        $currentUser->setFirstname($newUser->getFirstname());

        $entityManager->persist($currentUser);
        $entityManager->flush();

        $context = SerializationContext::create()->setGroups(["getUser"]);
        $jsonUser = $serializer->serialize($currentUser, 'json', $context);

        $location = $urlGenerator->generate('get_user', ['id' => $currentUser->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse(
            $jsonUser,
            Response::HTTP_OK,
            ['location' => $location],
            true
        );

    }

    #[Route('/user/delete/{id}', name: 'delete_user', methods: ['DELETE'])]
    #[IsGranted('ROLE_CUSTOMER', message: 'Vous n\'avez pas les droits suffisants pour supprimer un utilisateur.')]
    public function deleteUser(User $user, EntityManagerInterface $entityManager, TagAwareCacheInterface $cache): JsonResponse {

        $this->denyAccessUnlessGranted('DELETE', $user);

        $cache->invalidateTags(['usersCache', 'userCache']);
        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}
