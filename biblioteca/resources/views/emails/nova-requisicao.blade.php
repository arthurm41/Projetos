<!DOCTYPE html>
{{-- E-mail enviado ao almoxarife quando um professor cria uma nova requisição --}}
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Requisição de Livro</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; padding: 30px;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 10px; padding: 25px; border: 1px solid #ddd;">
        {{-- Título do e-mail --}}
        <h2 style="color: #856404; margin-top: 0;">
            Nova requisição aguardando aprovação
        </h2>

        <p>Uma nova requisição de livro foi registrada e está aguardando sua aprovação.</p>

        {{-- Bloco com os detalhes da requisição --}}
        <div style="background: #fff8e1; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Professor:</strong> {{ $requisition->requester->name }}</p>
            <p style="margin: 5px 0;"><strong>Livro:</strong> {{ $requisition->book->title }}</p>
            {{-- Matéria(s) do livro --}}
            <p style="margin: 5px 0;"><strong>Matéria:</strong> {{ $requisition->book->subjects->pluck('name')->join(', ') ?: 'Não informada' }}</p>
            <p style="margin: 5px 0;"><strong>Quantidade:</strong> {{ $requisition->quantity }}</p>
            <p style="margin: 5px 0;"><strong>Turma:</strong> {{ $requisition->class_group ?? 'Não informada' }}</p>
            <p style="margin: 5px 0;"><strong>Motivo:</strong> {{ $requisition->reason ?? 'Não informado' }}</p>
        </div>

        <p>Acesse o sistema para aprovar ou recusar a requisição.</p>

        <p style="margin-top: 30px;">
            Atenciosamente,<br>
            <strong>Sistema SenaiStock</strong>
        </p>
    </div>
</body>
</html>
