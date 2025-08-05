<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/api/products/{productId}/rate', name: 'api_product_rate', methods: ['POST'])]
    public function rateProduct(
        int $productId,
        Request $request,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            return $this->json(['error' => 'Rating must be between 1 and 5'], 400);
        }

        $product = $productRepository->find($productId);
        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        $product->setRating($data['rating']);
        $entityManager->flush();

        return $this->json([
            'id' => $product->getId(),
            'name' => $product->getName(),
            'rating' => $product->getRating()
        ], 200);
    }
} 