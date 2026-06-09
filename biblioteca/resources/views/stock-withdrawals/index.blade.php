<x-app-layout>
    <x-slot name="title">Saídas de Estoque</x-slot>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Histórico de Saídas</h2>
    </x-slot>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('stock-withdrawals.index') }}" class="mb-4 bg-white rounded-xl shadow-sm p-4">
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Título do livro ou turma..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div class="min-w-[130px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">De</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div class="min-w-[130px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Até</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-orange-500 text-white text-sm font-medium rounded-lg hover:bg-orange-600 transition-colors">
                    Filtrar
                </button>
                @if(request()->hasAny(['search','date_from','date_to']))
                <a href="{{ route('stock-withdrawals.index') }}"
                   class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Limpar
                </a>
                @endif
            </div>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if(request()->hasAny(['search','date_from','date_to']))
        <div class="px-6 py-3 bg-orange-50 border-b border-orange-100 text-xs text-orange-700">
            {{ $withdrawals->total() }} resultado(s) encontrado(s)
        </div>
        @endif
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-xs text-gray-500 uppercase">
                        <th class="px-6 py-3">Data</th>
                        <th class="px-6 py-3">Livro</th>
                        <th class="px-6 py-3">Matéria</th>
                        <th class="px-6 py-3 text-center">Qtd.</th>
                        <th class="px-6 py-3 text-center">Antes</th>
                        <th class="px-6 py-3 text-center">Depois</th>
                        <th class="px-6 py-3">Turma</th>
                        <th class="px-6 py-3">Motivo</th>
                        @if(Auth::user()->hasRole('almoxarife'))
                        <th class="px-6 py-3 text-right">Ação</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($withdrawals as $w)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $w->withdrawn_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $w->book->title }}</p>
                            <p class="text-xs text-gray-400">{{ $w->book->isbn }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $w->book->subjects->pluck('name')->join(', ') ?: '—' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-orange-500">-{{ $w->quantity }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500">{{ $w->stock_before }}</td>
                        <td class="px-6 py-4 text-center font-medium text-gray-800">{{ $w->stock_after }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $w->class_group ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-400 max-w-[180px] truncate">{{ $w->reason ?? '—' }}</td>
                        @if(Auth::user()->hasRole('almoxarife'))
                        <td class="px-6 py-4 text-right">
                            <div x-data="{ open: false }">
                                <button type="button" @click="open = true"
                                        class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                                    Excluir
                                </button>
                                <form x-ref="del" method="POST" action="{{ route('stock-withdrawals.destroy', $w) }}">
                                    @csrf @method('DELETE')
                                </form>
                                <div x-show="open" x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                                     @click.self="open = false">
                                    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm" @click.stop>
                                        <div class="flex flex-col items-center text-center">
                                            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-base font-bold text-gray-900">Excluir registro de saída?</h3>
                                            <p class="text-sm text-gray-500 mt-1">O estoque do livro será restaurado. Esta ação não pode ser desfeita.</p>
                                        </div>
                                        <div class="flex gap-3 mt-6">
                                            <button type="button" @click="open = false"
                                                    class="flex-1 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                                Não, voltar
                                            </button>
                                            <button type="button" @click="$refs.del.submit()"
                                                    class="flex-1 py-2.5 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                                                Sim, excluir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->hasRole('almoxarife') ? 9 : 8 }}" class="px-6 py-12 text-center text-gray-400">
                            @if(request()->hasAny(['search','date_from','date_to']))
                                Nenhuma saída encontrada para os filtros aplicados.
                            @else
                                Nenhuma saída registrada.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($withdrawals->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $withdrawals->links() }}</div>
        @endif
    </div>
</x-app-layout>
