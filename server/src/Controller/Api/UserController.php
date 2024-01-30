<?php

namespace App\Controller\Api;

use App\Repository\MemberRepository;
use App\Utils\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserController extends AbstractController
{

    private $memberRepo;

    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepo = $memberRepository;
    }

    #[Route('/api/user', name: 'user', methods: ['POST'])]
    public function getConnectedUser(Request $request, MemberRepository $memberRepository): JsonResponse
    {
        
        $user = Utils::getMemberFromRequest($request, $memberRepository);
        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'roles' => $user->getRoles(),
        ]);


    }
}
