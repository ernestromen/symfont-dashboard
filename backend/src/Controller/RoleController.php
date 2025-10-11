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

final class RoleController extends AbstractController
{
    private EntityManagerInterface $em;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        // $this->categoryRepository = $categoryRepository;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    public function create(Request $request): Response
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($role);
            $this->em->flush();
            $this->addFlash('success', 'Role created successfully.');
            return $this->redirectToRoute('roles');
        }

        return $this->render('role/create.html.twig', [
            'form' => $form->createView(),
            'permissions' => $this->em->getRepository(Permission::class)->findAllActive(),
        ]);
    }

    // #[Route('/role', name: 'app_role')]
    public function index(RoleRepository $roleRepository): Response
    {
        // $roles = $roleRepository->find(46);
        $roles = $this->em->getRepository(Role::class)->findAll();
        $json = $this->serializer->serialize($roles, 'json');

        // return new JsonResponse($json, 200, [], true);
        return $this->render('role/index.html.twig', [
            'roles' => $roles,
        ]);
    }
}
