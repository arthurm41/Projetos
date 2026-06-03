<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
    </x-slot>

    @if(!Auth::user()->hasRole('almoxarife'))
    {{-- Dashboard do Professor --}}
    <div class="max-w-2xl space-y-6">
        <div class="bg-indigo-600 rounded-xl p-6 text-white">
            <p class="text-indigo-200 text-sm mb-1">Bem-vindo(a),</p>
            <p class="text-2xl font-bold">{{ Auth::user()->name }}</p>
            <p class="text-indigo-200 text-sm mt-1">Professor · SenaiStock</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-5">
                <h3 class="font-semibold text-gray-800">Minhas Requisições</h3>
                <a href="{{ route('requisitions.index') }}" class="text-xs text-indigo-600 hover:underline">Ver todas</a>
            </div>

            @forelse($pendingRequisitions as $req)
                <div class="py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $req->book->title }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $req->created_at->format('d/m/Y') }} · {{ $req->quantity }}x</p>
                        </div>
                        <div class="shrink-0 text-right">
                            @if($req->isPending())
                                <span class="inline-block px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Pendente</span>
                            @elseif($req->isApproved())
                                <span class="inline-block px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Aprovada</span>
                            @elseif($req->isDispatched())
                                <span class="inline-block px-2 py-0.5 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">Em retirada</span>
                            @endif
                        </div>
                    </div>

                    {{-- Previsão de entrega --}}
                    @if($req->isApproved() && $req->estimated_delivery_from)
                    <div class="mt-2 flex items-center gap-1.5 text-xs text-blue-600">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Previsão: {{ $req->estimated_delivery_from->format('d/m') }} – {{ $req->estimated_delivery_to->format('d/m/Y') }}
                    </div>
                    @endif

                    {{-- Aviso de em retirada --}}
                    @if($req->isDispatched())
                    <div class="mt-2 flex items-center justify-between">
                        <p class="text-xs text-purple-600 font-medium">
                            Livros entregues em {{ $req->dispatched_at->format('d/m/Y \à\s H:i') }} — confirme o recebimento.
                        </p>
                        <a href="{{ route('requisitions.show', $req) }}"
                           class="text-xs text-indigo-600 font-medium hover:underline">Confirmar →</a>
                    </div>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-400 py-4 text-center">Nenhuma requisição em aberto.</p>
            @endforelse

            <div class="mt-5 pt-4 border-t border-gray-100">
                <a href="{{ route('requisitions.create') }}"
                   class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nova Requisição
                </a>
            </div>
        </div>
    </div>
    @else
    {{-- Dashboard do Almoxarife --}}
    {{-- Cards de Resumo --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total de Títulos</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalBooks }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Matérias</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalSubjects }}</p>
            </div>
        </div>

        <a href="{{ route('low-stock') }}" class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Abaixo do Mínimo</p>
                <p class="text-2xl font-bold {{ $lowStockCount > 0 ? 'text-yellow-600' : 'text-gray-800' }}">{{ $lowStockCount }}</p>
            </div>
        </a>

        <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Sem Estoque</p>
                <p class="text-2xl font-bold {{ $zeroStockCount > 0 ? 'text-red-600' : 'text-gray-800' }}">{{ $zeroStockCount }}</p>
            </div>
        </div>

        <a href="{{ route('requisitions.index') }}" class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4 hover:shadow-md transition-shadow sm:col-span-2 lg:col-span-1">
            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">
                    {{ Auth::user()->hasRole('almoxarife') ? 'Requisições Pendentes' : 'Minhas Requisições' }}
                </p>
                <p class="text-2xl font-bold {{ $pendingCount > 0 ? 'text-indigo-600' : 'text-gray-800' }}">{{ $pendingCount }}</p>
            </div>
        </a>
    </div>

    {{-- Requisições Pendentes (almoxarife) --}}
    @if($pendingCount > 0)
    <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-xl p-5">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-yellow-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $pendingCount }} requisição(ões) aguardando aprovação
            </h3>
            <a href="{{ route('requisitions.index') }}" class="text-xs text-yellow-700 hover:underline font-medium">Ver todas →</a>
        </div>
        <div class="space-y-2">
            @foreach($pendingRequisitions as $req)
            <div class="flex items-center justify-between bg-white rounded-lg px-4 py-3">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $req->book->title }}</p>
                    <p class="text-xs text-gray-400">{{ $req->requester->name }} · {{ $req->quantity }}x · {{ $req->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div x-data="{ open: false }">
                    <button type="button" @click="open = true"
                            class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                        Aprovar
                    </button>
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                         @click.self="open = false">
                        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md" @click.stop>
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900">Aprovar Requisição #{{ $req->id }}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $req->book->title }} · {{ $req->quantity }}x · {{ $req->requester->name }}</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('requisitions.approve', $req) }}">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Data inicial da previsão de entrega</label>
                                        <input type="date" name="estimated_delivery_from" required
                                               min="{{ date('Y-m-d') }}"
                                               class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Data final da previsão de entrega</label>
                                        <input type="date" name="estimated_delivery_to" required
                                               min="{{ date('Y-m-d') }}"
                                               class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    </div>
                                </div>
                                <div class="flex gap-3 mt-6">
                                    <button type="button" @click="open = false"
                                            class="flex-1 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                            class="flex-1 py-2.5 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                                        Aprovar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Aprovadas aguardando confirmação de entrega (almoxarife) --}}
    @if($dispatchedCount > 0)
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-5">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-blue-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $dispatchedCount }} requisição(ões) aguardando confirmação de entrega
            </h3>
            <a href="{{ route('requisitions.index') }}" class="text-xs text-blue-600 hover:underline font-medium">Ver todas →</a>
        </div>
        <div class="space-y-2">
            @foreach($dispatchedRequisitions as $req)
            <div class="flex items-center justify-between bg-white rounded-lg px-4 py-3">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $req->book->title }}</p>
                    <p class="text-xs text-gray-400">
                        {{ $req->requester->name }} · {{ $req->quantity }}x
                        @if($req->estimated_delivery_to && $req->estimated_delivery_to->isPast())
                            · <span class="text-red-500 font-medium">Previsão expirada</span>
                        @elseif($req->estimated_delivery_to)
                            · até {{ $req->estimated_delivery_to->format('d/m/Y') }}
                        @endif
                    </p>
                </div>
                <div x-data="{ open: false }">
                    <button type="button" @click="open = true"
                            class="px-3 py-1.5 text-xs font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700 transition-colors">
                        Confirmar Entrega
                    </button>
                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                         @click.self="open = false">
                        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md" @click.stop>
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8 8-4-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900">Confirmar Entrega #{{ $req->id }}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $req->book->title }} · para {{ $req->requester->name }}</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('requisitions.dispatch', $req) }}">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Data e hora da entrega</label>
                                        <input type="datetime-local" name="dispatched_at" required
                                               value="{{ now()->format('Y-m-d\TH:i') }}"
                                               class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Quem realizou a retirada</label>
                                        <input type="text" name="delivered_by" required
                                               placeholder="Nome completo de quem retirou os livros"
                                               class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    </div>
                                </div>
                                <div class="flex gap-3 mt-6">
                                    <button type="button" @click="open = false"
                                            class="flex-1 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                            class="flex-1 py-2.5 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors">
                                        Confirmar Entrega
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Ações Rápidas --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Ações Rápidas</h3>
            <div class="space-y-3">
                <a href="{{ route('stock-entries.create') }}"
                   class="flex items-center gap-3 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Registrar Entrada
                </a>
                <a href="{{ route('stock-withdrawals.index') }}"
                   class="flex items-center gap-3 px-4 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                    Histórico de Saídas
                </a>
                <a href="{{ route('books.create') }}"
                   class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Cadastrar Livro
                </a>
            </div>
        </div>

        {{-- Entradas Recentes --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-800">Entradas Recentes</h3>
                <a href="{{ route('stock-entries.index') }}" class="text-xs text-blue-600 hover:underline">Ver todas</a>
            </div>
            @forelse($recentEntries as $entry)
                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div>
                        <p class="text-sm font-medium text-gray-800 truncate max-w-[160px]">{{ $entry->book->title }}</p>
                        <p class="text-xs text-gray-400">{{ $entry->received_at->format('d/m/Y') }}</p>
                    </div>
                    <span class="text-sm font-semibold text-green-600">+{{ $entry->quantity }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-400">Nenhuma entrada registrada.</p>
            @endforelse
        </div>

        {{-- Saídas Recentes --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-800">Saídas Recentes</h3>
                <a href="{{ route('stock-withdrawals.index') }}" class="text-xs text-blue-600 hover:underline">Ver todas</a>
            </div>
            @forelse($recentWithdrawals as $w)
                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div>
                        <p class="text-sm font-medium text-gray-800 truncate max-w-[160px]">{{ $w->book->title }}</p>
                        <p class="text-xs text-gray-400">{{ $w->class_group ?? '—' }}</p>
                    </div>
                    <span class="text-sm font-semibold text-orange-500">-{{ $w->quantity }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-400">Nenhuma saída registrada.</p>
            @endforelse
        </div>
    </div>

    @if($lowStockBooks->count() > 0)
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-yellow-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Atenção: livros abaixo do estoque mínimo
            </h3>
            <a href="{{ route('low-stock') }}" class="text-xs text-blue-600 hover:underline">Ver todos</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-yellow-700 uppercase border-b border-yellow-200">
                        <th class="pb-2">Título</th>
                        <th class="pb-2">Matéria</th>
                        <th class="pb-2 text-center">Atual</th>
                        <th class="pb-2 text-center">Mínimo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockBooks as $book)
                    <tr class="border-b border-yellow-100">
                        <td class="py-2 font-medium text-gray-800">{{ $book->title }}</td>
                        <td class="py-2 text-gray-500">{{ $book->subject->name }}</td>
                        <td class="py-2 text-center font-bold {{ $book->current_stock === 0 ? 'text-red-600' : 'text-yellow-600' }}">
                            {{ $book->current_stock }}
                        </td>
                        <td class="py-2 text-center text-gray-500">{{ $book->minimum_stock }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @endif
</x-app-layout>
