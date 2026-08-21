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
            ['name' => 'roles.view', 'group' => 'roles', 'description' => 'View role matrix and permissions'],
            ['name' => 'roles.create', 'group' => 'roles', 'description' => 'Create new roles'],
            ['name' => 'roles.edit', 'group' => 'roles', 'description' => 'Modify role permissions'],
            ['name' => 'roles.delete', 'group' => 'roles', 'description' => 'Delete custom roles'],
            ['name' => 'courses.create', 'group' => 'courses', 'description' => 'Create new online courses'],
            ['name' => 'courses.view', 'group' => 'courses', 'description' => 'Access and view courses catalogue'],
            ['name' => 'courses.edit', 'group' => 'courses', 'description' => 'Modify syllabus or metadata of courses'],
            ['name' => 'courses.delete', 'group' => 'courses', 'description' => 'Remove courses from system'],
            ['name' => 'settings.view', 'group' => 'settings', 'description' => 'Read system configurations'],
            ['name' => 'settings.edit', 'group' => 'settings', 'description' => 'Update system settings'],
            ['name' => 'media.upload', 'group' => 'media', 'description' => 'Upload files to media manager'],
            ['name' => 'media.view', 'group' => 'media', 'description' => 'Browse media assets'],
            // Developer Permissions
            ['name' => 'crud_generator.view', 'group' => 'developer', 'description' => 'Access Visual CRUD Generator'],
            ['name' => 'database_manager.view', 'group' => 'developer', 'description' => 'Access Database Studio & Schema Manager'],
            ['name' => 'webhooks.view', 'group' => 'developer', 'description' => 'Access Outgoing Webhooks Engine'],
            ['name' => 'docs.view', 'group' => 'developer', 'description' => 'Access API Documentation'],
            ['name' => 'backups.view', 'group' => 'system', 'description' => 'Manage Database Backups'],
            ['name' => 'audit_logs.view', 'group' => 'system', 'description' => 'View System Audit Logs'],
            ['name' => 'system_health.view', 'group' => 'system', 'description' => 'View System Health Monitor'],
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

        $developerRole = Role::updateOrCreate(
            ['name' => 'developer', 'guard_name' => 'web'],
            [
                'description' => 'Software Developer with full access to developer tools, database, webhooks, and CRUD generator',
                'parent_id' => $adminRole->id
            ]
        );

        $teacherRole = Role::updateOrCreate(
            ['name' => 'educator', 'guard_name' => 'web'],
            [
                'description' => 'Instructors who manage courses and student registrations',
                'parent_id' => $adminRole->id
            ]
        );

        $studentRole = Role::updateOrCreate(
            ['name' => 'student', 'guard_name' => 'web'],
            [
                'description' => 'Registered students who consume content and view catalogs',
                'parent_id' => $teacherRole->id
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

        $allPermissionNames = Permission::pluck('name')->toArray();

        // Administrator and Developer get ALL permissions
        $adminRole->syncPermissions($allPermissionNames);
        $developerRole->syncPermissions($allPermissionNames);

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

        $developerUser = User::updateOrCreate(
            ['email' => 'developer@laraforgex.com'],
            [
                'id' => '3c8fd9a7-e1ba-4f2e-89a1-cb9e8c4e47ac',
                'name' => 'Developer',
                'password' => Hash::make('SecurePassword123!'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $developerUser->assignRole($developerRole);

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
            ['key' => 'app_logo', 'value' => '', 'group' => 'general', 'is_encrypted' => false],
            ['key' => 'sidebar_theme', 'value' => 'clean_light', 'group' => 'appearance', 'is_encrypted' => false],
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
