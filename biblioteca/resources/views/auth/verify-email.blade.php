{{-- Página de verificação de e-mail — exibida após o registro, antes de liberar o acesso --}}
<x-guest-layout>
    {{-- Orientação para o usuário verificar o e-mail --}}
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    {{-- Confirmação de que um novo link de verificação foi enviado --}}
    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        {{-- Formulário: botão para reenviar o e-mail de verificação --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                {{-- Botão "Resend Verification Email" — envia novo link de verificação --}}
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        {{-- Formulário: botão de logout caso o usuário queira sair --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            {{-- Botão "Log Out" — encerra a sessão --}}
            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
