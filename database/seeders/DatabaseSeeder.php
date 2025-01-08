<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\RoleModel;
use App\Models\RoleUserModel;
use App\Models\User;
use Couchbase\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'admin',
            'phone' => '+992000207747',
            'password' => Hash::make('1'),
        ]);
        $user2 = User::create([
            'name' => 'User',
            'phone' => '+992987671091',
            'password' => Hash::make('1'),
        ]);

        $role = RoleModel::create(['name' => 'admin']);
        $role2 = RoleModel::create(['name' => 'user']);

        RoleUserModel::create([
            'user_id' => $user->id,
            'role_id' => $role->id
        ]);
        RoleUserModel::create([
            'user_id' => $user2->id,
            'role_id' => $role2->id
        ]);
    }
}
