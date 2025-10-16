<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Role;
use App\Entity\Permission;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RoleType;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class RoleController extends AbstractController
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

    public function create(Request $request): Response
    {
        $role = new Role();

        $form = $this->createForm(RoleType::class, $role);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($role);
            $this->em->flush();

            $this->addFlash('success', 'Role saved!');

            return $this->redirectToRoute('role_create'); // Adjust route as needed

        }
        return $this->render('role/create.html.twig', [
            'form' => $form->createView(),
            'permissions' => $this->em->getRepository(Role::class)->findByAllActive(),
        ]);
    }
    // #[Route('/role', name: 'app_role')]
    public function index(RoleRepository $roleRepository): Response
    {
        // $this->denyAccessUnlessGranted('EDIT', $product);
        // $roles = $roleRepository->find(46);
        $roles = $this->em->getRepository(Role::class)->findByAllActive();
        $json = $this->serializer->serialize($roles, 'json');

        // return new JsonResponse($json, 200, [], true);
        return $this->render('role/index.html.twig', [
            'roles' => $roles,
        ]);
    }

    public function edit(Role $role, Request $request): Response
    {

        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Role updated successfully.');
            return $this->redirectToRoute('role_edit', ['id' => $role->getId()]);
        }

        return $this->render('role/edit.html.twig', [
            'form' => $form->createView(),
            'role' => $role,
        ]);
    }


    public function destroy(Request $request, Role $role): Response
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('delete' . $role->getId(), $submittedToken))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }
        // $this->em->remove($permission);
        $role->softDelete();
        $this->em->flush();
        $this->addFlash('success', 'Role deleted successfully.');
        return $this->redirectToRoute('roles');
    }
}
