<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\User;
use App\Entity\Role;
use App\Entity\Permission;
use App\Entity\Category;
use App\Entity\Product;


final class ExportController extends AbstractController
{
    // #[Route('/export/users', name: 'export_users_csv')]

    private EntityManagerInterface $em;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        // $this->categoryRepository = $categoryRepository;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    private function csvExport(array $data, array $headers, string $filename): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($data, $headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            foreach ($data as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }


    public function exportTable(string $entity): StreamedResponse
    {
        $data = [];
        $headers = [];

        switch (strtolower($entity)) {
            case 'users':
                $users = $this->em->getRepository(User::class)->findAll();
                $headers = ['ID', 'Name', 'Created At', 'Updated At'];
                $data = array_map(fn($u) => [
                    $u->getId(),
                    $u->getUsername(),
                    $u->getCreatedAt()->format('Y-m-d H:i:s'),
                    $u->getUpdatedAt()->format('Y-m-d H:i:s'),

                ], $users);
                break;

            case 'roles':
                $roles = $this->em->getRepository(Role::class)->findAll();
                $headers = ['ID', 'Name', 'Created At', 'Updated At'];
                $data = array_map(fn($r) => [
                    $r->getId(),
                    $r->getName(),
                    $r->getCreatedAt()->format('Y-m-d H:i:s'),
                    $r->getUpdatedAt()->format('Y-m-d H:i:s'),
                ], $roles);
                break;

            case 'permissions':
                $permissions = $this->em->getRepository(Permission::class)->findAll();
                $headers = ['ID', 'Name', 'Created At', 'Updated At'];
                $data = array_map(fn($p) => [
                    $p->getId(),
                    $p->getName(),
                    $p->getCreatedAt()->format('Y-m-d H:i:s'),
                    $p->getUpdatedAt()->format('Y-m-d H:i:s'),
                ], $permissions);
                break;


            case 'categories':
                $categories = $this->em->getRepository(Category::class)->findAll();
                $headers = ['ID', 'Name', 'Created At', 'Updated At'];
                $data = array_map(fn($c) => [
                    $c->getId(),
                    $c->getName(),
                    $c->getCreatedAt()->format('Y-m-d H:i:s'),
                    $c->getUpdatedAt()->format('Y-m-d H:i:s'),
                ], $categories);
                break;


            case 'products':
                $products = $this->em->getRepository(Category::class)->findAll();
                $headers = ['ID', 'Name', 'Created At', 'Updated At'];
                $data = array_map(fn($p) => [
                    $p->getId(),
                    $p->getName(),
                    $p->getCreatedAt()->format('Y-m-d H:i:s'),
                    $p->getUpdatedAt()->format('Y-m-d H:i:s'),
                ], $products);
                break;

            default:
                throw new NotFoundHttpException("Export for entity '{$entity}' is not supported.");
        }

        return $this->csvExport($data, $headers, "{$entity}.csv");
    }
}
