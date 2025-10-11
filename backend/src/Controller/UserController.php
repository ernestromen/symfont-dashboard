<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

final class UserController extends AbstractController
{

    private EntityManagerInterface $em;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        // $this->categoryRepository = $categoryRepository;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    // #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        $users = $this->em->getRepository(User::class)->findAll();
        $json = $this->serializer->serialize($users, 'json');
        // return new JsonResponse($json, 200, [], true);

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }
}