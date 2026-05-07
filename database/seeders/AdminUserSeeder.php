<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('admin.default_user.email');
        $password = config('admin.default_user.password');

        User::firstOrCreate(
            ['email' => $email],
            [
                'name'     => 'Administrator',
                'password'    => Hash::make($password),
                'permissions' => [
                    'platform.index' => true,
                    'platform.systems.roles' => true,
                    'platform.systems.users' => true,
                    'platform.systems.attachment' => true,
                    'platform.systems.settings' => true,
                    'platform.posts' => true,
                ],
            ]
        );
    }
}
