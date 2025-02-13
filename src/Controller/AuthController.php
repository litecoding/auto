<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AuthController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['username', 'password'], // ✅ Передаємо масив
            properties: [
                new OA\Property(property: 'username', description: 'User login', type: 'string'),
                new OA\Property(property: 'password', description: 'User password', type: 'string')
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'User logged in successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'token', description: 'JWT token', type: 'string')
            ],
            type: 'object'
        )
    )]
    #[OA\Response(response: 400, description: 'Invalid credentials')]
    #[OA\Tag(name: 'Authentication')]
    public function login(Request $request, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        if (!$username || !$password) {
            return new JsonResponse(['error' => 'Username and password are required'], 400);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['error' => 'Invalid credentials'], 401);
        }

        $token = $JWTManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}
