<x-app-layout>
    <x-slot name="title">Registrar Entrada</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('stock-entries.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-xl font-semibold text-gray-800">Registrar Entrada de Estoque</h2>
        </div>
    </x-slot>

    <div class="max-w-lg">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('stock-entries.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Livro *</label>
                    <select name="book_id" required id="book-select"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('book_id') border-red-400 @enderror">
                        <option value="">Selecione o livro...</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}"
                                    data-stock="{{ $book->current_stock }}"
                                    {{ old('book_id', request('book_id')) == $book->id ? 'selected' : '' }}>
                                {{ $book->title }} ({{ $book->subject->name }}) — Estoque: {{ $book->current_stock }}
                            </option>
                        @endforeach
                    </select>
                    @error('book_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div id="stock-info" class="hidden p-3 bg-blue-50 rounded-lg text-sm text-blue-700"></div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade *</label>
                    <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('quantity') border-red-400 @enderror">
                    @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                    <textarea name="notes" rows="3" placeholder="Ex: Remessa recebida da editora..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('notes') }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                        Registrar Entrada
                    </button>
                    <a href="{{ route('stock-entries.index') }}"
                       class="px-6 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('book-select').addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const info = document.getElementById('stock-info');
            if (opt.value) {
                info.classList.remove('hidden');
                info.textContent = 'Estoque atual: ' + opt.dataset.stock + ' unidade(s)';
            } else {
                info.classList.add('hidden');
            }
        });
        document.getElementById('book-select').dispatchEvent(new Event('change'));
    </script>
</x-app-layout>
