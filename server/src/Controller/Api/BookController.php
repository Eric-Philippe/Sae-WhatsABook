<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\BookRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BookController extends AbstractController
{
    # {{URL}}/api/books/1
    #[Route('/api/books/{id}', name: 'detailBook', methods: ['GET'])]
    public function getDetailBook(string $id, SerializerInterface $serializer, BookRepository $bookRepository): JsonResponse
    {
        $book = $bookRepository->find($id);
    
        if ($book) {
            $jsonBook = $serializer->serialize($book, 'json');
            return new JsonResponse($jsonBook, Response::HTTP_OK, [], true);
        }
    
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/books', name: 'books', methods: ['GET'])]
    public function getBookList(BookRepository $bookRepository, SerializerInterface $serializer): JsonResponse
    {
        $bookList = $bookRepository->findAll();
        $jsonBookList = $serializer->serialize($bookList, 'json');
        return new JsonResponse($jsonBookList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/books', name:"createBook", methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour créer un livre')]
    public function createBook(Request $request, SerializerInterface $serializer, BookRepository $bookRepository): JsonResponse 
    {

       // TODO: Implement createBook() method.

        return new JsonResponse("NOT_IMPLEMENTED", Response::HTTP_CREATED, [], true);
   }
}