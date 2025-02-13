<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->validator = $validator;
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    #[OA\Post(
        description: "Register a new user by providing a username and password.",
        summary: "User registration",
        tags: ["Authentication"]
    )]
    #[OA\RequestBody(
        description: "User credentials for registration",
        required: true,
        content: new OA\JsonContent(
            required: ["username", "password"],
            properties: [
                new OA\Property(property: "username", type: "string", example: "john_doe"),
                new OA\Property(property: "password", type: "string", example: "password123")
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: "User successfully registered.",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "message", type: "string", example: "User registered successfully")
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: "Missing required fields or validation error.",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "error", type: "string", example: "Username and password are required")
            ]
        )
    )]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['username']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Username and password are required'], 400);
        }

        $user = new User();
        $user->setUsername($data['username']);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string)$errors], 400);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'User registered successfully'], 201);
    }
}
