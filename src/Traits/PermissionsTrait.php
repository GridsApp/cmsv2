<?php

namespace twa\cmsv2\Traits;

use twa\cmsv2\Models\CmsUserRolePermission;
use twa\cmsv2\Models\CmsPermissions;

trait PermissionsTrait
{
    public function getPermissions($cms_user)
    {
        if (empty($cms_user['roles'])) {
            return [];
        }

        $role_ids = collect($cms_user['roles'])->flatten()->toArray();

        if (empty($role_ids)) {
            return [];
        }

        $role_permissions = CmsUserRolePermission::whereNull('cms_user_role_permission.deleted_at')
            ->whereIn('cms_user_role_id', $role_ids)
            ->join('cms_permissions', 'cms_permissions.id', '=', 'cms_user_role_permission.cms_permission_id')
            ->select('cms_permissions.key as permission_key')
            ->get();

        if ($role_permissions->isEmpty()) {
            return [];
        }

        return $role_permissions->pluck('permission_key')->toArray();
    }
}
