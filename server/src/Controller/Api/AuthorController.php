<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\AuthorRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AuthorController extends AbstractController
{
    # {{URL}}/api/author/1
    #[Route('/api/author/{id}', name: 'detailAuthor', methods: ['GET'])]
    public function getDetailBook(string $id, SerializerInterface $serializer, AuthorRepository $authorRepo): JsonResponse
    {
        $author = $authorRepo->find($id);
    
        if ($author) {
            $jsonAuthor = $serializer->serialize($author, 'json', ['groups' => 'getAuthors']);
            return new JsonResponse($jsonAuthor, Response::HTTP_OK, [], true);
        }
    
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}