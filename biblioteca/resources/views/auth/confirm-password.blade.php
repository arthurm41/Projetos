{{-- Página de confirmação de senha — exibida antes de acessar áreas sensíveis (ex: excluir conta) --}}
<x-guest-layout>
    {{-- Aviso explicando que a área exige confirmação de senha --}}
    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    {{-- Formulário de confirmação de senha --}}
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        {{-- Campo: senha atual do usuário --}}
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            {{-- Botão "Confirm" — valida a senha e libera o acesso à área segura --}}
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
