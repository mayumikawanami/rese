<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 権限の定義
        $permissions = [
            'manage shops',
            'view reservations',
            'create shop managers',
            'create reviews',
            'edit reviews',
            'delete reviews' // 新しい権限
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }

        // ロールの作成
        $roles = [
            'admin',
            'shop_manager',
            'user'
        ];

        foreach ($roles as $role) {
            if (!Role::where('name', $role)->exists()) {
                Role::create(['name' => $role]);
            }
        }

        // ロールに権限を割り当て
        $adminRole = Role::findByName('admin');
        $shopManagerRole = Role::findByName('shop_manager');
        $userRole = Role::findByName('user');

        // 既存の権限の割り当てを確認し、存在しない場合にのみ割り当てる
        $adminRole->givePermissionTo(['create shop managers', 'delete reviews']);
        $shopManagerRole->givePermissionTo(['manage shops', 'view reservations']);
        $userRole->givePermissionTo(['create reviews', 'edit reviews', 'delete reviews']);
    }

}

