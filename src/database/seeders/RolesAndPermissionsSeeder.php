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
        Permission::create(['name' => 'manage shops']);
        Permission::create(['name' => 'view reservations']);
        Permission::create(['name' => 'create shop managers']);

        // ロールの作成
        $adminRole = Role::create(['name' => 'admin']);
        $shopManagerRole = Role::create(['name' => 'shop_manager']);

        // ロールに権限を割り当て
        $adminRole->givePermissionTo('create shop managers');
        $shopManagerRole->givePermissionTo(['manage shops', 'view reservations']);
    }

}

