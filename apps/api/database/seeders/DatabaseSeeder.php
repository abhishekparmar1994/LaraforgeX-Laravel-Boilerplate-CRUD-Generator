<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\User\Models\User;
use App\Domains\User\Models\Role;
use App\Domains\User\Models\Permission;
use App\Domains\Settings\Models\Settings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Permissions
        $permissions = [
            ['name' => 'users.create', 'group' => 'users', 'description' => 'Create new user accounts'],
            ['name' => 'users.view', 'group' => 'users', 'description' => 'View user lists and profiles'],
            ['name' => 'users.edit', 'group' => 'users', 'description' => 'Modify existing user details'],
            ['name' => 'users.delete', 'group' => 'users', 'description' => 'Delete user profiles'],
            ['name' => 'users.suspend', 'group' => 'users', 'description' => 'Suspend user access permissions'],
            ['name' => 'courses.create', 'group' => 'courses', 'description' => 'Create new online courses'],
            ['name' => 'courses.view', 'group' => 'courses', 'description' => 'Access and view courses catalogue'],
            ['name' => 'courses.edit', 'group' => 'courses', 'description' => 'Modify syllabus or metadata of courses'],
            ['name' => 'courses.delete', 'group' => 'courses', 'description' => 'Remove courses from system'],
            ['name' => 'settings.view', 'group' => 'settings', 'description' => 'Read system configurations'],
            ['name' => 'settings.edit', 'group' => 'settings', 'description' => 'Update system settings'],
            ['name' => 'media.upload', 'group' => 'media', 'description' => 'Upload files to media manager'],
            ['name' => 'media.view', 'group' => 'media', 'description' => 'Browse media assets'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::updateOrCreate(
                ['name' => $permissionData['name'], 'guard_name' => 'web'],
                $permissionData
            );
        }

        // 2. Seed Roles with hierarchy
        $adminRole = Role::updateOrCreate(
            ['name' => 'administrator', 'guard_name' => 'web'],
            ['description' => 'System administrator with full system capabilities']
        );

        $teacherRole = Role::updateOrCreate(
            ['name' => 'educator', 'guard_name' => 'web'],
            [
                'description' => 'Instructors who manage courses and student registrations',
                'parent_id' => $adminRole->id // educator is a child role of administrator
            ]
        );

        $studentRole = Role::updateOrCreate(
            ['name' => 'student', 'guard_name' => 'web'],
            [
                'description' => 'Registered students who consume content and view catalogs',
                'parent_id' => $teacherRole->id // student is a child role of educator
            ]
        );

        // 3. Assign direct permissions to roles
        $studentRole->syncPermissions([
            'courses.view',
            'media.view'
        ]);

        $teacherRole->syncPermissions([
            'courses.create',
            'courses.edit',
            'courses.delete',
            'media.upload',
            'users.view'
        ]);

        $adminRole->syncPermissions([
            'users.create',
            'users.edit',
            'users.delete',
            'users.suspend',
            'settings.view',
            'settings.edit'
        ]);

        // 4. Seed Users matching Postman sample IDs
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@laraforgex.com'],
            [
                'id' => '98fd9a76-e1ba-4f2e-89a1-cb9e8c4e47a9',
                'name' => 'Administrator',
                'password' => Hash::make('SecurePassword123!'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole($adminRole);

        $teacherUser = User::updateOrCreate(
            ['email' => 'teacher@laraforgex.com'],
            [
                'id' => '1a8fd9a7-e1ba-4f2e-89a1-cb9e8c4e47aa',
                'name' => 'Jane Teacher',
                'password' => Hash::make('SecurePassword123!'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $teacherUser->assignRole($teacherRole);

        $studentUser = User::updateOrCreate(
            ['email' => 'student@laraforgex.com'],
            [
                'id' => '2b8fd9a7-e1ba-4f2e-89a1-cb9e8c4e47ab',
                'name' => 'Bob Student',
                'password' => Hash::make('SecurePassword123!'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $studentUser->assignRole($studentRole);

        // 5. Seed Platform Settings
        $settings = [
            ['key' => 'app_name', 'value' => 'LaraforgeX', 'group' => 'general', 'is_encrypted' => false],
            ['key' => 'theme', 'value' => 'dark', 'group' => 'general', 'is_encrypted' => false],
            ['key' => 'mail_host', 'value' => 'smtp.mailtrap.io', 'group' => 'smtp', 'is_encrypted' => false],
            ['key' => 'mail_port', 'value' => '2525', 'group' => 'smtp', 'is_encrypted' => false],
            ['key' => 'mail_username', 'value' => 'dev_user', 'group' => 'smtp', 'is_encrypted' => false],
            ['key' => 'mail_password', 'value' => 'VerySecretSmtpPassword123!', 'group' => 'smtp', 'is_encrypted' => true],
        ];

        foreach ($settings as $settingData) {
            Settings::updateOrCreate(
                ['key' => $settingData['key']],
                $settingData
            );
        }

        // Mark installation complete
        file_put_contents(storage_path('installed'), date('Y-m-d H:i:s'));
    }
}
