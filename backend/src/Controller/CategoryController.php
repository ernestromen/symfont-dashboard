<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategoryType;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class CategoryController extends AbstractController
{
    // #[Route('/category', name: 'app_category')]

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
    public function index(CategoryRepository $categoryRepository, ): Response
    {

        $categories = $this->em->getRepository(Category::class)->findAllActive();
        // $categories = $this->em->getRepository(Category::class)->findAll();
        // $json = $this->serializer->serialize($categories, 'json', ['groups' => ['read']]);

        // return new JsonResponse($json, 200, [], true);
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }


    public function create(Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($category);
            $this->em->flush();

            $this->addFlash('success', 'Category saved!');

            return $this->redirectToRoute('category_create'); // Adjust route as needed

        }
        return $this->render('category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit(Category $category, Request $request): Response
    {

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Category updated successfully.');
            return $this->redirectToRoute('category_edit', ['id' => $category->getId()]);
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
            'permission' => $category,
        ]);
    }

    public function destroy(Request $request, Category $category): Response
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('delete' . $category->getId(), $submittedToken))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }
        // $this->em->remove($category);
        $category->softDelete();
        $this->em->flush();
        $this->addFlash('success', 'Category deleted successfully.');
        return $this->redirectToRoute('categories');
    }
}
