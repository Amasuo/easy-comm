<?php

namespace App\Helpers;

use App\Models\Store;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionHelper
{
    public static function createStoreRolesAndPermissions(Store $store)
    {
        $storeRoles = config('permission.roles.store');
        foreach ($storeRoles as $roleIndex => $storeRole) {
            $storeRoleName = $storeRole['name'];
            $storeRoleName = str_replace('[id]', $store->id, $storeRoleName);
            $role = Role::create([
                'name' => $storeRoleName
            ]);
    
            $storePermissions = $storeRole['permissions'];
            foreach ($storePermissions as $permissionIndex => $storePermission) {
                $storePermissionName = $storePermission['name'];
                $storePermissionName = str_replace('[id]', $store->id, $storePermissionName);
                $permissionActions = $storePermission['actions'];
                foreach ($permissionActions as $actionIndex => $permissionAction) {
                    $permissionName = str_replace('[action]', $permissionAction, $storePermissionName);
                    $permission = Permission::create([
                        'name' => $permissionName
                    ]);
                    $permission->assignRole($role);
                }
            }
        }
    }

    public static function assignUserAsAdminForStore(User $user, Store $store)
    {

    }
}
