{{-- Barra de navegação principal — usada em todas as páginas autenticadas --}}
<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- ================================================ --}}
            {{-- LADO ESQUERDO: Logo + links de navegação desktop  --}}
            {{-- ================================================ --}}
            <div class="flex items-center">
                {{-- Logo clicável que leva para o dashboard --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0 mr-8">
                    <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="font-bold text-gray-800 text-lg">SenaiStock</span>
                </a>

                {{-- Links de navegação — visíveis apenas em telas médias e grandes --}}
                <div class="hidden sm:flex sm:items-center sm:gap-1">
                    {{-- Link: Dashboard (visível para todos os usuários) --}}
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        Dashboard
                    </a>

                    {{-- Links exclusivos do almoxarife --}}
                    @if(Auth::user()->hasRole('almoxarife'))
                        {{-- Link: Livros — listagem e cadastro de títulos --}}
                        <a href="{{ route('books.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('books.*') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Livros
                        </a>

                        {{-- Link: Matérias — listagem e cadastro de matérias --}}
                        <a href="{{ route('subjects.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('subjects.*') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Matérias
                        </a>

                        {{-- Link: Entradas — histórico de entradas de estoque --}}
                        <a href="{{ route('stock-entries.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('stock-entries.*') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Entradas
                        </a>

                        {{-- Link: Saídas — histórico de saídas de estoque --}}
                        <a href="{{ route('stock-withdrawals.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('stock-withdrawals.*') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Saídas
                        </a>

                        {{-- Link: Estoque Mínimo — livros abaixo do nível mínimo --}}
                        <a href="{{ route('low-stock') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('low-stock') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            Estoque Mínimo
                        </a>

                        {{-- Link: E-mails — abre o Mailpit em nova aba para ver e-mails enviados --}}
                        <a href="{{ route('mailpit') }}"
                           target="_blank"
                           class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('mailpit') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                            E-mails
                        </a>
                    @endif

                    {{-- Link: Requisições (visível para todos) --}}
                    <a href="{{ route('requisitions.index') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('requisitions.*') ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        Requisições
                    </a>
                </div>
            </div>

            {{-- ================================================ --}}
            {{-- LADO DIREITO: Dropdown do usuário logado          --}}
            {{-- ================================================ --}}
            <div class="hidden sm:flex sm:items-center">
                <div x-data="{ open: false }" class="relative">
                    {{-- Botão com avatar e nome do usuário — abre o dropdown --}}
                    <button @click="open = !open"
                            class="flex items-center gap-2 px-3 py-2 rounded-md text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                        {{-- Avatar com inicial do nome --}}
                        <div class="w-7 h-7 bg-gray-300 rounded-full flex items-center justify-center text-gray-700 font-semibold text-xs">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    {{-- Dropdown com opção de sair --}}
                    <div x-show="open" @click.outside="open = false" x-transition
                         class="absolute right-0 mt-1 w-44 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                        {{-- Formulário de logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            {{-- Botão "Sair" — encerra a sessão do usuário --}}
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md">
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ================================================ --}}
            {{-- BOTÃO HAMBÚRGUER — visível apenas em mobile       --}}
            {{-- Abre/fecha o menu de navegação mobile             --}}
            {{-- ================================================ --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    {{-- Ícone de 3 linhas (menu fechado) --}}
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        {{-- Ícone de X (menu aberto) --}}
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ================================================ --}}
    {{-- MENU MOBILE — exibido ao clicar no hambúrguer    --}}
    {{-- ================================================ --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1">
            {{-- Link mobile: Dashboard --}}
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                Dashboard
            </a>

            {{-- Links mobile exclusivos do almoxarife --}}
            @if(Auth::user()->hasRole('almoxarife'))
                <a href="{{ route('books.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    Livros
                </a>
                <a href="{{ route('subjects.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    Matérias
                </a>
                <a href="{{ route('stock-entries.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    Entradas de Estoque
                </a>
                <a href="{{ route('stock-withdrawals.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    Saídas de Estoque
                </a>
                <a href="{{ route('low-stock') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    Estoque Mínimo
                </a>
                <a href="{{ route('mailpit') }}" target="_blank" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    E-mails
                </a>
            @endif

            {{-- Link mobile: Requisições (visível para todos) --}}
            <a href="{{ route('requisitions.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                Requisições
            </a>
        </div>

        {{-- Seção de informações do usuário no menu mobile --}}
        <div class="pt-4 pb-3 border-t border-gray-200 px-4">
            <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>

            {{-- Botão "Sair" no menu mobile --}}
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="text-sm text-red-600 font-medium">
                    Sair
                </button>
            </form>
        </div>
    </div>
</nav>
