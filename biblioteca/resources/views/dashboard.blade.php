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
                <div class="flex items-start justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }} gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $req->book->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $req->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div class="shrink-0 text-right">
                        <p class="text-sm font-semibold text-indigo-600">{{ $req->quantity }}x</p>
                        @if($req->isPending())
                            <span class="text-xs text-yellow-600 font-medium">Pendente</span>
                        @elseif($req->isApproved())
                            <span class="text-xs text-blue-600 font-medium">Aguardando retirada</span>
                        @endif
                    </div>
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
    <div class="mb-6 bg-indigo-50 border border-indigo-200 rounded-xl p-5">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold text-indigo-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                {{ $pendingCount }} requisição(ões) aguardando aprovação
            </h3>
            <a href="{{ route('requisitions.index') }}" class="text-xs text-indigo-600 hover:underline font-medium">Ver todas →</a>
        </div>
        <div class="space-y-2">
            @foreach($pendingRequisitions as $req)
            <div class="flex items-center justify-between bg-white rounded-lg px-4 py-3">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $req->book->title }}</p>
                    <p class="text-xs text-gray-400">{{ $req->requester->name }} · {{ $req->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-indigo-600">{{ $req->quantity }}x</span>
                    <form method="POST" action="{{ route('requisitions.approve', $req) }}">
                        @csrf
                        <button type="submit"
                                class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                            Aprovar
                        </button>
                    </form>
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
                <a href="{{ route('stock-withdrawals.create') }}"
                   class="flex items-center gap-3 px-4 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                    Registrar Saída
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
