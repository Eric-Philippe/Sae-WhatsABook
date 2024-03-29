<?php

namespace App\Controller\Api;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\BookRepository;
use App\Repository\MemberRepository;
use App\Repository\ReservationRepository;
use App\Utils\Utils;

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

    #[Route('/api/reservations/create', name: 'reservations_create', methods: ['POST'])]
    public function createReservation(ReservationRepository $resRepo, MemberRepository $memberRepo, BookRepository $bookRepo,  Request $req, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        // Get the request data wich contains the bookId and the userId
        $data = json_decode($req->getContent(), true);
        $bookId = $data['bookId'];
        $memberId = $data['userId'];
        if (empty($bookId) || empty($memberId)) {
            return new JsonResponse('Missing required data', Response::HTTP_BAD_REQUEST);
        }

        // Get all the reservations for the member
        $reservations = $resRepo->findBy(['member' => $memberId]);
        $reservationsCount = count($reservations);
        if ($reservationsCount >= 3) {
            return new JsonResponse('You can not reserve more than 3 books', Response::HTTP_BAD_REQUEST);
        }

        // Get the member
        $member = $memberRepo->find($memberId);
        if (!$member) {
            return new JsonResponse('Member not found', Response::HTTP_NOT_FOUND);
        }

        // Get the book
        $book = $bookRepo->find($bookId);
        if (!$book) {
            return new JsonResponse('Book not found', Response::HTTP_NOT_FOUND);
        }

        // Create the reservation
        $reservation = new Reservation();
        $reservation->setId(Utils::generateUUID());
        $reservation->setMember($member);
        $reservation->setBook($book);
        $reservation->setDateResa(new \DateTime());

        $em->persist($reservation);
        $em->flush();

        return new JsonResponse('Reservation created', Response::HTTP_CREATED);
    }

    #[Route('/api/reservations/cancel/{resId}', name: 'reservations_cancel', methods: ['DELETE'])]
    public function cancelReservation(string $resId, ReservationRepository $resRepo, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        // Get the reservation
        $reservation = $resRepo->find($resId);
        if (!$reservation) {
            return new JsonResponse('Reservation not found', Response::HTTP_NOT_FOUND);
        }

        // Delete the reservation
        $em->remove($reservation);
        $em->flush();

        return new JsonResponse('Reservation deleted', Response::HTTP_OK);
    }
}