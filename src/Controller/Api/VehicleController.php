<?php

namespace App\Controller\Api;

use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class VehicleController extends AbstractController
{
    #[Route('/api/brands', name: 'get_vehicle_brands', methods: ['GET'])]
    public function getVehicleBrands(EntityManagerInterface $entityManager): JsonResponse
    {
        $brands = $entityManager->getRepository(Vehicle::class)->createQueryBuilder('v')
            ->select('DISTINCT v.brand')
            ->getQuery()
            ->getResult();

        return new JsonResponse($brands, Response::HTTP_OK);
    }

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
