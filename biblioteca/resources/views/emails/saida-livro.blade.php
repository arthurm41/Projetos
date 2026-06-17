<!DOCTYPE html>
{{-- E-mail de notificação automática enviado ao almoxarife quando uma saída de estoque é registrada --}}
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Saída de Livro Registrada</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; padding: 30px;">
    <div style="max-width: 650px; margin: auto; background: #ffffff; border-radius: 12px; padding: 25px; border: 1px solid #ddd;">
        {{-- Título com cor vermelha indicando saída de estoque --}}
        <h2 style="color: #b91c1c; margin-top: 0;">
            📚 Saída de livro registrada
        </h2>

        <p>Uma nova saída de livro foi registrada no sistema <strong>SenaiStock</strong>.</p>

        {{-- Bloco com os detalhes da saída --}}
        <div style="background: #f8f9fa; border-left: 4px solid #dc2626; padding: 15px; margin: 20px 0;">
            <p><strong>Livro:</strong> {{ $withdrawal->book?->title ?? 'Não informado' }}</p>
            {{-- Matéria(s) do livro --}}
            <p><strong>Matéria:</strong> {{ $withdrawal->book?->subjects?->pluck('name')->join(', ') ?: 'Não informada' }}</p>
            {{-- Quantidade retirada --}}
            <p><strong>Quantidade retirada:</strong> {{ $withdrawal->quantity }}</p>
            {{-- Estoque antes e depois da saída --}}
            <p><strong>Estoque antes:</strong> {{ $withdrawal->stock_before }}</p>
            <p><strong>Estoque depois:</strong> {{ $withdrawal->stock_after }}</p>
            <p><strong>Turma:</strong> {{ $withdrawal->class_group ?? 'Não informada' }}</p>
            <p><strong>Motivo:</strong> {{ $withdrawal->reason ?? 'Não informado' }}</p>
            <p><strong>Data da saída:</strong> {{ $withdrawal->withdrawn_at ? $withdrawal->withdrawn_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</p>
            {{-- Usuário que registrou a saída --}}
            <p><strong>Registrado por:</strong> {{ $withdrawal->user->name ?? 'Almoxarife' }}</p>
        </div>

        <p>
            Essa mensagem foi gerada automaticamente para controle do almoxarifado.
        </p>

        <p style="margin-top: 30px;">
            Atenciosamente,<br>
            <strong>Sistema SenaiStock</strong>
        </p>
    </div>
</body>
</html>
