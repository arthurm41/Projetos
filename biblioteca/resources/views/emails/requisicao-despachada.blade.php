<!DOCTYPE html>
{{-- E-mail enviado ao professor quando os livros estão prontos para retirada no almoxarifado --}}
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Livros disponíveis para retirada</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; padding: 30px;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 10px; padding: 25px; border: 1px solid #ddd;">
        {{-- Título com cor azul indicando que os livros estão disponíveis --}}
        <h2 style="color: #0a58ca; margin-top: 0;">
            Seus livros estão prontos para retirada
        </h2>

        <p>Olá, {{ $requisition->requester?->name ?? 'Professor' }}.</p>

        <p>
            O almoxarife confirmou a separação dos livros da sua requisição. Você já pode retirá-los no almoxarifado.
        </p>

        {{-- Bloco com os detalhes da entrega --}}
        <div style="background: #f8f9fa; border-left: 4px solid #0d6efd; padding: 15px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Livro:</strong> {{ $requisition->book->title }}</p>
            {{-- Matéria(s) do livro --}}
            <p style="margin: 5px 0;"><strong>Matéria:</strong> {{ $requisition->book->subjects->pluck('name')->join(', ') ?: 'Não informada' }}</p>
            <p style="margin: 5px 0;"><strong>Quantidade:</strong> {{ $requisition->quantity }}</p>
            <p style="margin: 5px 0;"><strong>Turma:</strong> {{ $requisition->class_group ?? 'Não informada' }}</p>
            {{-- Quem realizou a separação/entrega dos livros --}}
            <p style="margin: 5px 0;"><strong>Entregue por:</strong> {{ $requisition->delivered_by ?? 'Não informado' }}</p>
            <p style="margin: 5px 0;"><strong>Data de entrega:</strong> {{ \Carbon\Carbon::parse($requisition->dispatched_at)->format('d/m/Y H:i') }}</p>
        </div>

        <p>
            Após retirar os livros, acesse o sistema e confirme o recebimento.
        </p>

        <p style="margin-top: 30px;">
            Atenciosamente,<br>
            <strong>Sistema SenaiStock</strong>
        </p>
    </div>
</body>
</html>
