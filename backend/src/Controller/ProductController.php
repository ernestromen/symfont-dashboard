<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Product;

final class ProductController extends AbstractController
{

    private EntityManagerInterface $em;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        // $this->categoryRepository = $categoryRepository;
        $this->em = $em;
        $this->serializer = $serializer;
    }
    // #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        $products = $this->em->getRepository(Product::class)->findAll();
        $json = $this->serializer->serialize($products, 'json', ['groups' => ['read']]);
        // return new JsonResponse($json, 200, [], true);

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    public function create(): Response
    {
        var_dump('create function called');
        die;
    }

    public function edit(ProductRepository $productRepository, ): Response
    {
        var_dump('edit function called');
        die;
    }

    public function destroy(ProductRepository $productRepository, ): Response
    {
        var_dump('destroy function called');
        die;
    }
}
