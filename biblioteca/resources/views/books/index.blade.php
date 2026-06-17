<x-app-layout>
    {{-- Título da aba do navegador --}}
    <x-slot name="title">Livros</x-slot>

    {{-- Cabeçalho da página --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Catálogo de Livros</h2>
            {{-- Botão para ir para a tela de cadastrar novo livro (visível só para almoxarife) --}}
            @if(Auth::user()->hasRole('almoxarife'))
            <a href="{{ route('books.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                + Novo Livro
            </a>
            @endif
        </div>
    </x-slot>

    {{-- Formulário de filtros do catálogo de livros --}}
    <form method="GET" action="{{ route('books.index') }}" class="mb-4 bg-white rounded-xl shadow-sm p-4">
        <div class="flex flex-wrap gap-3 items-end">

            {{-- Campo de busca por título, ISBN ou autor --}}
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Título, ISBN ou autor..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Filtro: selecionar matéria para listar apenas os livros daquela matéria --}}
            <div class="min-w-[160px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Matéria</label>
                <select name="subject_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtro: situação do estoque (OK, Baixo ou Zerado) --}}
            <div class="min-w-[140px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="ok"   {{ request('status') === 'ok'   ? 'selected' : '' }}>OK</option>
                    <option value="low"  {{ request('status') === 'low'  ? 'selected' : '' }}>Estoque Baixo</option>
                    <option value="zero" {{ request('status') === 'zero' ? 'selected' : '' }}>Zerado</option>
                </select>
            </div>

            <div class="flex gap-2">
                {{-- Botão para aplicar os filtros --}}
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Filtrar
                </button>

                {{-- Botão "Limpar" aparece somente quando há filtros ativos --}}
                @if(request()->hasAny(['search','subject_id','status']))
                <a href="{{ route('books.index') }}"
                   class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Limpar
                </a>
                @endif
            </div>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">

        {{-- Faixa com total de resultados (aparece somente com filtros ativos) --}}
        @if(request()->hasAny(['search','subject_id','status']))
        <div class="px-6 py-3 bg-blue-50 border-b border-blue-100 text-xs text-blue-700">
            {{ $books->total() }} resultado(s) encontrado(s)
        </div>
        @endif

        <div class="overflow-x-auto">
            {{-- Tabela do catálogo de livros --}}
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-xs text-gray-500 uppercase">
                        <th class="px-6 py-3">Título / ISBN</th>      {{-- Nome do livro e código ISBN --}}
                        <th class="px-6 py-3">Matéria</th>            {{-- Matéria(s) vinculadas ao livro (badges) --}}
                        <th class="px-6 py-3">Autor</th>              {{-- Nome do autor --}}
                        <th class="px-6 py-3 text-center">Estoque</th>  {{-- Quantidade atual em estoque --}}
                        <th class="px-6 py-3 text-center">Mínimo</th>   {{-- Quantidade mínima configurada para o livro --}}
                        <th class="px-6 py-3 text-center">Status</th>   {{-- Badge de status: OK / Baixo / Zerado --}}
                        <th class="px-6 py-3 text-right">Ações</th>     {{-- Botões Editar e Excluir --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($books as $book)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $book->title }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">ISBN: {{ $book->isbn }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{-- Cada matéria vinculada ao livro aparece como um badge --}}
                            @foreach($book->subjects as $s)
                                <span class="inline-block px-1.5 py-0.5 bg-gray-100 text-gray-600 text-xs rounded mr-1">{{ $s->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $book->author ?? '—' }}</td>
                        <td class="px-6 py-4 text-center">
                            {{-- Número do estoque: vermelho se zerado, amarelo se baixo, cinza se OK --}}
                            <span class="font-bold text-lg {{ $book->current_stock === 0 ? 'text-red-600' : ($book->isLowStock() ? 'text-yellow-600' : 'text-gray-800') }}">
                                {{ $book->current_stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500">{{ $book->minimum_stock }}</td>
                        <td class="px-6 py-4 text-center">
                            {{-- Badge de status do estoque --}}
                            @if($book->current_stock === 0)
                                <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">Zerado</span>
                            @elseif($book->isLowStock())
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">Baixo</span>
                            @else
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">OK</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Botões visíveis somente para o almoxarife --}}
                                @if(Auth::user()->hasRole('almoxarife'))

                                {{-- Botão "Ajustar Estoque" com modal inline --}}
                                <div x-data="{ open: false }">
                                    <button type="button" @click="open = true"
                                            class="px-3 py-1.5 text-xs font-medium text-orange-700 bg-orange-50 rounded-md hover:bg-orange-100 transition-colors">
                                        Ajustar Estoque
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
                                        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm" @click.stop>
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center shrink-0">
                                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="text-sm font-bold text-gray-900">Ajustar Estoque</h3>
                                                    <p class="text-xs text-gray-500 mt-0.5 truncate max-w-[200px]">{{ $book->title }}</p>
                                                </div>
                                            </div>
                                            <form method="POST" action="{{ route('books.adjust-stock', $book) }}">
                                                @csrf
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Total de unidades *</label>
                                                    <input type="number" name="current_stock" min="0" required
                                                           value="{{ $book->current_stock }}"
                                                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                                                    <p class="text-xs text-gray-400 mt-1">Atual: <strong>{{ $book->current_stock }}</strong> unidades</p>
                                                </div>
                                                <div class="flex gap-3">
                                                    <button type="button" @click="open = false"
                                                            class="flex-1 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                                        Cancelar
                                                    </button>
                                                    <button type="submit"
                                                            class="flex-1 py-2.5 bg-orange-500 text-white text-sm font-medium rounded-xl hover:bg-orange-600 transition-colors">
                                                        Salvar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Botão "Editar" — leva para a tela de edição do livro --}}
                                <a href="{{ route('books.edit', $book) }}"
                                   class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-md hover:bg-blue-100 transition-colors">
                                    Editar
                                </a>

                                {{-- Componente Alpine.js que controla o modal de exclusão do livro --}}
                                <div x-data="{ open: false }">

                                    {{-- Botão "Excluir" — abre o modal de confirmação --}}
                                    <button type="button" @click="open = true"
                                            class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                                        Excluir
                                    </button>

                                    {{-- Formulário oculto de DELETE — só é submetido ao confirmar no modal --}}
                                    <form x-ref="frm" method="POST" action="{{ route('books.destroy', $book) }}">
                                        @csrf @method('DELETE')
                                    </form>

                                    {{-- Modal de confirmação de exclusão do livro --}}
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
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                                    </svg>
                                                </div>
                                                <h3 class="text-base font-bold text-gray-900">Excluir livro?</h3>
                                                <p class="text-sm text-gray-500 mt-1">Esta ação não pode ser desfeita.</p>
                                            </div>
                                            <div class="flex gap-3 mt-6">
                                                {{-- Botão "Não, voltar" — fecha o modal sem excluir --}}
                                                <button type="button" @click="open = false"
                                                        class="flex-1 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                                    Não, voltar
                                                </button>
                                                {{-- Botão "Sim, excluir" — confirma e envia o formulário DELETE --}}
                                                <button type="button" @click="$refs.frm.submit()"
                                                        class="flex-1 py-2.5 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                                                    Sim, excluir
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        {{-- Linha exibida quando não há livros na listagem --}}
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            @if(request()->hasAny(['search','subject_id','status']))
                                Nenhum livro encontrado para os filtros aplicados.
                            @else
                                Nenhum livro cadastrado.
                                @if(Auth::user()->hasRole('almoxarife'))
                                <a href="{{ route('books.create') }}" class="text-blue-600 hover:underline ml-1">Cadastrar agora</a>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação — aparece somente quando há mais de uma página de resultados --}}
        @if($books->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $books->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
