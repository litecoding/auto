<?php

namespace App\Controller\Api;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;

class VehicleController extends AbstractController
{
    /**
     * @OA\Get(
     *     path="/api/brands",
     *     summary="Get vehicle brands",
     *     description="Returns a list of distinct vehicle brands",
     *     @OA\Response(
     *         response=200,
     *         description="List of vehicle brands",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     tags={"Vehicles"}
     * )
     */
    #[Route('/api/brands', name: 'get_vehicle_brands', methods: ['GET'])]
    public function getVehicleBrands(EntityManagerInterface $entityManager): JsonResponse
    {
        $brands = $entityManager->getRepository(Vehicle::class)->createQueryBuilder('v')
            ->select('DISTINCT v.brand')
            ->getQuery()
            ->getResult();

        return new JsonResponse($brands, Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/models/{brand}",
     *     summary="Get vehicle models by brand",
     *     description="Returns a list of distinct vehicle models for a given brand",
     *     @OA\Parameter(
     *         name="brand",
     *         in="path",
     *         required=true,
     *         description="The vehicle brand",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of vehicle models",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     ),
     *     tags={"Vehicles"}
     * )
     */
    #[Route('/api/models/{brand}', name: 'get_vehicle_models', methods: ['GET'])]
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

    /**
     * @OA\Get(
     *     path="/api/vehicle/{vin}",
     *     summary="Get vehicle by VIN",
     *     description="Returns vehicle information for a given VIN",
     *     @OA\Parameter(
     *         name="vin",
     *         in="path",
     *         required=true,
     *         description="The VIN of the vehicle",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehicle data",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vehicle not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     tags={"Vehicles"}
     * )
     */
    #[Route('/api/vehicle/{vin}', name: 'get_vehicle_by_vin', methods: ['GET'])]
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
