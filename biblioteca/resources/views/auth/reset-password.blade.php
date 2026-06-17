{{-- Página de redefinição de senha (acessada via link no e-mail) --}}
<x-guest-layout>
    {{-- Formulário de redefinição de senha --}}
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        {{-- Token de redefinição enviado via URL (campo oculto) --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Campo: e-mail (pré-preenchido com o valor da URL) --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Campo: nova senha --}}
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Campo: confirmação da nova senha --}}
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            {{-- Botão "Reset Password" — salva a nova senha --}}
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
