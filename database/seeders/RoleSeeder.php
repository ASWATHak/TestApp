<?php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['Admin', 'Manager', 'Developer', 'Tester'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}