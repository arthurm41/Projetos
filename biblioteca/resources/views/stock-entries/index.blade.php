<x-app-layout>
    {{-- Título da aba do navegador --}}
    <x-slot name="title">Entradas de Estoque</x-slot>

    {{-- Cabeçalho da página --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Entradas de Estoque (Abastecimento)</h2>
            {{-- Botão para ir para a tela de registrar nova entrada de estoque --}}
            <a href="{{ route('stock-entries.create') }}"
               class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                + Registrar Entrada
            </a>
        </div>
    </x-slot>

    {{-- Formulário de filtros da listagem de entradas --}}
    <form method="GET" action="{{ route('stock-entries.index') }}" class="mb-4 bg-white rounded-xl shadow-sm p-4">
        <div class="flex flex-wrap gap-3 items-end">

            {{-- Campo de busca por título do livro --}}
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Título do livro..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            {{-- Filtro: data inicial do período --}}
            <div class="min-w-[130px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">De</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            {{-- Filtro: data final do período --}}
            <div class="min-w-[130px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Até</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="flex gap-2">
                {{-- Botão para aplicar os filtros --}}
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    Filtrar
                </button>

                {{-- Botão "Limpar" aparece somente quando há filtros ativos --}}
                @if(request()->hasAny(['search','date_from','date_to']))
                <a href="{{ route('stock-entries.index') }}"
                   class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Limpar
                </a>
                @endif
            </div>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">

        {{-- Faixa com total de resultados (aparece somente com filtros ativos) --}}
        @if(request()->hasAny(['search','date_from','date_to']))
        <div class="px-6 py-3 bg-green-50 border-b border-green-100 text-xs text-green-700">
            {{ $entries->total() }} resultado(s) encontrado(s)
        </div>
        @endif

        <div class="overflow-x-auto">
            {{-- Tabela com o histórico de entradas de estoque --}}
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-xs text-gray-500 uppercase">
                        <th class="px-6 py-3">Data</th>         {{-- Data/hora em que a entrada foi registrada --}}
                        <th class="px-6 py-3">Livro</th>        {{-- Título e ISBN do livro --}}
                        <th class="px-6 py-3">Matéria</th>      {{-- Matéria(s) vinculadas ao livro --}}
                        <th class="px-6 py-3 text-center">Qtd.</th>    {{-- Quantidade que entrou no estoque --}}
                        <th class="px-6 py-3 text-center">Antes</th>   {{-- Estoque antes da entrada --}}
                        <th class="px-6 py-3 text-center">Depois</th>  {{-- Estoque após a entrada --}}
                        <th class="px-6 py-3">Registrado por</th>      {{-- Usuário que registrou a entrada --}}
                        <th class="px-6 py-3">Observações</th>         {{-- Notas opcionais sobre a entrada --}}
                        <th class="px-6 py-3 text-right">Ação</th>     {{-- Botão de excluir --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($entries as $entry)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $entry->received_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $entry->book->title }}</p>
                            <p class="text-xs text-gray-400">{{ $entry->book->isbn }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $entry->book->subjects->pluck('name')->join(', ') ?: '—' }}</td>
                        <td class="px-6 py-4 text-center">
                            {{-- Quantidade exibida em verde com sinal de + --}}
                            <span class="font-bold text-green-600">+{{ $entry->quantity }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500">{{ $entry->stock_before }}</td>
                        <td class="px-6 py-4 text-center font-medium text-gray-800">{{ $entry->stock_after }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $entry->user?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-400 max-w-[180px] truncate">{{ $entry->notes ?? '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">

                            {{-- Botão "Editar" — leva para o formulário de edição da entrada --}}
                            <a href="{{ route('stock-entries.edit', $entry) }}"
                               class="px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 rounded-md hover:bg-green-100 transition-colors">
                                Editar
                            </a>

                            {{-- Componente Alpine.js que controla a abertura/fechamento do modal de exclusão --}}
                            <div x-data="{ open: false }">

                                {{-- Botão "Excluir" — abre o modal de confirmação --}}
                                <button type="button" @click="open = true"
                                        class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                                    Excluir
                                </button>

                                {{-- Formulário oculto de DELETE — só é submetido ao confirmar no modal --}}
                                <form x-ref="del" method="POST" action="{{ route('stock-entries.destroy', $entry) }}">
                                    @csrf @method('DELETE')
                                </form>

                                {{-- Modal de confirmação de exclusão da entrada --}}
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
                                            <h3 class="text-base font-bold text-gray-900">Excluir registro de entrada?</h3>
                                            <p class="text-sm text-gray-500 mt-1">O estoque do livro será revertido. Esta ação não pode ser desfeita.</p>
                                        </div>
                                        <div class="flex gap-3 mt-6">
                                            {{-- Botão "Não, voltar" — fecha o modal sem excluir --}}
                                            <button type="button" @click="open = false"
                                                    class="flex-1 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                                Não, voltar
                                            </button>
                                            {{-- Botão "Sim, excluir" — confirma e envia o formulário DELETE --}}
                                            <button type="button" @click="$refs.del.submit()"
                                                    class="flex-1 py-2.5 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                                                Sim, excluir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>{{-- fim flex --}}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        {{-- Linha exibida quando não há entradas na listagem --}}
                        <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                            @if(request()->hasAny(['search','date_from','date_to']))
                                Nenhuma entrada encontrada para os filtros aplicados.
                            @else
                                Nenhuma entrada registrada.
                                <a href="{{ route('stock-entries.create') }}" class="text-blue-600 hover:underline ml-1">Registrar agora</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação — aparece somente quando há mais de uma página de resultados --}}
        @if($entries->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $entries->links() }}</div>
        @endif
    </div>
</x-app-layout>
