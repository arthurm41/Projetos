<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

try {
    // Executar migrations
    \Artisan::call('migrate:fresh', ['--force' => true]);
    echo "✓ Migrations executadas com sucesso\n";
    
    // Executar seeders
    \Artisan::call('db:seed', ['--force' => true]);
    echo "✓ Seeders executados com sucesso\n";
    
    // Criar token de teste
    $user = \App\Models\User::first();
    if ($user) {
        $token = $user->createToken('api-token')->plainTextToken;
        echo "\n✓ Usuário de teste criado:\n";
        echo "   Email: {$user->email}\n";
        echo "   Password: password\n";
        echo "   Token: {$token}\n";
    }
} catch (Exception $e) {
    echo "✗ Erro: {$e->getMessage()}\n";
}
