<?php

namespace twa\cmsv2\Database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CMSUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => "Hovig Senekjian",
                'email' => 'hovig@thewebaddicts.com',
                'password' => md5('changeme'),
                'super_admin' => 1
            ],
            [
                'name' => "Nourhane Sarieddine",
                'email' => 'nourhane.sarieddine@thewebaddicts.com',
                'password' => md5('changeme'),
                'super_admin' => 1
            ]
        ];
        foreach($users as $user){
            $existing_user = DB::table('cms_users')->where("email" , $user['email'])->first();
            if($existing_user){ continue; }
            DB::table('cms_users')->insert($user);
        }   
    }
}
