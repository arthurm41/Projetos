<x-app-layout>
    <x-slot name="title">Entradas de Estoque</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Entradas de Estoque (Abastecimento)</h2>
            <a href="{{ route('stock-entries.create') }}"
               class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                + Registrar Entrada
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-xs text-gray-500 uppercase">
                        <th class="px-6 py-3">Data</th>
                        <th class="px-6 py-3">Livro</th>
                        <th class="px-6 py-3">Matéria</th>
                        <th class="px-6 py-3 text-center">Qtd. Entrada</th>
                        <th class="px-6 py-3 text-center">Antes</th>
                        <th class="px-6 py-3 text-center">Depois</th>
                        <th class="px-6 py-3">Registrado por</th>
                        <th class="px-6 py-3">Observações</th>
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
                        <td class="px-6 py-4 text-gray-500">{{ $entry->book->subject->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-green-600">+{{ $entry->quantity }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500">{{ $entry->stock_before }}</td>
                        <td class="px-6 py-4 text-center font-medium text-gray-800">{{ $entry->stock_after }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $entry->user->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-400 max-w-[180px] truncate">{{ $entry->notes ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                            Nenhuma entrada registrada.
                            <a href="{{ route('stock-entries.create') }}" class="text-blue-600 hover:underline ml-1">Registrar agora</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($entries->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $entries->links() }}</div>
        @endif
    </div>
</x-app-layout>
