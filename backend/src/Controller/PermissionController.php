<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Permission;
use App\Form\PermissionType;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

final class PermissionController extends AbstractController
{

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, CsrfTokenManagerInterface $csrfTokenManager, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->requestStack = $requestStack;
    }

    private EntityManagerInterface $em;
    private SerializerInterface $serializer;

    private CsrfTokenManagerInterface $csrfTokenManager;



    public function create(Request $request): Response
    {
        $permission = new Permission();
        $form = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($permission);
            $this->em->flush();
            $this->addFlash('success', 'Permission created successfully.');
            return $this->redirectToRoute('permissions');
        }

        return $this->render('permission/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function index(): Response
    {
        $permissions = $this->em->getRepository(Permission::class)->findAllActive();
        $json = $this->serializer->serialize($permissions, 'json', ['groups' => ['read']]);
        // return new JsonResponse($json, 200, [], true);

        return $this->render('permission/index.html.twig', [
            'permissions' => $permissions,
        ]);
    }
    public function edit(Permission $permission, Request $request): Response
    {

        $form = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Permission updated successfully.');
            return $this->redirectToRoute('permission_edit', ['id' => $permission->getId()]);
        }

        return $this->render('permission/edit.html.twig', [
            'form' => $form->createView(),
            'permission' => $permission,
        ]);
    }


    public function destroy(Request $request, Permission $permission): Response
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('delete' . $permission->getId(), $submittedToken))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }
        // $this->em->remove($permission);
        $permission->softDelete();
        $this->em->flush();
        $this->addFlash('success', 'Permission deleted successfully.');
        return $this->redirectToRoute('permissions');
    }

    public function error(Request $request, RouterInterface $router): Response
    {
        $session = $request->getSession();
        $flashes = $session->getFlashBag()->peekAll();
        if (empty($flashes)) {
            // No flash messages — redirect somewhere else
            return new RedirectResponse($router->generate('app_home')); // Replace with your route
        }

        return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
    }

}
