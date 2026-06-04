<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersSeeder extends Seeder
{
    public function run(): void
    {

        // ── Create Roles ──────────────────────────────────────────────
        $adminRole   = Role::find(1);
        $editorRole = Role::find(2);
        $userRole = Role::find(3);

        // ── Create Admin ─────────────────────────────────────────
        $admin = User::create(
            [
                'name'              => 'Barat Ahmed',
                'username'          => 'admin123',
                'email'             => 'admin@gmail.com',
                'phone'             => '0123456789',
                'password'          => 'password',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole($adminRole);


        // ── Create Editor 1 ───────────────────────────────────────
        $editor = User::create(
            [
                'name'              => 'Shams Tabrizi',
                'username'          => 'editor123',
                'email'             => 'editor@gmail.com',
                'phone'             => '0123456788',
                'password'          => 'password',
                'email_verified_at' => now(),
            ]
        );
        $editor->assignRole($editorRole);


         // ── Create Editor 2 ───────────────────────────────────────
        $editor = User::create(
            [
                'name'              => 'Shams Tabrizi 2',
                'username'          => 'editor1234',
                'email'             => 'editor2@gmail.com',
                'phone'             => '0123456787',
                'password'          => 'password',
                'email_verified_at' => now(),
            ]
        );
        $editor->assignRole($editorRole);

    

        // ── Create User 1 ───────────────────────────────────────
        $user = User::create(
            [
                'name'              => 'User 01',
                'username'          => 'user001',
                'email'             => 'user001@gmail.com',
                'phone'             => '0123456786',
                'password'          => 'password',
                'email_verified_at' => now(),
            ]
        );
        $user->profile([
            'board_id' => 2,
            'klass_id' => 1,
            'institution_id' => 10,
            'group' => 'SCIENCE',
        ]);

        $user->assignRole($userRole);


         // ── Create User 2 ───────────────────────────────────────
        $user = User::create(
            [
                'name'              => 'User 02',
                'username'          => 'user002',
                'email'             => 'user002@gmail.com',
                'phone'             => '0123456785',
                'password'          => 'password',
                'email_verified_at' => now(),
            ]
        );
        $user->profile([
            'board_id' => 2,
            'klass_id' => 1,
            'institution_id' => 10,
            'group' => 'HUMANITIES',
        ]);

        $user->assignRole($userRole);


         // ── Create User 3 ───────────────────────────────────────
        $user = User::create(
            [
                'name'              => 'User 03',
                'username'          => 'user003',
                'email'             => 'user003@gmail.com',
                'phone'             => '0123456784',
                'password'          => 'password',
                'email_verified_at' => now(),
            ]
        );
        $user->profile([
            'board_id' => 2,
            'klass_id' => 1,
            'institution_id' => 10,
            'group' => 'BUSINESS STUDIES',
        ]);

        $user->assignRole($userRole);




        // ── Create User 4 ───────────────────────────────────────
        $user = User::create(
            [
                'name'              => 'User 04',
                'username'          => 'user004',
                'email'             => 'user004@gmail.com',
                'phone'             => '0123456783',
                'password'          => 'password',
                'email_verified_at' => now(),
            ]
        );
        $user->profile([
            'board_id' => 2,
            'klass_id' => 2,
            'institution_id' => 10,
            'group' => 'SCIENCE',
        ]);

        $user->assignRole($userRole);


         // ── Create User 5 ───────────────────────────────────────
        $user = User::create(
            [
                'name'              => 'User 05',
                'username'          => 'user005',
                'email'             => 'user005@gmail.com',
                'phone'             => '0123456782',
                'password'          => 'password',
                'email_verified_at' => now(),
            ]
        );
        $user->profile([
            'board_id' => 2,
            'klass_id' => 2,
            'institution_id' => 10,
            'group' => 'HUMANITIES',
        ]);

        $user->assignRole($userRole);


         // ── Create User 6 ───────────────────────────────────────
        $user = User::create(
            [
                'name'              => 'User 06',
                'username'          => 'user006',
                'email'             => 'user006@gmail.com',
                'phone'             => '0123456781',
                'password'          => 'password',
                'email_verified_at' => now(),
            ]
        );
        $user->profile([
            'board_id' => 2,
            'klass_id' => 2,
            'institution_id' => 10,
            'group' => 'BUSINESS STUDIES',
        ]);

        $user->assignRole($userRole);

    }
}