<?php

namespace App\DataFixtures;

use App\Entity\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Role;

class RoleFixtures extends Fixture
{
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_REGULAR_USER = 'ROLE_REGULAR_USER';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $roles = [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN,
            self::ROLE_REGULAR_USER,
        ];


        $rolePermissionsMap = [
            'ROLE_SUPER_ADMIN' => [
                PermissionFixtures::CREATE_USER,
                PermissionFixtures::READ_USER,
                PermissionFixtures::UPDATE_USER,
                PermissionFixtures::DELETE_USER,

                PermissionFixtures::ASSIGN_PERMISSION_TO_ADMIN_USER,
                PermissionFixtures::ASSIGN_PERMISSION_TO_REGULAR_USER,

                PermissionFixtures::CREATE_ADMIN_USER,
                PermissionFixtures::UPDATE_ADMIN_USER,
                PermissionFixtures::DELETE_ADMIN_USER,

                PermissionFixtures::CREATE_ROLE,
                PermissionFixtures::READ_ROLE,
                PermissionFixtures::UPDATE_ROLE,
                PermissionFixtures::DELETE_ROLE,

                PermissionFixtures::CREATE_PERMISSION,
                PermissionFixtures::READ_PERMISSION,
                PermissionFixtures::UPDATE_PERMISSION,
                PermissionFixtures::DELETE_PERMISSION,

                PermissionFixtures::CREATE_CATEGORY,
                PermissionFixtures::READ_CATEGORY,
                PermissionFixtures::UPDATE_CATEGORY,
                PermissionFixtures::DELETE_CATEGORY,

                PermissionFixtures::CREATE_PRODUCT,
                PermissionFixtures::READ_PRODUCT,
                PermissionFixtures::UPDATE_PRODUCT,
                PermissionFixtures::DELETE_PRODUCT,

            ],
            'ROLE_ADMIN' => [
                PermissionFixtures::ASSIGN_PERMISSION_TO_REGULAR_USER,

                PermissionFixtures::CREATE_USER,
                PermissionFixtures::READ_USER,
                PermissionFixtures::UPDATE_USER,
                PermissionFixtures::DELETE_USER,

                PermissionFixtures::CREATE_CATEGORY,
                PermissionFixtures::READ_CATEGORY,
                PermissionFixtures::UPDATE_CATEGORY,
                PermissionFixtures::DELETE_CATEGORY,

                PermissionFixtures::CREATE_PRODUCT,
                PermissionFixtures::READ_PRODUCT,
                PermissionFixtures::UPDATE_PRODUCT,
                PermissionFixtures::DELETE_PRODUCT,
            ],
            'ROLE_USER' => [
                PermissionFixtures::CREATE_CATEGORY,
                PermissionFixtures::READ_CATEGORY,
                PermissionFixtures::UPDATE_CATEGORY,
                PermissionFixtures::DELETE_CATEGORY,

                PermissionFixtures::CREATE_PRODUCT,
                PermissionFixtures::READ_PRODUCT,
                PermissionFixtures::UPDATE_PRODUCT,
                PermissionFixtures::DELETE_PRODUCT,
            ],
        ];

        foreach ($roles as $roleName) {
            $role = new Role();
            $role->setName($roleName);
            $permissionsOfRole = $rolePermissionsMap[$roleName] ?? [];


            for ($i = 0; $i < count($permissionsOfRole); $i++) {
                $role->addPermission($this->getReference($permissionsOfRole[$i], Permission::class));
            }

            $manager->persist($role);

            // Add references if you want to use these roles in other fixtures
            $this->addReference($roleName, $role);
        }

        $manager->flush();
    }
}
