<x-guest-layout>
<div class="min-h-screen flex">

    {{-- ====================================================== --}}
    {{-- PAINEL ESQUERDO — Marca e apresentação do sistema      --}}
    {{-- Visível apenas em telas grandes (lg:) --}}
    {{-- ====================================================== --}}
    <div class="hidden lg:flex lg:w-1/2 bg-red-600 flex-col justify-between p-12 relative overflow-hidden">

        {{-- Padrão de grade decorativo no fundo do painel --}}
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
                        <path d="M 60 0 L 0 0 0 60" fill="none" stroke="white" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)"/>
            </svg>
        </div>

        {{-- Círculos decorativos de fundo --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white opacity-5 rounded-full"></div>
        <div class="absolute -bottom-32 -left-16 w-80 h-80 bg-white opacity-5 rounded-full"></div>

        {{-- Logo do sistema no topo do painel --}}
        <div class="relative z-10 flex items-center gap-3">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="text-white text-xl font-bold tracking-tight">SenaiStock</span>
        </div>

        {{-- Título e descrição do sistema --}}
        <div class="relative z-10 space-y-6">
            <div>
                <p class="text-red-200 text-sm font-medium uppercase tracking-widest mb-3">Sistema de Gestão</p>
                <h1 class="text-white text-4xl font-extrabold leading-tight">
                    Controle de Estoque<br>de Livros Didáticos
                </h1>
                <p class="text-red-100 mt-4 text-base leading-relaxed max-w-xs">
                    Gerencie requisições, entradas e saídas de materiais didáticos de forma simples e eficiente.
                </p>
            </div>

            {{-- Lista de funcionalidades em destaque --}}
            <div class="space-y-3">
                @foreach([
                    ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Requisições de livros por professores'],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Controle de estoque em tempo real'],
                    ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Histórico completo de movimentações'],
                ] as $item)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                    </div>
                    <span class="text-red-100 text-sm">{{ $item['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Rodapé do painel com nome da instituição --}}
        <div class="relative z-10">
            <p class="text-red-300 text-xs">
                SENAI — Serviço Nacional de Aprendizagem Industrial
            </p>
        </div>
    </div>

    {{-- ====================================================== --}}
    {{-- PAINEL DIREITO — Formulário de login                   --}}
    {{-- ====================================================== --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
        <div class="w-full max-w-md">

            {{-- Logo do sistema exibida somente em mobile (painel esquerdo fica oculto) --}}
            <div class="flex items-center gap-3 mb-10 lg:hidden">
                <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-gray-800 text-xl font-bold">SenaiStock</span>
            </div>

            {{-- Saudação do formulário --}}
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Bem-vindo de volta</h2>
                <p class="text-gray-500 text-sm mt-1">Entre com suas credenciais para continuar</p>
            </div>

            {{-- Alerta de sessão (ex: mensagem após reset de senha ou logout) --}}
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Formulário de autenticação --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Campo de e-mail --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        E-mail
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               required autofocus autocomplete="username"
                               placeholder="seu@email.com"
                               class="w-full pl-10 pr-4 py-3 border rounded-xl text-sm bg-white transition-colors
                                      focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                                      {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Campo de senha com link "Esqueceu a senha?" --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                        {{-- Link para redefinição de senha --}}
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-xs text-red-600 hover:text-red-700 font-medium">
                                Esqueceu a senha?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" type="password" name="password"
                               required autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full pl-10 pr-4 py-3 border rounded-xl text-sm bg-white transition-colors
                                      focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                                      {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Checkbox "Manter conectado" (lembrar sessão) --}}
                <div class="flex items-center gap-2.5">
                    <input id="remember_me" type="checkbox" name="remember"
                           class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500 cursor-pointer">
                    <label for="remember_me" class="text-sm text-gray-600 cursor-pointer select-none">
                        Manter conectado
                    </label>
                </div>

                {{-- Botão "Entrar" — submete o formulário de login --}}
                <button type="submit"
                        class="w-full py-3 px-4 bg-red-600 hover:bg-red-700 active:bg-red-800
                               text-white text-sm font-semibold rounded-xl transition-colors
                               focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2
                               flex items-center justify-center gap-2">
                    Entrar
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            {{-- Rodapé com nome do sistema --}}
            <p class="mt-8 text-center text-xs text-gray-400">
                SenaiStock · Sistema de Controle de Estoque
            </p>
        </div>
    </div>

</div>
</x-guest-layout>
