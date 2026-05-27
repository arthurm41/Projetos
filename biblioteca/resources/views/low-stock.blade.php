<x-app-layout>
    <x-slot name="title">Estoque Mínimo</x-slot>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Monitoramento de Estoque Mínimo</h2>
    </x-slot>

    @if($books->isEmpty())
        <div class="bg-green-50 border border-green-200 rounded-xl p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="text-green-800 font-semibold text-lg">Tudo em ordem!</p>
            <p class="text-green-600 text-sm mt-1">Todos os livros estão com estoque acima do mínimo.</p>
        </div>
    @else
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-yellow-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-yellow-800 text-sm">
                <strong>{{ $books->total() }} título(s)</strong> precisam de reposição.
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr class="text-left text-xs text-gray-500 uppercase">
                            <th class="px-6 py-3">Título</th>
                            <th class="px-6 py-3">ISBN</th>
                            <th class="px-6 py-3">Matéria</th>
                            <th class="px-6 py-3 text-center">Atual</th>
                            <th class="px-6 py-3 text-center">Mínimo</th>
                            <th class="px-6 py-3 text-center">Déficit</th>
                            <th class="px-6 py-3 text-right">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($books as $book)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $book->title }}</td>
                            <td class="px-6 py-4 text-gray-400 text-xs">{{ $book->isbn }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $book->subject->name }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-lg {{ $book->current_stock === 0 ? 'text-red-600' : 'text-yellow-600' }}">
                                    {{ $book->current_stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-500">{{ $book->minimum_stock }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                    -{{ $book->minimum_stock - $book->current_stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('stock-entries.create') }}?book_id={{ $book->id }}"
                                   class="px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 rounded-md hover:bg-green-100 transition-colors">
                                    + Abastecer
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($books->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $books->links() }}</div>
            @endif
        </div>
    @endif
</x-app-layout>
