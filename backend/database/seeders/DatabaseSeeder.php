<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Full system access',
                'is_default' => true,
                'permissions' => [
                    '*',
                    'reviews.view_all',
                    'reviews.view_department',
                    'reviews.reply',
                    'reviews.moderate',
                    'reviews.view_employee_rating',
                    'reviews.view_service_rating',
                ],
            ],
            [
                'name' => 'Staff',
                'slug' => 'staff',
                'description' => 'Handles guest requests',
                'is_default' => false,
                'permissions' => ['orders.handle', 'guests.support', 'reviews.self'],
            ],
            [
                'name' => 'Network Admin',
                'slug' => 'network-admin',
                'description' => 'Manages hotel network resources',
                'is_default' => false,
                'permissions' => ['network.manage', 'modules.issue'],
            ],
            [
                'name' => 'Developer',
                'slug' => 'developer',
                'description' => 'Publishes and maintains modules',
                'is_default' => false,
                'permissions' => ['modules.publish'],
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::query()->updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );

            foreach ($permissions as $permission) {
                RolePermission::query()->updateOrCreate(
                    [
                        'role_id' => $role->id,
                        'permission' => $permission,
                    ],
                    ['is_allowed' => true]
                );
            }
        }
    }
}
