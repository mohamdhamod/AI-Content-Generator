<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            ["name" => RoleEnum::ADMIN, 'guard_name' => "web"],
            ["name" => RoleEnum::MANAGER, 'guard_name' => "web"],
            ["name" => RoleEnum::SUBSCRIBER, 'guard_name' => "web"],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }

        $permissions = [
            // User Management
            ["name" => PermissionEnum::USERS_ADD, 'guard_name' => "web", 'page' => PermissionEnum::USERS],
            ["name" => PermissionEnum::USERS_VIEW, 'guard_name' => "web", 'page' => PermissionEnum::USERS],
            ["name" => PermissionEnum::USERS_UPDATE, 'guard_name' => "web", 'page' => PermissionEnum::USERS],
            ["name" => PermissionEnum::USERS_DELETE, 'guard_name' => "web", 'page' => PermissionEnum::USERS],

            // Settings
            ["name" => PermissionEnum::SETTING_ADD, 'guard_name' => "web", 'page' => PermissionEnum::SETTING],
            ["name" => PermissionEnum::SETTING_VIEW, 'guard_name' => "web", 'page' => PermissionEnum::SETTING],
            ["name" => PermissionEnum::SETTING_UPDATE, 'guard_name' => "web", 'page' => PermissionEnum::SETTING],
            ["name" => PermissionEnum::SETTING_DELETE, 'guard_name' => "web", 'page' => PermissionEnum::SETTING],

            // Role Management
            ["name" => PermissionEnum::MANAGE_ROLES, 'guard_name' => "web", 'page' => ''],

            // Subscription Management
            ["name" => PermissionEnum::MANAGE_SUBSCRIPTIONS, 'guard_name' => "web", 'page' => ''],

            // Content Generation
            ["name" => PermissionEnum::CONTENT_GENERATION_CREATE, 'guard_name' => "web", 'page' => PermissionEnum::CONTENT_GENERATION],
            ["name" => PermissionEnum::CONTENT_GENERATION_VIEW, 'guard_name' => "web", 'page' => PermissionEnum::CONTENT_GENERATION],
            ["name" => PermissionEnum::CONTENT_GENERATION_EXPORT, 'guard_name' => "web", 'page' => PermissionEnum::CONTENT_GENERATION],

            // Medical Specialties Management
            ["name" => PermissionEnum::MANAGE_SPECIALTIES_ADD, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_SPECIALTIES],
            ["name" => PermissionEnum::MANAGE_SPECIALTIES_VIEW, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_SPECIALTIES],
            ["name" => PermissionEnum::MANAGE_SPECIALTIES_UPDATE, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_SPECIALTIES],
            ["name" => PermissionEnum::MANAGE_SPECIALTIES_DELETE, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_SPECIALTIES],

            // Prompts Management
            ["name" => PermissionEnum::MANAGE_PROMPTS_ADD, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_PROMPTS],
            ["name" => PermissionEnum::MANAGE_PROMPTS_VIEW, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_PROMPTS],
            ["name" => PermissionEnum::MANAGE_PROMPTS_UPDATE, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_PROMPTS],
            ["name" => PermissionEnum::MANAGE_PROMPTS_DELETE, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_PROMPTS],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }

        // Admin gets all permissions
        $allPermissionIds = Permission::all()->pluck('id')->toArray();
        $adminRole = Role::whereName(RoleEnum::ADMIN)->first();
        $adminRole->permissions()->detach();
        $adminRole->permissions()->attach($allPermissionIds);

        // Manager gets limited permissions
        $managerPermissions = [
            PermissionEnum::USERS_VIEW,
            PermissionEnum::SETTING_VIEW,
            PermissionEnum::MANAGE_SUBSCRIPTIONS,
            PermissionEnum::CONTENT_GENERATION_CREATE,
            PermissionEnum::CONTENT_GENERATION_VIEW,
            PermissionEnum::CONTENT_GENERATION_EXPORT,
            PermissionEnum::MANAGE_SPECIALTIES_VIEW,
            PermissionEnum::MANAGE_PROMPTS_VIEW,
        ];
        $managerRole = Role::whereName(RoleEnum::MANAGER)->first();
        if ($managerRole) {
            $managerPermissionIds = Permission::whereIn('name', $managerPermissions)->pluck('id')->toArray();
            $managerRole->permissions()->sync($managerPermissionIds);
        }

        // Subscriber gets content generation permissions only
        $subscriberPermissions = [
            PermissionEnum::CONTENT_GENERATION_CREATE,
            PermissionEnum::CONTENT_GENERATION_VIEW,
            PermissionEnum::CONTENT_GENERATION_EXPORT,
        ];
        $subscriberRole = Role::whereName(RoleEnum::SUBSCRIBER)->first();
        if ($subscriberRole) {
            $subscriberPermissionIds = Permission::whereIn('name', $subscriberPermissions)->pluck('id')->toArray();
            $subscriberRole->permissions()->sync($subscriberPermissionIds);
        }
    }
}
