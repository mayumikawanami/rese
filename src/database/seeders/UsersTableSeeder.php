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

        // 一般ユーザーの作成
        $user = User::create([
            'name' => 'User1',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');

        // 一般ユーザーの作成
        $user = User::create([
            'name' => 'User2',
            'email' => 'user2@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');

        // 一般ユーザーの作成
        $user = User::create([
            'name' => 'User3',
            'email' => 'user3@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');

        // 一般ユーザーの作成
        $user = User::create([
            'name' => 'User4',
            'email' => 'user4@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');

        // 一般ユーザーの作成
        $user = User::create([
            'name' => 'User5',
            'email' => 'user5@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');

        // 一般ユーザーの作成
        $user = User::create([
            'name' => 'User6',
            'email' => 'user6@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');

        // 一般ユーザーの作成
        $user = User::create([
            'name' => 'User7',
            'email' => 'user7@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');

        // 一般ユーザーの作成
        $user = User::create([
            'name' => 'User8',
            'email' => 'user8@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');

        // 一般ユーザーの作成
        $user = User::create([
            'name' => 'User9',
            'email' => 'user9@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');

        // 一般ユーザーの作成
        $user = User::create([
            'name' => 'User10',
            'email' => 'user10@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');
    }
}
