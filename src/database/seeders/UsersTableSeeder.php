<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 管理者ユーザーの作成
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // 店舗管理者ユーザーの作成
        $shopManager = User::create([
            'name' => 'Shop Manager User',
            'email' => 'shopmanager@example.com',
            'password' => bcrypt('password'),
        ]);
        $shopManager->assignRole('shop_manager');
    }
}
