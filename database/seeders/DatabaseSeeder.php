<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\RoleModel;
use App\Models\RoleUserModel;
use App\Models\StoreModel;
use App\Models\SubscribePlansModel;
use App\Models\User;
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
        StoreModel::create(['name' => 'Ozon']);
        StoreModel::create(['name' => 'Wildberries']);
        StoreModel::create(['name' => 'Yandesx Market']);
        SubscribePlansModel::create([
            'title' => '1 месяц',
            'description' => '1 месяц 1 месяц 1 месяц 1 месяц',
            'price' => 10,
            'month' => 1,
        ]);
        SubscribePlansModel::create([
            'title' => '4 месяц',
            'description' => '4 месяц 4 месяц 4 месяц 4 месяц',
            'price' => 30,
            'month' => 4,
        ]);
        SubscribePlansModel::create([
            'title' => '12 месяц',
            'description' => '12 месяц 12 месяц 12 месяц 12 месяц',
            'price' => 100,
            'month' => 12,
        ]);
    }
}
