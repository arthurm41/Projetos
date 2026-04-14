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

        $coordenador = Role::firstOrCreate(
            ['slug' => 'coordenador'],
            ['name' => 'Coordenador', 'description' => 'Coordenador de estoque e relatórios']
        );

        // Atribuir permissions ao Almoxarife
        $almoxarifePermissions = [
            'view-books', 'create-entries', 'view-entries', 
            'create-withdrawals', 'view-withdrawals'
        ];

        foreach ($almoxarifePermissions as $slug) {
            $permission = Permission::where('slug', $slug)->first();
            if ($permission && !$almoxarife->permissions()->where('permission_id', $permission->id)->exists()) {
                $almoxarife->permissions()->attach($permission);
            }
        }

        // Atribuir permissions ao Coordenador
        $coordenadorPermissions = [
            'view-books', 'create-books', 'edit-books',
            'view-entries', 'view-withdrawals', 'view-reports'
        ];

        foreach ($coordenadorPermissions as $slug) {
            $permission = Permission::where('slug', $slug)->first();
            if ($permission && !$coordenador->permissions()->where('permission_id', $permission->id)->exists()) {
                $coordenador->permissions()->attach($permission);
            }
        }
    }
}
