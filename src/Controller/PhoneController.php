<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Phone;
use App\Repository\PhoneRepository;
use JMS\Serializer\SerializationContext;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializerInterface;

#[Route('/api')]
class PhoneController extends AbstractController {

    #[Route('/phones/{page}', name: 'get_phones', methods: ['GET'])]
    #[IsGranted('ROLE_CUSTOMER', message: 'Vous n\'avez pas les droits suffisants pour consulter la liste des téléphones.')]
    public function getAllPhones(PhoneRepository $phoneRepository, SerializerInterface $serializer, PaginatorInterface $paginator,
                                 Request $request): Response {

        $allPhones = $phoneRepository->findAll();

        $page = $request->get('page', 1);

        $allUsersPaginated = $paginator->paginate(
            $allPhones,
            $page,
            10
        );

        $context = SerializationContext::create()->setGroups(["getPhones"]);
        $jsonAllPhones = $serializer->serialize($allUsersPaginated->getItems(), 'json', $context);

        return new JsonResponse(
            $jsonAllPhones,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/phone/{id}', name: 'get_phone', methods: ['GET'])]
    #[IsGranted('ROLE_CUSTOMER', message: 'Vous n\'avez pas les droits suffisants pour consulter un téléphone.')]
    public function getOnePhone(Phone $phone, PhoneRepository $phoneRepository, SerializerInterface $serializer): JsonResponse {

        $phone = $phoneRepository->findOneBy(['id' => $phone->getId()]);

        $context = SerializationContext::create()->setGroups(["getPhones"]);
        $jsonPhone = $serializer->serialize($phone, 'json', $context);

        return new JsonResponse(
            $jsonPhone,
            Response::HTTP_OK,
            [],
            true
        );
    }
}
