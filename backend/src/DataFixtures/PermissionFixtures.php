<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Permission;

class PermissionFixtures extends Fixture
{
    public const CREATE_USER = 'create_user';
    public const UPDATE_USER = 'UPDATE_USER';
    public const READ_USER = 'read_user';
    public const DELETE_USER = 'delete_user';
    //permissions only for super admin
    public const CREATE_ADMIN_USER = 'create_admin_user';
    public const EDIT_ADMIN_USER = 'edit_admin_user';
    public const DELETE_ADMIN_USER = 'delete_admin_user';

    // admin can only edit himself

    public const SHOW_SUPR_ADMIN_USER = 'show_super_admin_user';

    // /both admin and super admin
    public const CREATE_ROLE = 'create_role';
    public const READ_ROLE = 'read_role';
    public const UPDATE_ROLE = 'update_role';
    public const DELETE_ROLE = 'delete_role';

    public const CREATE_PERMISSION = 'create_permission';
    public const READ_PERMISSION = 'read_permission';
    public const UPDATE_PERMISSION = 'update_permission';
    public const DELETE_PERMISSION = 'delete_permission';

    // admin can assign only non admin permissions to regular user role
    public const ASSIGN_PERMISSION_TO_REGULAR_USER = 'assign_permission_to_regular_user';
    //super admin can assign any permission to any role
    public const ASSIGN_PERMISSION_TO_ADMIN_USER = 'assign_permission_to_admin_user';


    //permissios everybody can use
    public const CREATE_CATEGORY = 'create_category';
    public const READ_CATEGORY = 'read_category';
    public const UPDATE_CATEGORY = 'update_category';
    public const DELETE_CATEGORY = 'delete_category';

    public const CREATE_PRODUCT = 'create_product';
    public const READ_PRODUCT = 'read_product';
    public const UPDATE_PRODUCT = 'update_product';
    public const DELETE_PRODUCT = 'delete_product';

    public function load(ObjectManager $manager): void
    {
        $permissions = [
            self::CREATE_USER,
            self::READ_USER,
            self::UPDATE_USER,
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
