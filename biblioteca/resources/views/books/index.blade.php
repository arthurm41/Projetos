<x-app-layout>
    <x-slot name="title">Livros</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Catálogo de Livros</h2>
            @if(Auth::user()->hasRole('almoxarife'))
            <a href="{{ route('books.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                + Novo Livro
            </a>
            @endif
        </div>
    </x-slot>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('books.index') }}" class="mb-4 bg-white rounded-xl shadow-sm p-4">
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Título, ISBN ou autor..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
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
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Filtrar
                </button>
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
        @if(request()->hasAny(['search','subject_id','status']))
        <div class="px-6 py-3 bg-blue-50 border-b border-blue-100 text-xs text-blue-700">
            {{ $books->total() }} resultado(s) encontrado(s)
        </div>
        @endif
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-xs text-gray-500 uppercase">
                        <th class="px-6 py-3">Título / ISBN</th>
                        <th class="px-6 py-3">Matéria</th>
                        <th class="px-6 py-3">Autor</th>
                        <th class="px-6 py-3 text-center">Estoque</th>
                        <th class="px-6 py-3 text-center">Mínimo</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-right">Ações</th>
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
                            @foreach($book->subjects as $s)
                                <span class="inline-block px-1.5 py-0.5 bg-gray-100 text-gray-600 text-xs rounded mr-1">{{ $s->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $book->author ?? '—' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-lg {{ $book->current_stock === 0 ? 'text-red-600' : ($book->isLowStock() ? 'text-yellow-600' : 'text-gray-800') }}">
                                {{ $book->current_stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-gray-500">{{ $book->minimum_stock }}</td>
                        <td class="px-6 py-4 text-center">
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
                                @if(Auth::user()->hasRole('almoxarife'))
                                <a href="{{ route('books.edit', $book) }}"
                                   class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-md hover:bg-blue-100 transition-colors">
                                    Editar
                                </a>
                                <div x-data="{ open: false }">
                                    <button type="button" @click="open = true"
                                            class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                                        Excluir
                                    </button>
                                    <form x-ref="frm" method="POST" action="{{ route('books.destroy', $book) }}">
                                        @csrf @method('DELETE')
                                    </form>
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
                                                <button type="button" @click="open = false"
                                                        class="flex-1 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                                    Não, voltar
                                                </button>
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

        @if($books->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $books->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
