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

    {{-- Filtros --}}
    <form method="GET" action="{{ route('requisitions.index') }}" class="mb-4 bg-white rounded-xl shadow-sm p-4">
        <div class="flex flex-wrap gap-3 items-end">
            @if(Auth::user()->hasRole('almoxarife'))
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Livro ou professor..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            @endif
            <div class="min-w-[150px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    <option value="pending"    {{ request('status') === 'pending'    ? 'selected' : '' }}>Pendente</option>
                    <option value="approved"   {{ request('status') === 'approved'   ? 'selected' : '' }}>Aprovada</option>
                    <option value="dispatched" {{ request('status') === 'dispatched' ? 'selected' : '' }}>Em retirada</option>
                    <option value="delivered"  {{ request('status') === 'delivered'  ? 'selected' : '' }}>Entregue</option>
                    <option value="cancelled"  {{ request('status') === 'cancelled'  ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div class="min-w-[130px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">De</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="min-w-[130px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Até</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Filtrar
                </button>
                @if(request()->hasAny(['search','status','date_from','date_to']))
                <a href="{{ route('requisitions.index') }}"
                   class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Limpar
                </a>
                @endif
            </div>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if(request()->hasAny(['search','status','date_from','date_to']))
        <div class="px-6 py-3 bg-indigo-50 border-b border-indigo-100 text-xs text-indigo-700">
            {{ $requisitions->total() }} resultado(s) encontrado(s)
        </div>
        @endif
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
                        <td class="px-6 py-4 text-gray-500">{{ $req->book->subjects->pluck('name')->join(', ') }}</td>
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
                            @elseif($req->isDispatched())
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">Em retirada</span>
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
                            <div class="flex items-center justify-end gap-2 flex-wrap">
                                <a href="{{ route('requisitions.show', $req) }}"
                                   class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                                    Ver
                                </a>

                                {{-- Almoxarife: Aprovar (pendente) --}}
                                @if(Auth::user()->hasRole('almoxarife') && $req->isPending())
                                    <div x-data="{ open: false }">
                                        <button type="button" @click="open = true"
                                                class="px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 rounded-md hover:bg-green-100 transition-colors">
                                            Aprovar
                                        </button>
                                        <div x-show="open" x-cloak
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                                             @click.self="open = false">
                                            <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md" @click.stop>
                                                <div class="flex items-center gap-3 mb-5">
                                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center shrink-0">
                                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-base font-bold text-gray-900">Aprovar Requisição #{{ $req->id }}</h3>
                                                        <p class="text-xs text-gray-500 mt-0.5">{{ $req->book->title }} · {{ $req->quantity }} unidade(s)</p>
                                                    </div>
                                                </div>
                                                <form method="POST" action="{{ route('requisitions.approve', $req) }}">
                                                    @csrf
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Data inicial da previsão de entrega</label>
                                                            <input type="date" name="estimated_delivery_from" required
                                                                   min="{{ date('Y-m-d') }}"
                                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Data final da previsão de entrega</label>
                                                            <input type="date" name="estimated_delivery_to" required
                                                                   min="{{ date('Y-m-d') }}"
                                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-3 mt-6">
                                                        <button type="button" @click="open = false"
                                                                class="flex-1 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                                            Cancelar
                                                        </button>
                                                        <button type="submit"
                                                                class="flex-1 py-2.5 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                                                            Aprovar
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Almoxarife: Confirmar entrega (aprovado) --}}
                                @if(Auth::user()->hasRole('almoxarife') && $req->isApproved())
                                    <div x-data="{ open: false }">
                                        <button type="button" @click="open = true"
                                                class="px-3 py-1.5 text-xs font-medium text-purple-700 bg-purple-50 rounded-md hover:bg-purple-100 transition-colors">
                                            Confirmar Entrega
                                        </button>
                                        <div x-show="open" x-cloak
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                                             @click.self="open = false">
                                            <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md" @click.stop>
                                                <div class="flex items-center gap-3 mb-5">
                                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center shrink-0">
                                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8 8-4-4"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-base font-bold text-gray-900">Confirmar Entrega #{{ $req->id }}</h3>
                                                        <p class="text-xs text-gray-500 mt-0.5">{{ $req->book->title }} · para {{ $req->requester->name }}</p>
                                                    </div>
                                                </div>
                                                <form method="POST" action="{{ route('requisitions.dispatch', $req) }}">
                                                    @csrf
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Data e hora da entrega</label>
                                                            <input type="datetime-local" name="dispatched_at" required
                                                                   value="{{ now()->format('Y-m-d\TH:i') }}"
                                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Quem realizou a retirada</label>
                                                            <input type="text" name="delivered_by" required
                                                                   placeholder="Nome completo de quem retirou os livros"
                                                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-3 mt-6">
                                                        <button type="button" @click="open = false"
                                                                class="flex-1 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                                            Cancelar
                                                        </button>
                                                        <button type="submit"
                                                                class="flex-1 py-2.5 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors">
                                                            Confirmar Entrega
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Professor: Confirmar recebimento (em retirada) --}}
                                @if(!Auth::user()->hasRole('almoxarife') && $req->isDispatched() && $req->requested_by === Auth::id())
                                    <form method="POST" action="{{ route('requisitions.deliver', $req) }}">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-md hover:bg-indigo-100 transition-colors">
                                            Confirmar Recebimento
                                        </button>
                                    </form>
                                @endif

                                {{-- Cancelar --}}
                                @if($req->isActive() && !$req->isDispatched())
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

                                {{-- Almoxarife: Excluir histórico (entregue ou cancelada) --}}
                                @if(Auth::user()->hasRole('almoxarife') && ($req->isDelivered() || $req->isCancelled()))
                                    <div x-data="{ open: false }">
                                        <button type="button" @click="open = true"
                                                class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                                            Excluir
                                        </button>
                                        <form x-ref="del" method="POST" action="{{ route('requisitions.destroy', $req) }}">
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
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </div>
                                                    <h3 class="text-base font-bold text-gray-900">Excluir do histórico?</h3>
                                                    <p class="text-sm text-gray-500 mt-1">Requisição #{{ $req->id }} será removida permanentemente.</p>
                                                </div>
                                                <div class="flex gap-3 mt-6">
                                                    <button type="button" @click="open = false"
                                                            class="flex-1 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                                        Não, voltar
                                                    </button>
                                                    <button type="button" @click="$refs.del.submit()"
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
                        <td colspan="{{ Auth::user()->hasRole('almoxarife') ? 9 : 8 }}" class="px-6 py-12 text-center text-gray-400">
                            @if(request()->hasAny(['search','status','date_from','date_to']))
                                Nenhuma requisição encontrada para os filtros aplicados.
                            @else
                                Nenhuma requisição encontrada.
                                @if(!Auth::user()->hasRole('almoxarife'))
                                <a href="{{ route('requisitions.create') }}" class="text-indigo-600 hover:underline ml-1">Criar agora</a>
                                @endif
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
