<?php

namespace twa\cmsv2\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsUserRolePermission extends Model
{
    use HasFactory;
    protected $table = 'cms_user_role_permission'; 
   
    protected $fillable = [
        'cms_user_role_id',    
        'cms_permission_id',
        'menu_key',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
