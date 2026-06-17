<x-app-layout>
    {{-- Título da aba do navegador --}}
    <x-slot name="title">Editar Livro</x-slot>

    {{-- Cabeçalho da página --}}
    <x-slot name="header">
        <div class="flex items-center gap-3">
            {{-- Seta de voltar para o catálogo de livros --}}
            <a href="{{ route('books.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-xl font-semibold text-gray-800">Editar Livro</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm p-6">

            {{-- Card de estoque atual — exibe a quantidade disponível no momento e atalhos --}}
            <div x-data="{ openAdjust: false }" class="mb-5 p-4 bg-gray-50 rounded-lg flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Estoque atual</p>
                    {{-- Número em amarelo se estiver abaixo do mínimo, verde se OK --}}
                    <p class="text-2xl font-bold {{ $book->isLowStock() ? 'text-yellow-600' : 'text-green-600' }}">
                        {{ $book->current_stock }} unidades
                    </p>
                </div>
                <div class="flex gap-2">
                    {{-- Botão "Ajustar Estoque" — abre modal para digitar o total exato --}}
                    <button type="button" @click="openAdjust = true"
                            class="px-3 py-2 bg-orange-500 text-white text-xs rounded-lg hover:bg-orange-600 transition-colors">
                        Ajustar Estoque
                    </button>
                    {{-- Botão "+ Entrada" — atalho para registrar entrada de estoque para este livro --}}
                    <a href="{{ route('stock-entries.create') }}?book_id={{ $book->id }}"
                       class="px-3 py-2 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors">
                        + Entrada
                    </a>
                </div>

                {{-- Modal de ajuste direto do estoque --}}
                <div x-show="openAdjust" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                     @click.self="openAdjust = false">
                    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm" @click.stop>
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Ajustar Estoque</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Digite o total exato de unidades disponíveis</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('books.adjust-stock', $book) }}">
                            @csrf
                            {{-- Campo: número total de livros que existem fisicamente --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total de unidades *</label>
                                <input type="number" name="current_stock" min="0" required
                                       value="{{ $book->current_stock }}"
                                       class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                       autofocus>
                                <p class="text-xs text-gray-400 mt-1">Estoque atual: <strong>{{ $book->current_stock }}</strong> unidades</p>
                            </div>
                            <div class="flex gap-3">
                                {{-- Botão "Cancelar" — fecha o modal --}}
                                <button type="button" @click="openAdjust = false"
                                        class="flex-1 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                    Cancelar
                                </button>
                                {{-- Botão "Salvar" — define o estoque para o número informado --}}
                                <button type="submit"
                                        class="flex-1 py-2.5 bg-orange-500 text-white text-sm font-medium rounded-xl hover:bg-orange-600 transition-colors">
                                    Salvar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Formulário de edição do livro --}}
            <form method="POST" action="{{ route('books.update', $book) }}" class="space-y-5">
                @csrf @method('PUT')

                {{-- Campo: título do livro (obrigatório) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-400 @enderror">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Campo: ISBN do livro (obrigatório) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ISBN *</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('isbn') border-red-400 @enderror">
                    @error('isbn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Campo: seleção de matéria(s) por checkboxes — matérias já vinculadas aparecem pré-marcadas --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Matéria(s) *
                        <span class="text-xs font-normal text-gray-400 ml-1">Selecione uma ou mais</span>
                    </label>
                    @php
                        // IDs das matérias já salvas (ou do campo antigo em caso de erro de validação)
                        $selectedIds = old('subject_ids', $book->subjects->pluck('id')->toArray());
                    @endphp
                    {{-- Grade de checkboxes — cada checkbox representa uma matéria cadastrada no sistema --}}
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($subjects as $subject)
                        <label class="flex items-center gap-2.5 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-colors">
                            <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}"
                                   class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                   {{ in_array($subject->id, $selectedIds) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $subject->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('subject_ids') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Campos: autor e editora lado a lado --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
                        <input type="text" name="author" value="{{ old('author', $book->author) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Editora</label>
                        <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                {{-- Campos: edição e estoque mínimo lado a lado --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Edição</label>
                        <input type="text" name="edition" value="{{ old('edition', $book->edition) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        {{-- Estoque mínimo: quantidade abaixo da qual o livro é marcado como "Baixo" --}}
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estoque Mínimo</label>
                        <input type="number" name="minimum_stock" value="{{ old('minimum_stock', $book->minimum_stock) }}" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    {{-- Botão "Salvar Alterações" — envia o formulário para atualizar o livro --}}
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Salvar Alterações
                    </button>

                    {{-- Botão "Cancelar" — descarta as alterações e volta para o catálogo --}}
                    <a href="{{ route('books.index') }}"
                       class="px-6 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
