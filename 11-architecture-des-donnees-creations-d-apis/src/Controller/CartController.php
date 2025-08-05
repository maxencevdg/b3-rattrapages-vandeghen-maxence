<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\CartItemRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    #[Route('/api/cart/{cartId}/add-product', name: 'api_cart_add_product', methods: ['POST'])]
    public function addToCart(
        int $cartId,
        Request $request,
        CartRepository $cartRepository,
        ProductRepository $productRepository,
        CartItemRepository $cartItemRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['productId']) || !isset($data['quantity'])) {
            return $this->json(['error' => 'productId and quantity are required'], 400);
        }

        $cart = $cartRepository->find($cartId);
        if (!$cart) {
            return $this->json(['error' => 'Cart not found'], 404);
        }

        $product = $productRepository->find($data['productId']);
        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        // Vérifier si le produit est déjà dans le panier
        $existingCartItem = $cartItemRepository->findOneBy([
            'cart' => $cart,
            'product' => $product
        ]);

        if ($existingCartItem) {
            // Incrémenter la quantité si le produit existe déjà
            $existingCartItem->setQuantity($existingCartItem->getQuantity() + $data['quantity']);
            $cartItem = $existingCartItem;
        } else {
            // Créer une nouvelle ligne de panier
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity($data['quantity']);
            $cartItem->setUnitPrice($product->getPrice());
            
            $entityManager->persist($cartItem);
        }

        $entityManager->flush();

        return $this->json([
            'id' => $cartItem->getId(),
            'productId' => $product->getId(),
            'productName' => $product->getName(),
            'quantity' => $cartItem->getQuantity(),
            'unitPrice' => $cartItem->getUnitPrice(),
            'totalPrice' => $cartItem->getQuantity() * $cartItem->getUnitPrice()
        ], 201);
    }

    #[Route('/api/cart/{cartId}/remove-product/{productId}', name: 'api_cart_remove_product', methods: ['DELETE'])]
    public function removeFromCart(
        int $cartId,
        int $productId,
        CartRepository $cartRepository,
        ProductRepository $productRepository,
        CartItemRepository $cartItemRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $cart = $cartRepository->find($cartId);
        if (!$cart) {
            return $this->json(['error' => 'Cart not found'], 404);
        }

        $product = $productRepository->find($productId);
        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        $cartItem = $cartItemRepository->findOneBy([
            'cart' => $cart,
            'product' => $product
        ]);

        if (!$cartItem) {
            return $this->json(['error' => 'Product not in cart'], 404);
        }

        $entityManager->remove($cartItem);
        $entityManager->flush();

        return $this->json(['success' => true], 200);
    }

    #[Route('/api/cart/{cartId}/validate', name: 'api_cart_validate', methods: ['POST'])]
    public function validateCart(
        int $cartId,
        CartRepository $cartRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $cart = $cartRepository->find($cartId);
        if (!$cart) {
            return $this->json(['error' => 'Cart not found'], 404);
        }

        $cart->setStatus('validated');
        $entityManager->flush();

        return $this->json([
            'id' => $cart->getId(),
            'status' => $cart->getStatus()
        ], 200);
    }
}
