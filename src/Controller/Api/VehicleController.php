<?php

namespace App\Controller\Api;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class VehicleController extends AbstractController
{
    #[Route('/api/brands', name: 'get_vehicle_brands', methods: ['GET'])]
    #[OA\Get(
        description: "Returns a list of distinct vehicle brands",
        summary: "Get vehicle brands",
        tags: ["Vehicles"]
    )]
    #[OA\Response(
        response: 200,
        description: "List of vehicle brands",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(type: "string")
        )
    )]
    public function getVehicleBrands(EntityManagerInterface $entityManager): JsonResponse
    {
        $brands = $entityManager->getRepository(Vehicle::class)->createQueryBuilder('v')
            ->select('DISTINCT v.brand')
            ->getQuery()
            ->getResult();

        return new JsonResponse($brands, Response::HTTP_OK);
    }

    #[Route('/api/models/{brand}', name: 'get_vehicle_models', methods: ['GET'])]
    #[OA\Get(
        description: "Returns a list of distinct vehicle models for a given brand",
        summary: "Get vehicle models by brand",
        tags: ["Vehicles"]
    )]
    #[OA\Parameter(
        name: "brand",
        description: "The vehicle brand",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\Response(
        response: 200,
        description: "List of vehicle models",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(type: "string")
        )
    )]
    public function getVehicleModels(string $brand, EntityManagerInterface $entityManager): JsonResponse
    {
        $models = $entityManager->getRepository(Vehicle::class)->createQueryBuilder('v')
            ->select('DISTINCT v.model')
            ->where('v.brand = :brand')
            ->setParameter('brand', $brand)
            ->getQuery()
            ->getResult();

        return new JsonResponse($models, Response::HTTP_OK);
    }

    #[Route('/api/vehicle/{vin}', name: 'get_vehicle_by_vin', methods: ['GET'])]
    #[OA\Get(
        description: "Returns vehicle information for a given VIN",
        summary: "Get vehicle by VIN",
        tags: ["Vehicles"]
    )]
    #[OA\Parameter(
        name: "vin",
        description: "The VIN of the vehicle",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\Response(
        response: 200,
        description: "Vehicle data",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(
                properties: [
                    new OA\Property(property: "id", type: "integer"),
                    new OA\Property(property: "person", type: "string"),
                    new OA\Property(property: "regAddrKoatuu", type: "integer"),
                    new OA\Property(property: "operCode", type: "integer"),
                    new OA\Property(property: "operName", type: "string"),
                    new OA\Property(property: "dReg", type: "string", format: "date-time"),
                    new OA\Property(property: "depCode", type: "integer"),
                    new OA\Property(property: "dep", type: "string"),
                    new OA\Property(property: "brand", type: "string"),
                    new OA\Property(property: "model", type: "string"),
                    new OA\Property(property: "vin", type: "string"),
                    new OA\Property(property: "makeYear", type: "integer"),
                    new OA\Property(property: "color", type: "string"),
                    new OA\Property(property: "kind", type: "string"),
                    new OA\Property(property: "body", type: "string"),
                    new OA\Property(property: "purpose", type: "string"),
                    new OA\Property(property: "fuel", type: "string"),
                    new OA\Property(property: "capacity", type: "number", format: "float", nullable: true),
                    new OA\Property(property: "ownWeight", type: "integer"),
                    new OA\Property(property: "totalWeight", type: "integer"),
                    new OA\Property(property: "nRegNew", type: "string"),
                ],
                type: "object"
            )
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Vehicle not found",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "message", type: "string", example: "Vehicle(s) not found")
            ]
        )
    )]
    public function getVehicleByVin(
        string $vin,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ): JsonResponse {
        $vehicles = $entityManager->getRepository(Vehicle::class)->findBy(['vin' => $vin]);

        if (empty($vehicles)) {
            return new JsonResponse(['message' => 'Vehicle(s) not found'], Response::HTTP_NOT_FOUND);
        }

        $data = $serializer->serialize($vehicles, 'json');

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}
