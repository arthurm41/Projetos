<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Executar seeder de roles e permissions primeiro
        $this->call(RolesAndPermissionsSeeder::class);

        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Atribuir role almoxarife ao usuário de teste
        $almoxarifeRole = \App\Models\Role::where('slug', 'almoxarife')->first();
        if ($almoxarifeRole) {
            $user->roles()->attach($almoxarifeRole);
        }
    }
}
