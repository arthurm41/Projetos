#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

$user = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => 'password',
]);

echo "✅ Usuário criado com sucesso!\n";
echo "📧 Email: {$user->email}\n";
echo "🔑 Senha: password\n";
echo "🎯 Acesse: http://127.0.0.1:8000/admin/login\n";
