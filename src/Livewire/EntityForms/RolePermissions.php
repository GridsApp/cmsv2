<?php

namespace twa\cmsv2\Livewire\EntityForms;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use twa\cmsv2\Models\CmsPermissions;
use twa\cmsv2\Models\CmsUserRolePermission;
use twa\cmsv2\Traits\FormTrait;
use twa\cmsv2\Traits\PermissionsTrait;
use twa\uikit\Traits\ToastTrait;

class RolePermissions extends Component
{

    use ToastTrait , PermissionsTrait;

    public $filteredMenu = [];


    // public function mount()
    // {



    //     $role_id = request()->route('id');
    //     $menu = collect(config('menu'));

    //     $permissions = CmsPermissions::whereNull('deleted_at')->get()->groupby('menu_key');
    //     $exiting=CmsUserRolePermission::select(DB::raw("CONCAT(cms_user_role_id , '-' , cms_permission_id , '-' , menu_key) as identifier"))->whereNull('deleted_at')->pluck('identifier')->toArray();


    //     $this->filteredMenu = $this->flattenArray($menu)->map(function ($menuItem) use ($permissions,$role_id , $exiting) {

    //         if ($menuItem['permission_types'] ?? null) {
    //             $menuItem['permissions'] = $permissions->whereIn('type', $menuItem['permission_types'])->map(function ($per) use ($menuItem,$role_id , $exiting) {

    //                 $per['target'] = [
    //                     'role_id' => $role_id,
    //                     'permission_id' => $per->id,
    //                     'menu_key' => $menuItem['key']
    //                 ];

    //                 $identifier = $role_id."-".$per->id.'-'.$menuItem['key'];

    //                 $per['selected'] = in_array($identifier , $exiting);
    //                 return $per;
    //             })->toArray();
    //         } else {
    //             $menuItem['permissions'] = [];
    //         }


    //         if (count($menuItem['permissions']) == 0) {
    //             return null;
    //         }




    //         return $menuItem;
    //     })->filter()->values();
    // }

    public function mount()
    {
        $role_id = request()->route('id');
        $menu = collect(config('menu'));

        $permissions = CmsPermissions::whereNull('deleted_at')->get()->groupBy('menu_key');

        $existing = CmsUserRolePermission::select(
            DB::raw("CONCAT(cms_user_role_id, '-', cms_permission_id, '-', menu_key) as identifier")
        )
            ->whereNull('deleted_at')
            ->pluck('identifier')
            ->toArray();

        $this->filteredMenu = $this->flattenArray($menu)
            ->map(function ($menuItem) use ($permissions, $role_id, $existing, $menu) {
                $menuKey = $menuItem['key'] ?? null;
                if (!$menuKey) return null;


                $itemPermissions = $permissions->get($menuKey, collect());


                if ($itemPermissions->isEmpty() && isset($menuItem['link']['name']) && $menuItem['link']['name'] === 'entity') {
                    $slug = $menuItem['link']['params']['slug'] ?? $menuItem['link']['slug'] ?? null;
                    if ($slug) {
                        $entity = get_entity($slug);
                        if ($entity) {
                            $entityPermissions = $entity->getPermissions();
                            $itemPermissions = collect($entityPermissions)->map(function ($permission) use ($menuKey) {
                                return (object)[
                                    'key' => $permission,
                                    'type' => 'entity',
                                    'menu_key' => $menuKey
                                ];
                            });
                        }
                    }
                }


                $menuData = $menu->where('key', $menuKey)->first();


                $menuItem['permissions'] = $itemPermissions->map(function ($per) use ($role_id, $menuKey, $existing, $menuData) {
                    $identifier = $role_id . "-" . $per->id . '-' . $menuKey;

                    return [
                        'id' => $per->id ?? null,
                        'key' => $per->key ?? '',
                        'label' => $per->label ?? '',
                        'type' => $per->type ?? '',
                        'target' => [
                            'role_id' => $role_id,
                            'permission_id' => $per->id ?? null,
                            'menu_key' => $menuKey,
                            'label' => $menuData['label'] ?? null,
                        ],
                        'selected' => in_array($identifier, $existing)
                    ];
                })->toArray();

                return collect($menuItem['permissions'])->isNotEmpty() ? $menuItem : null;
            })
            ->filter()
            ->values();
    }


    public function flattenArray($array)
    {
        $result = [];

        foreach ($array as $item) {

            if (isset($item['children']) && is_array($item['children'])) {

                $children = $this->flattenArray($item['children']);


                $result = [...$result, ...$children];
            } else {

                $result[] = $item;
            }
        }


        return collect($result)->whereNotNull('key');
    }


    public function save()
    {
        $permissions = collect($this->filteredMenu)->pluck('permissions')->flatten(1);

        foreach ($permissions as $permission) {

            $arr = CmsUserRolePermission::firstOrNew([
                'cms_user_role_id' => $permission['target']['role_id'],
                'cms_permission_id' => $permission['target']['permission_id'],
                'menu_key' => $permission['target']['menu_key'],
            ]);

            $arr->updated_at = now();


            if ($arr->exists) {

                if ($permission['selected'] == true) {

                    $arr->deleted_at = null;
                    $arr->save();
                } else {

                    $arr->deleted_at = now();
                    $arr->save();
                }
            } else {

                if ($permission['selected'] == true) {

                    $arr->cms_user_role_id = $permission['target']['role_id'];
                    $arr->cms_permission_id = $permission['target']['permission_id'];
                    $arr->menu_key = $permission['target']['menu_key'];
                    $arr->created_at = now();
                    $arr->save();
                }

                // $arr->deleted_at = $permission['selected'] == true ? null : now();
            }
        }
        $cms_user = session('cms_user');  
        $permissions = $this->getPermissions($cms_user);  
    
     
        session([
            'cms_user_permissions' => $permissions,
        ]);
        
        $this->sendSuccess("Records Saved", "Records Saved");
    }


    public function render()
    {
        return view('CMSView::pages.form.components.role-permissions');
    }
}
