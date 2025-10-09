<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Permission;

class PermissionFixtures extends Fixture
{
    public const CREATE_USER = 'create_user';
    public const EDIT_USER = 'edit_user';
    public const READ_USER = 'read_user';
    public const DELETE_USER = 'delete_user';
    public const CREATE_ADMIN_USER = 'create_admin_user';
    public const DELETE_ADMIN_USER = 'delete_admin_user';

    public const CREATE_CATEGORY = 'create_category';
    public const READ_CATEGORY = 'read_category';
    public const DELETE_CATEGORY = 'delete_category';
    public const UPDATE_CATEGORY = 'update_category';

    public const CREATE_PRODUCT = 'create_product';
    public const READ_PRODUCT = 'read_product';
    public const DELETE_PRODUCT = 'delete_product';
    public const UPDATE_PRODUCT = 'update_product';

    public function load(ObjectManager $manager): void
    {
        $permissions = [
            self::CREATE_USER,
            self::EDIT_USER,
            self::READ_USER,
            self::DELETE_USER,

            self::CREATE_ADMIN_USER,
            self::DELETE_ADMIN_USER,

            self::CREATE_CATEGORY,
            self::READ_CATEGORY,
            self::DELETE_CATEGORY,
            self::UPDATE_CATEGORY,

            self::CREATE_PRODUCT,
            self::READ_PRODUCT,
            self::DELETE_PRODUCT,
            self::UPDATE_PRODUCT,
        ];

        foreach ($permissions as $permissionName) {
            $permission = new Permission();
            $permission->setName($permissionName);
            $manager->persist($permission);

            // Save reference to use in other fixtures (e.g. assign to roles)
            $this->addReference($permissionName, $permission);
        }

        $manager->flush();
    }
}
