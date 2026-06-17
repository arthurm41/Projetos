{{-- Componente: exibe mensagem de status da sessão (ex: link de reset enviado, logout) --}}
@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }}>
        {{ $status }}
    </div>
@endif
