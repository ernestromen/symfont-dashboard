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
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\SlugGenerator;

final class UserController extends AbstractController
{

    private EntityManagerInterface $em;
    private SerializerInterface $serializer;
    private Security $security;
    private AuthorizationCheckerInterface $authorizationChecker;
    private CsrfTokenManagerInterface $csrfTokenManager;

    private UserPasswordHasherInterface $passwordHasher;

    private SlugGenerator $slugGenerator;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, Security $security, AuthorizationCheckerInterface $authorizationChecker, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordHasherInterface $passwordHasher,SlugGenerator $slugGenerator)
    {
        // $this->categoryRepository = $categoryRepository;
        $this->em = $em;
        $this->serializer = $serializer;
        $this->security = $security;
        $this->authorizationChecker = $authorizationChecker;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordHasher = $passwordHasher;
        $this->slugGenerator = $slugGenerator;
    }

    // #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        $users = $this->em->getRepository(User::class)->findAllActive();
        $token = $this->container->get('security.token_storage')->getToken();
        $json = $this->serializer->serialize($users, 'json');
        // return new JsonResponse($json, 200, [], true);

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    public function create(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();

            // Hash the password
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'User saved!');

            return $this->redirectToRoute('user_create');
        }
        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit(User $user, Request $request): Response
    {
        $currentUser = $this->security->getUser();

        $form = $this->createForm(UserType::class, $user, [
            'ROLE_SUPER_ADMIN' => $this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'User updated successfully.');
            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    public function destroy(Request $request, User $user): Response
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('delete' . $user->getId(), $submittedToken))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }
        // $this->em->remove($permission);
        $user->softDelete();
        $this->em->flush();
        $this->addFlash('success', 'User deleted successfully.');
        return $this->redirectToRoute('users');
    }
}