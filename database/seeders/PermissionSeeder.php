<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create permissions
        $permissions = [
            'add-facility',
            'edit-facility',
            'delete-facility',
            'view-facility',
            'add-user',
            'edit-user',
            'delete-user',
            'view-user',
            'add-role',
            'edit-role',
            'delete-role',
            'view-role',
            'add-permission',
            'edit-permission',
            'delete-permission',
            'view-permission',
            'view-reservations',
            'update-reservations',
        ];
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }
    }
}
