<x-app-layout>
    <x-slot name="title">Requisições de Livros</x-slot>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ Auth::user()->hasRole('almoxarife') ? 'Requisições Recebidas' : 'Minhas Requisições' }}
            </h2>
            @if(!Auth::user()->hasRole('almoxarife'))
            <a href="{{ route('requisitions.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                + Nova Requisição
            </a>
            @endif
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-xs text-gray-500 uppercase">
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Livro</th>
                        <th class="px-6 py-3">Matéria</th>
                        @if(Auth::user()->hasRole('almoxarife'))
                        <th class="px-6 py-3">Professor</th>
                        @endif
                        <th class="px-6 py-3 text-center">Qtd.</th>
                        <th class="px-6 py-3">Turma</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3">Data</th>
                        <th class="px-6 py-3 text-right">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requisitions as $req)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-400 text-xs">#{{ $req->id }}</td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $req->book->title }}</p>
                            <p class="text-xs text-gray-400">{{ $req->book->isbn }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $req->book->subject->name }}</td>
                        @if(Auth::user()->hasRole('almoxarife'))
                        <td class="px-6 py-4 text-gray-600">{{ $req->requester->name }}</td>
                        @endif
                        <td class="px-6 py-4 text-center font-semibold text-gray-800">{{ $req->quantity }}</td>
                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $req->class_group ?? '—' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($req->isPending())
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Pendente</span>
                            @elseif($req->isApproved())
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Aprovada</span>
                            @elseif($req->isDelivered())
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Entregue</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full">Cancelada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs whitespace-nowrap">
                            {{ $req->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('requisitions.show', $req) }}"
                                   class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                                    Ver
                                </a>
                                @if(Auth::user()->hasRole('almoxarife') && $req->isPending())
                                    <form method="POST" action="{{ route('requisitions.approve', $req) }}">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 rounded-md hover:bg-green-100 transition-colors">
                                            Aprovar
                                        </button>
                                    </form>
                                @endif
                                @if(!Auth::user()->hasRole('almoxarife') && $req->isApproved())
                                    <form method="POST" action="{{ route('requisitions.deliver', $req) }}">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-md hover:bg-blue-100 transition-colors">
                                            Confirmar Recebimento
                                        </button>
                                    </form>
                                @endif
                                @if(!$req->isDelivered() && !$req->isCancelled())
                                    <div x-data="{ open: false }">
                                        <button type="button" @click="open = true"
                                                class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                                            Cancelar
                                        </button>
                                        <form x-ref="frm" method="POST" action="{{ route('requisitions.cancel', $req) }}">
                                            @csrf
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
                                                    <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
                                                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                                        </svg>
                                                    </div>
                                                    <h3 class="text-base font-bold text-gray-900">Cancelar requisição?</h3>
                                                    <p class="text-sm text-gray-500 mt-1">Esta ação não pode ser desfeita.</p>
                                                </div>
                                                <div class="flex gap-3 mt-6">
                                                    <button type="button" @click="open = false"
                                                            class="flex-1 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                                        Não, voltar
                                                    </button>
                                                    <button type="button" @click="$refs.frm.submit()"
                                                            class="flex-1 py-2.5 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                                                        Sim, cancelar
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
                        <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                            Nenhuma requisição encontrada.
                            @if(!Auth::user()->hasRole('almoxarife'))
                            <a href="{{ route('requisitions.create') }}" class="text-indigo-600 hover:underline ml-1">Criar agora</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requisitions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $requisitions->links() }}</div>
        @endif
    </div>
</x-app-layout>
