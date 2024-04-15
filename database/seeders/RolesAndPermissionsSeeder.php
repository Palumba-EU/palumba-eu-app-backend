<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    private array $permissions = [
        'countries',
        'local_parties',
        'parties',
        'policies',
        'sponsors',
        'statements',
        'topics',
        'users',
        'user_invitations',
        'responses',
    ];

    private array $extraPermissions = [
        'sync crowdin',
    ];

    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionsCount = Permission::count();

        // create permissions
        foreach ($this->permissions as $permission) {
            Permission::findOrCreate('read '.$permission);
            Permission::findOrCreate('write '.$permission);
        }

        foreach ($this->extraPermissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminPermissions = Permission::all();
        $this->createRoleWithPermissions('Admin', $adminPermissions);

        $visitorPermissions = array_map(fn ($p) => 'read '.$p, $this->permissions);
        $this->createRoleWithPermissions('Visitor', $visitorPermissions);

        $cccPermissions = [...$visitorPermissions, 'write local_parties'];
        $this->createRoleWithPermissions('Country Cluster Coordinator', $cccPermissions);

        // Give all existing users Admin permission when running the seeder for the first time
        // Otherwise no one would have any permissions
        if ($permissionsCount === 0) {
            User::all()->each(function ($user) {
                $user->assignRole('Admin');
            });
        }
    }

    private function createRoleWithPermissions($role, $permissions)
    {
        $role = Role::findOrCreate($role);
        $role->givePermissionTo($permissions);

        return $role;
    }
}
