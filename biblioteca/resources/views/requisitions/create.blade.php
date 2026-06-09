<x-app-layout>
    <x-slot name="title">Nova Requisição</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('requisitions.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-xl font-semibold text-gray-800">Solicitar Livros</h2>
        </div>
    </x-slot>

    <div class="max-w-lg">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('requisitions.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Livro *</label>
                    <select name="book_id" required id="book-select"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('book_id') border-red-400 @enderror">
                        <option value="">Selecione o livro...</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}"
                                    data-stock="{{ $book->current_stock }}"
                                    data-subject="{{ $book->subjects->pluck('name')->join(', ') }}"
                                    {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                {{ $book->title }} ({{ $book->subjects->pluck('name')->join(', ') }}) — Estoque: {{ $book->current_stock }}
                            </option>
                        @endforeach
                    </select>
                    @error('book_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div id="stock-info" class="hidden p-3 rounded-lg text-sm"></div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade *</label>
                    <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('quantity') border-red-400 @enderror">
                    @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Turma</label>
                    <input type="text" name="class_group" value="{{ old('class_group') }}"
                           placeholder="Ex: Turma ELE-2026-A"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Justificativa</label>
                    <textarea name="reason" rows="3"
                              placeholder="Descreva para que os livros serão utilizados..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('reason') }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Enviar Requisição
                    </button>
                    <a href="{{ route('requisitions.index') }}"
                       class="px-6 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const select = document.getElementById('book-select');
        const info   = document.getElementById('stock-info');

        select.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (!opt.value) { info.classList.add('hidden'); return; }

            const stock = parseInt(opt.dataset.stock);
            info.classList.remove('hidden');

            if (stock === 0) {
                info.className = 'p-3 bg-red-50 rounded-lg text-sm text-red-700';
                info.textContent = 'Atenção: este livro está sem estoque no momento. A requisição será enviada mesmo assim.';
            } else {
                info.className = 'p-3 bg-indigo-50 rounded-lg text-sm text-indigo-700';
                info.textContent = 'Estoque disponível: ' + stock + ' unidade(s).';
            }
        });

        select.dispatchEvent(new Event('change'));
    </script>
</x-app-layout>
