<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\SecurityBundle\Security;


final class UserController extends AbstractController
{

    private EntityManagerInterface $em;
    private SerializerInterface $serializer;
    private Security $security;
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer,Security $security)
    {
        // $this->categoryRepository = $categoryRepository;
        $this->em = $em;
        $this->serializer = $serializer;
        $this->security = $security;
    }

    // #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        $users = $this->em->getRepository(User::class)->findAll();
        // var_dump($this->security->username);
        $token = $this->container->get('security.token_storage')->getToken();
        // dump($token);
        // die();
        $json = $this->serializer->serialize($users, 'json');
        // return new JsonResponse($json, 200, [], true);
    $user = $this->security->getUser();
    $roles = $user?->getRoles() ?? [];
    $currentUserRole = $this->security->getUser()->getRoles()[0];
    // var_dump( $user->getRoles()[0] );
    // die();

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'currentUserRole'=> $currentUserRole
        ]);
    }
}