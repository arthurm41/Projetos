{{-- Página de solicitação de redefinição de senha --}}
<x-guest-layout>
    {{-- Instruções para o usuário --}}
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    {{-- Mensagem de confirmação quando o link foi enviado --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Formulário: insere o e-mail para receber o link de redefinição --}}
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        {{-- Campo: e-mail da conta --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            {{-- Botão "Email Password Reset Link" — envia o e-mail de redefinição --}}
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
