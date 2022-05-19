<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = new Role();
        $role_user->name = 'admin';
        $role_user->description = 'Admin/Superadmin User';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'user';
        $role_user->description = 'A web User';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'coach';
        $role_user->description = 'A coach';
        $role_user->save();

        $role_user = new Role();
        $role_user->name = 'guest';
        $role_user->description = 'A guest User';
        $role_user->save();
    }

}
