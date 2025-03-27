<?php

namespace twa\cmsv2\Http\Controllers;

use Illuminate\Http\Request;

class RolePermissionsController extends Controller
{
    public function render(){
     

        return view("CMSView::pages.role-permissions");
    }

 
}
