<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\CategoryRepository;

class CategoryController extends AbstractController
{
    #[Route('/api/categories', name: 'categories', methods: ['GET'])]
    public function getBookCategories(SerializerInterface $serializer, CategoryRepository $catRepo): JsonResponse
    {
        $categoryList = $catRepo->findAll();
        // Only take the categories, without the books li
        $jsonCategoryList = $serializer->serialize($categoryList, 'json', ['groups' => 'getCategories']);
        return new JsonResponse($jsonCategoryList, Response::HTTP_OK, [], true);
        
    }
}