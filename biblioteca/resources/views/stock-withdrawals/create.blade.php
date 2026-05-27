<x-app-layout>
    <x-slot name="title">Registrar Saída</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('stock-withdrawals.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-xl font-semibold text-gray-800">Registrar Saída de Estoque</h2>
        </div>
    </x-slot>

    <div class="max-w-lg">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('stock-withdrawals.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Livro *</label>
                    <select name="book_id" required id="book-select"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 @error('book_id') border-red-400 @enderror">
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

                <div id="stock-info" class="hidden p-3 bg-orange-50 rounded-lg text-sm text-orange-700"></div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade *</label>
                    <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 @error('quantity') border-red-400 @enderror">
                    @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Turma</label>
                    <input type="text" name="class_group" value="{{ old('class_group') }}"
                           placeholder="Ex: Turma ELE-2025-A"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motivo / Observação</label>
                    <textarea name="reason" rows="3"
                              placeholder="Ex: Distribuição para alunos do módulo de eletricidade..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">{{ old('reason') }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="px-6 py-2 bg-orange-500 text-white text-sm font-medium rounded-lg hover:bg-orange-600 transition-colors">
                        Registrar Saída
                    </button>
                    <a href="{{ route('stock-withdrawals.index') }}"
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
                const stock = parseInt(opt.dataset.stock);
                info.classList.remove('hidden');
                info.textContent = 'Estoque disponível: ' + stock + ' unidade(s)';
                info.className = stock === 0
                    ? 'p-3 bg-red-50 rounded-lg text-sm text-red-700'
                    : 'p-3 bg-orange-50 rounded-lg text-sm text-orange-700';
            } else {
                info.classList.add('hidden');
            }
        });
        document.getElementById('book-select').dispatchEvent(new Event('change'));
    </script>
</x-app-layout>
