<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\RoleModel;
use App\Models\RoleUserModel;
use App\Models\User;
use Couchbase\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'admin',
            'phone' => '+992000207747'
        ]);

        $role = RoleModel::create(['name' => 'super-admin']);
        $role2 = RoleModel::create(['name' => 'user']);
        RoleUserModel::create([
            'user_id' => $user->id,
            'role_id' => $role->id
        ]);
    }
}
