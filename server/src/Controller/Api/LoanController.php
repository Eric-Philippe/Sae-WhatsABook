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

use App\Repository\LoanRepository;

class LoanController extends AbstractController
{
    #[Route('/api/loan/me/current/{userId}', name: 'current_loan_me', methods: ['GET'])]
    public function getUserCurrentLoans(string $userId, LoanRepository $loanRepo, Request $req, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $loans = $loanRepo->findBy(['member' => $userId, 'returnDate' => null]);
        $data = $serializer->serialize($loans, 'json', ['groups' => 'getLoans']);
        // Filter only the ones that contains a returnDate equals to null

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/loan/me/returned/{userId}', name: 'returnd_loan_me', methods: ['GET'])]
    public function getUserReturnedLoans(string $userId, LoanRepository $loanRepo, Request $req, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $qb = $loanRepo->createQueryBuilder('l');
        $loans = $qb->where('l.member = :userId')
                     ->andWhere($qb->expr()->isNotNull('l.returnDate'))
                     ->setParameter('userId', $userId)
                     ->getQuery()
                     ->getResult();
    
        $data = $serializer->serialize($loans, 'json', ['groups' => 'getLoans']);
    
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}