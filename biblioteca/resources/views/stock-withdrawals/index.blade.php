<x-app-layout>
    <x-slot name="title">Saídas de Estoque</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Saídas de Estoque (Baixas)</h2>
            <a href="{{ route('stock-withdrawals.create') }}"
               class="px-4 py-2 bg-orange-500 text-white text-sm font-medium rounded-lg hover:bg-orange-600 transition-colors">
                + Registrar Saída
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
                        <th class="px-6 py-3 text-center">Qtd. Saída</th>
                        <th class="px-6 py-3 text-center">Antes</th>
                        <th class="px-6 py-3 text-center">Depois</th>
                        <th class="px-6 py-3">Turma</th>
                        <th class="px-6 py-3">Motivo</th>
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
                        <td class="px-6 py-4 text-gray-500">{{ $w->book->subject->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-orange-500">-{{ $w->quantity }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500">{{ $w->stock_before }}</td>
                        <td class="px-6 py-4 text-center font-medium text-gray-800">{{ $w->stock_after }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $w->class_group ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-400 max-w-[180px] truncate">{{ $w->reason ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                            Nenhuma saída registrada.
                            <a href="{{ route('stock-withdrawals.create') }}" class="text-blue-600 hover:underline ml-1">Registrar agora</a>
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
