<x-app-layout>
    {{-- Título da aba do navegador --}}
    <x-slot name="title">Editar Entrada</x-slot>

    {{-- Cabeçalho com seta de voltar para o histórico de entradas --}}
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('stock-entries.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-xl font-semibold text-gray-800">Editar Entrada de Estoque</h2>
        </div>
    </x-slot>

    <div class="max-w-lg">
        <div class="bg-white rounded-xl shadow-sm p-6">

            {{-- Card informativo com os dados imutáveis da entrada --}}
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-xs text-gray-500 uppercase font-medium mb-2">Registro #{{ $stockEntry->id }}</p>
                <p class="text-sm font-semibold text-gray-800">{{ $stockEntry->book->title }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $stockEntry->book->subjects->pluck('name')->join(', ') ?: '—' }}</p>
                <p class="text-xs text-gray-400 mt-1">Estoque antes da entrada: <strong class="text-gray-600">{{ $stockEntry->stock_before }}</strong></p>
            </div>

            {{-- Formulário de edição — apenas quantidade, data e observações podem ser alterados --}}
            <form method="POST" action="{{ route('stock-entries.update', $stockEntry) }}" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Campo: nova quantidade (corrige o valor digitado errado) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade *</label>
                    <input type="number" name="quantity" value="{{ old('quantity', $stockEntry->quantity) }}" min="1" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('quantity') border-red-400 @enderror">
                    @error('quantity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    {{-- Aviso: o estoque atual será recalculado automaticamente --}}
                    <p class="text-xs text-gray-400 mt-1">
                        Novo estoque após a entrada: <strong class="text-gray-600">{{ $stockEntry->stock_before }} + quantidade informada</strong>
                    </p>
                </div>

                {{-- Campo: data da entrada --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data da Entrada</label>
                    <input type="datetime-local" name="received_at"
                           value="{{ old('received_at', $stockEntry->received_at->format('Y-m-d\TH:i')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 @error('received_at') border-red-400 @enderror">
                    @error('received_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo: observações --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                    <textarea name="notes" rows="3" placeholder="Ex: Remessa recebida da editora..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('notes', $stockEntry->notes) }}</textarea>
                </div>

                <div class="flex gap-3">
                    {{-- Botão "Salvar Alterações" — atualiza a entrada e recalcula o estoque --}}
                    <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                        Salvar Alterações
                    </button>
                    {{-- Botão "Cancelar" — descarta e volta para o histórico --}}
                    <a href="{{ route('stock-entries.index') }}"
                       class="px-6 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
