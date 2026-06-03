<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Requisição Aprovada</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; padding: 30px;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 10px; padding: 25px; border: 1px solid #ddd;">
        <h2 style="color: #0f5132; margin-top: 0;">
            Requisição aprovada
        </h2>

        <p>Olá, {{ $requisition->requester->name }}.</p>

        <p>
            Sua requisição de livros foi aprovada pelo almoxarifado.
        </p>

        <div style="background: #f8f9fa; border-left: 4px solid #198754; padding: 15px; margin: 20px 0;">
            <p><strong>Livro:</strong> {{ $requisition->book->title }}</p>
            <p><strong>Matéria:</strong> {{ $requisition->book->subject->name ?? 'Não informada' }}</p>
            <p><strong>Quantidade:</strong> {{ $requisition->quantity }}</p>
            <p><strong>Turma:</strong> {{ $requisition->class_group ?? 'Não informada' }}</p>
            <p><strong>Motivo:</strong> {{ $requisition->reason ?? 'Não informado' }}</p>
            <p><strong>Status:</strong> Aprovada</p>
        </div>

        <p>
            Os livros já foram separados do estoque e estão aguardando a confirmação de recebimento.
        </p>

        <p style="margin-top: 30px;">
            Atenciosamente,<br>
            <strong>Sistema SenaiStock</strong>
        </p>
    </div>
</body>
</html>