<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);

        if (!User::where('email', 'admin@example.com')->exists()) {
            $admin = User::create([
                'name'              => 'Администратор',
                'email'             => 'admin@example.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('admin123'),
                'remember_token'    => \Illuminate\Support\Str::random(10),
            ]);

            $admin->assignRole('admin');

            $this->command->info('Администратор создан: admin@example.com / admin123');
        }
    }
}