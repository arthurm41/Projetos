<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar permissions
        $permissions = [
            ['name' => 'Ver Livros', 'slug' => 'view-books'],
            ['name' => 'Criar Livros', 'slug' => 'create-books'],
            ['name' => 'Editar Livros', 'slug' => 'edit-books'],
            ['name' => 'Ver Entradas', 'slug' => 'view-entries'],
            ['name' => 'Criar Entradas', 'slug' => 'create-entries'],
            ['name' => 'Ver Retiradas', 'slug' => 'view-withdrawals'],
            ['name' => 'Criar Retiradas', 'slug' => 'create-withdrawals'],
            ['name' => 'Ver Relatórios', 'slug' => 'view-reports'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['slug' => $permission['slug']], $permission);
        }

        // Criar roles
        $almoxarife = Role::firstOrCreate(
            ['slug' => 'almoxarife'],
            ['name' => 'Almoxarife', 'description' => 'Responsável pelo almoxarifado']
        );

        $professor = Role::firstOrCreate(
            ['slug' => 'professor'],
            ['name' => 'Professor', 'description' => 'Professor que pode requisitar livros']
        );

        // Atribuir permissions ao Almoxarife
        $almoxarifePermissions = [
            'view-books', 'create-entries', 'view-entries',
            'create-withdrawals', 'view-withdrawals', 'view-reports',
        ];

        foreach ($almoxarifePermissions as $slug) {
            $permission = Permission::where('slug', $slug)->first();
            if ($permission && !$almoxarife->permissions()->where('permission_id', $permission->id)->exists()) {
                $almoxarife->permissions()->attach($permission);
            }
        }

        // Atribuir permissions ao Professor
        $professorPermissions = [
            'view-books', 'create-books', 'edit-books', 'view-entries', 'view-withdrawals',
        ];

        foreach ($professorPermissions as $slug) {
            $permission = Permission::where('slug', $slug)->first();
            if ($permission && !$professor->permissions()->where('permission_id', $permission->id)->exists()) {
                $professor->permissions()->attach($permission);
            }
        }
    }
}
