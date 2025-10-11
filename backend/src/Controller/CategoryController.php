<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CategoryController extends AbstractController
{
    // #[Route('/category', name: 'app_category')]

    private EntityManagerInterface $em;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        // $this->categoryRepository = $categoryRepository;
        $this->em = $em;
        $this->serializer = $serializer;
    }
    public function index(CategoryRepository $categoryRepository, ): Response
    {
        //   $categories = $categoryRepository->find(46);
        $categories = $this->em->getRepository(Category::class)->findAll();
        $json = $this->serializer->serialize($categories, 'json', ['groups' => ['read']]);

        // return new JsonResponse($json, 200, [], true);
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    public function edit(CategoryRepository $categoryRepository, ): Response
    {
        var_dump('edit function called');
        die;
    }

    public function destroy(CategoryRepository $categoryRepository, ): Response
    {
        var_dump('destroy function called');
        die;
    }
}
