<?php

namespace App\Controller\Api;

use App\Repository\MemberRepository;
use App\Utils\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{

    private $memberRepo;
    private $userPasswordHasher;

    public function __construct(MemberRepository $memberRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->memberRepo = $memberRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    #[Route('/api/user/me', name: 'user_me', methods: ['GET'])]
    public function getConnectedUser(Request $request, MemberRepository $memberRepository): JsonResponse
    {
        
        $user = Utils::getMemberFromRequest($request, $memberRepository);
        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'role' => $user->getRoles()[0],
        ]);


    }

    #[Route('/api/user/me/full', name: 'user_full', methods: ['GET'])]
    public function getFullUser(Request $request, MemberRepository $memberRepository): JsonResponse
    {
        
        $user = Utils::getMemberFromRequest($request, $memberRepository);
        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'role' => $user->getRoles()[0],
            'creationDate' => $user->getCreationDate(),
            'birthDate' => $user->getBirthDate(),
            'address' => $user->getAdress(),   
            'phone' => $user->getPhoneNumber(),
            'photoLink' => $user->getPhotoLink(),       
        ]);


    }

    #[Route('/api/user/me/update', name: 'user_update', methods: ['PUT'])]
    public function updateUserInfo(Request $request, MemberRepository $memberRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $userId = Utils::getMemberFromRequest($request, $memberRepository)->getId();

        $data = json_decode($request->getContent(), true);

        if (empty($data['firstname']) || empty($data['lastname']) || empty($data['phone']) || empty($data['email']) || empty($data['address'])) {
            return new JsonResponse(['status' => 'Missing parameters'], 400);
        }

        $user = $memberRepository->findOneBy(['id' => $userId]);

        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setPhoneNumber($data['phone']);
        $user->setEmail($data['email']);
        $user->setAdress($data['address']);

        $entityManager->flush();

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'role' => $user->getRoles()[0],
            'creationDate' => $user->getCreationDate(),
            'birthDate' => $user->getBirthDate(),
            'address' => $user->getAdress(),   
            'phone' => $user->getPhoneNumber(),
            'photoLink' => $user->getPhotoLink(),       
        ]);

    }

    #[Route('/api/user/password/update', name: 'password_update', methods: ['PUT'])]
    public function updateUserPassword(Request $request, MemberRepository $memberRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $userId = Utils::getMemberFromRequest($request, $memberRepository)->getId();

        $data = json_decode($request->getContent(), true);

        if (empty($data['password']) || empty($data['newPassword'])) {
            return new JsonResponse(['status' => 'Missing parameters'], 400);
        }

        $user = $memberRepository->findOneBy(['id' => $userId]);

        if (!$this->userPasswordHasher->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['status' => 'Wrong password'], 400);
        }

        $user->setPassword($this->userPasswordHasher->hashPassword($user, $data['newPassword']));

        $entityManager->flush();

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'role' => $user->getRoles()[0],
            'creationDate' => $user->getCreationDate(),
            'birthDate' => $user->getBirthDate(),
            'address' => $user->getAdress(),   
            'phone' => $user->getPhoneNumber(),
            'photoLink' => $user->getPhotoLink(),       
        ]);

    }

}
