<?php

namespace App\Controller\Api;

use App\Entity\Reservation;
use App\Entity\Suggestion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\SuggestionRepository;
use App\Repository\MemberRepository;
use App\Utils\Utils;

class SuggestionController extends AbstractController
{
    #[Route('/api/suggestion/create', name: 'suggestion_create', methods: ['POST'])]
    public function createReservation(SuggestionRepository $suggRepo, MemberRepository $memberRepo,  Request $req, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        // Get the request data wich contains the bookId and the userId
        $data = json_decode($req->getContent(), true);
        $member_id = $data['memberId'];
        $book_title = $data['bookTitle'];
        $book_authors = $data['bookAuthors'];
        $bookEditor = $data['bookEditor'];
        $bookReleaseDate = $data['bookReleaseDate'];
        $bookDescription = $data['bookDescription'];
        $details = $data['details'];


        if (empty($member_id) || empty($book_title) || empty($book_authors) || empty($bookEditor) || empty($bookReleaseDate) || empty($bookDescription) || empty($details)) {
            return new JsonResponse('Missing required data', Response::HTTP_BAD_REQUEST);
        }

        // Get the member
        $member = $memberRepo->find($member_id);

        if (!$member) {
            return new JsonResponse('Member not found', Response::HTTP_NOT_FOUND);
        }

        $suggestion = new Suggestion();
        $suggestion->setId(Utils::generateUUID());
        $suggestion->setMember($member);
        $suggestion->setTitle($book_title);
        $suggestion->setAuthors($book_authors);
        $suggestion->setEditor($bookEditor);
        $suggestion->setReleaseDate(new \DateTime($bookReleaseDate));
        $suggestion->setDescription($bookDescription);
        $suggestion->setDetails($details);
        $em->persist($suggestion);
        $em->flush();

        return new JsonResponse('Suggestion created', Response::HTTP_CREATED);
    }
}