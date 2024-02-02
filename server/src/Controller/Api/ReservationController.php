<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\BookRepository;
use App\Repository\ReservationRepository;

class ReservationController extends AbstractController
{
    #[Route('/api/reservations/me/{userId}', name: 'reservations_me', methods: ['GET'])]
    public function getMemberReservation(string $userId, ReservationRepository $resRepo, SerializerInterface $serializer): JsonResponse
    {
        // Find by member id
        $reservations = $resRepo->findBy(['member' => $userId]);
        $jsonResList = $serializer->serialize($reservations, 'json',  ['groups' => 'getMemberReservation']);
        return new JsonResponse($jsonResList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/reservations/book/{bookId}', name: 'reservations_id', methods: ['GET'])]
    public function getBookReservation(string $bookId, ReservationRepository $resRepo, SerializerInterface $serializer): JsonResponse
    {
        // Find by member id
        $reservations = $resRepo->findBy(['book' => $bookId]);
        $jsonResList = $serializer->serialize($reservations, 'json',  ['groups' => 'getBookReservation']);
        return new JsonResponse($jsonResList, Response::HTTP_OK, [], true);
    }
}