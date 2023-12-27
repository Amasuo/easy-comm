<?php

namespace App\Helpers;

use App\Models\Store;
use Spatie\Permission\Models\Role;

class PermissionHelper
{
    public static function createStoreRoles(Store $store)
    {
        $storeRoles = config('permission.roles.store');
        foreach ($storeRoles as $roleIndex => $storeRole) {
            $storeRole = str_replace('[id]', $store->id, $storeRole);
            Role::create([
                'name' => $storeRole
            ]);
        }
    }

    public static function getAdminRoleForStore(Store $store, bool $returnModel = false)
    {
        $role = config('permission.roles.store.admin');
        $role = str_replace('[id]', $store->id, $role);
        if ($returnModel) {
            $role = Role::where('name', $role)->first();
        }
        return $role;
    }

    public static function getSimpleRoleForStore(Store $store, bool $returnModel = false)
    {
        $role = config('permission.roles.store.simple');
        $role = str_replace('[id]', $store->id, $role);
        if ($returnModel) {
            $role = Role::where('name', $role)->first();
        }
        return $role;
    }

    public static function assignUserRole($user, $roleName) {
        $user->syncRoles([$roleName]);
    }
}
