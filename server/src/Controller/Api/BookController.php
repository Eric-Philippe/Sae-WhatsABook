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
            $jsonBook = $serializer->serialize($book, 'json', ['groups' => 'getBooks']);
            return new JsonResponse($jsonBook, Response::HTTP_OK, [], true);
        }
    
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/books', name: 'books', methods: ['GET'])]
    public function getBookList(BookRepository $bookRepository, SerializerInterface $serializer): JsonResponse
    {
        $bookList = $bookRepository->findAll();
        $jsonBookList = $serializer->serialize($bookList, 'json',  ['groups' => 'getBooks']);
        return new JsonResponse($jsonBookList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/books', name:"createBook", methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour créer un livre')]
    public function createBook(Request $request, SerializerInterface $serializer, BookRepository $bookRepository): JsonResponse 
    {

       // TODO: Implement createBook() method.

        return new JsonResponse("NOT_IMPLEMENTED", Response::HTTP_CREATED, [], true);
   }

   #[Route('/api/books/search/author', name: 'searchBooksByAuthor', methods: ['GET'])]
    public function searchBooksByAuthor(Request $request, SerializerInterface $serializer, BookRepository $bookRepository)
    {
        // Get All the books
        $books = $bookRepository->findAll();

        // Get All the parameters (they can be null or not)
        $firstname = $request->query->get('firstname');
        $lastname = $request->query->get('lastname');
        $nationality = $request->query->get('nationality');
        $maxDate = $request->query->get('maxDate');
        $minDate = $request->query->get('minDate');
        // asc or desc
        $sort = $request->query->get('sort');
        // Max number of books to return
        $offset = $request->query->get('offset');

        // Filter the books taking into account the parameters that are not null
        $filteredBooks = [];

        for ($i = 0; $i < count($books); $i++) {
            $authors = $books[$i]->getAuthors();
            foreach ($authors as $author) {
                if ($firstname != null && $author->getFirstname() != $firstname) {
                    continue;
                }
                if ($lastname != null && $author->getLastname() != $lastname) {
                    continue;
                }
                if ($nationality != null && $author->getNationality() != $nationality) {
                    continue;
                }
                if ($maxDate != null && $author->getBirthDate() > $maxDate) {
                    continue;
                }
                if ($minDate != null && $author->getBirthDate() < $minDate) {
                    continue;
                }
                array_push($filteredBooks, $books[$i]);
            }

        }

        // Sort if needed
        if ($sort != null) {
            if ($sort == "asc") {
                usort($filteredBooks, function ($a, $b) {
                    return $a->getPublicationDate() > $b->getPublicationDate();
                });
            } else {
                usort($filteredBooks, function ($a, $b) {
                    return $a->getPublicationDate() < $b->getPublicationDate();
                });
            }
        }

        // Limit the number of books if needed
        if ($offset != null) {
            $filteredBooks = array_slice($filteredBooks, 0, $offset);
        }
        
        // Serialize the filtered books
        $jsonBooks = $serializer->serialize($filteredBooks, 'json', ['groups' => 'getBooks']);
        return new JsonResponse($jsonBooks, Response::HTTP_OK, [], true);
    }
}