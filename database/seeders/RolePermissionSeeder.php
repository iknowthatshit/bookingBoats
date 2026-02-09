<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаём разрешения
        Permission::create(['name' => 'view boats']);
        Permission::create(['name' => 'create boats']);
        Permission::create(['name' => 'edit boats']);
        Permission::create(['name' => 'delete boats']);

        // Роль админа — все права
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Роль пользователя — только просмотр
        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo('view boats');

        // Назначаем роль первому пользователю (или создаём админа)
        $adminUser = \App\Models\User::first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }
    }
}
