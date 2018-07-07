<?php

use Illuminate\Database\Seeder;

class UserRoleseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newUserRole1 = new \App\UserRole();
        $newUserRole1->role_name = 'Admin';
        $newUserRole1->save();

        $newUserRole2 = new \App\UserRole();
        $newUserRole2->role_name = 'Editor';
        $newUserRole2->save();

        $newUserRole3 = new \App\UserRole();
        $newUserRole3->role_name = 'Viewer';
        $newUserRole3->save();
    }
}
