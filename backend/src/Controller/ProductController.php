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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use App\Form\ProductType;

final class ProductController extends AbstractController
{

    private EntityManagerInterface $em;
    private SerializerInterface $serializer;
    private CsrfTokenManagerInterface $csrfTokenManager;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, CsrfTokenManagerInterface $csrfTokenManager)
    {
        // $this->categoryRepository = $categoryRepository;
        $this->em = $em;
        $this->serializer = $serializer;
        $this->csrfTokenManager = $csrfTokenManager;

    }
    // #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        $products = $this->em->getRepository(Product::class)->findAllActive();
        // $json = $this->serializer->serialize($products, 'json', ['groups' => ['read']]);
        // return new JsonResponse($json, 200, [], true);

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    public function create(Request $request): Response
    {
        $product = new Product();
        $product->setCategory(null);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash('success', 'Product saved!');

            return $this->redirectToRoute('product_create'); // Adjust route as needed

        }
        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
            // 'permissions' => $this->em->getRepository(Permission::class)->findAllActive(),
        ]);
    }

    public function edit(Product $product, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Product updated successfully.');
            return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
            'permission' => $product,
        ]);
    }

    public function destroy(Request $request, Product $product): Response
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('delete' . $product->getId(), $submittedToken))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }
        // $this->em->remove($category);

        $product->softDelete();
        $this->em->flush();
        $this->addFlash('success', 'Product deleted successfully.');
        return $this->redirectToRoute('products');
    }
}
