<x-app-layout>
    <x-slot name="title">Requisição #{{ $requisition->id }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('requisitions.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-xl font-semibold text-gray-800">Requisição #{{ $requisition->id }}</h2>
            @if($requisition->isPending())
                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Pendente</span>
            @elseif($requisition->isApproved())
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Aprovada</span>
            @elseif($requisition->isDelivered())
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Entregue</span>
            @else
                <span class="px-2 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded-full">Cancelada</span>
            @endif
        </div>
    </x-slot>

    <div class="max-w-2xl space-y-6">

        {{-- Detalhes principais --}}
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Detalhes do Pedido</h3>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">Livro</p>
                    <p class="font-medium text-gray-900 mt-0.5">{{ $requisition->book->title }}</p>
                    <p class="text-gray-400 text-xs">{{ $requisition->book->isbn }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Matéria</p>
                    <p class="text-gray-800 mt-0.5">{{ $requisition->book->subject->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Quantidade Solicitada</p>
                    <p class="font-bold text-2xl text-indigo-600 mt-0.5">{{ $requisition->quantity }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Turma</p>
                    <p class="text-gray-800 mt-0.5">{{ $requisition->class_group ?? '—' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-400 text-xs">Justificativa</p>
                    <p class="text-gray-700 mt-0.5">{{ $requisition->reason ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Linha do tempo --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Histórico</h3>
            <ol class="space-y-4 text-sm">
                <li class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                    <div>
                        <p class="font-medium text-gray-800">Requisição enviada por {{ $requisition->requester->name }}</p>
                        <p class="text-xs text-gray-400">{{ $requisition->created_at->format('d/m/Y \à\s H:i') }}</p>
                    </div>
                </li>

                @if($requisition->approved_at)
                <li class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-green-500 mt-1.5 shrink-0"></div>
                    <div>
                        <p class="font-medium text-gray-800">Aprovada por {{ $requisition->approver?->name ?? '—' }}</p>
                        <p class="text-xs text-gray-400">{{ $requisition->approved_at->format('d/m/Y \à\s H:i') }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Livros separados do estoque e prontos para retirada.</p>
                    </div>
                </li>
                @endif

                @if($requisition->delivered_at)
                <li class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 shrink-0"></div>
                    <div>
                        <p class="font-medium text-gray-800">Recebimento confirmado</p>
                        <p class="text-xs text-gray-400">{{ $requisition->delivered_at->format('d/m/Y \à\s H:i') }}</p>
                    </div>
                </li>
                @endif

                @if($requisition->isCancelled())
                <li class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-red-400 mt-1.5 shrink-0"></div>
                    <div>
                        <p class="font-medium text-gray-600">Requisição cancelada</p>
                    </div>
                </li>
                @endif
            </ol>
        </div>

        {{-- Ações --}}
        <div class="flex gap-3">
            {{-- Almoxarife aprova --}}
            @if(Auth::user()->hasRole('almoxarife') && $requisition->isPending())
            <form method="POST" action="{{ route('requisitions.approve', $requisition) }}">
                @csrf
                <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    Aprovar e Separar Livros
                </button>
            </form>
            @endif

            {{-- Professor confirma recebimento --}}
            @if(!Auth::user()->hasRole('almoxarife') && $requisition->isApproved() && $requisition->requested_by === Auth::id())
            <form method="POST" action="{{ route('requisitions.deliver', $requisition) }}">
                @csrf
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    Confirmar Recebimento
                </button>
            </form>
            @endif

            {{-- Cancelar --}}
            @if(!$requisition->isDelivered() && !$requisition->isCancelled())
            <div x-data="{ open: false }">
                <button type="button" @click="open = true"
                        class="px-6 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                    Cancelar Requisição
                </button>
                <form x-ref="frm" method="POST" action="{{ route('requisitions.cancel', $requisition) }}">
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
    </div>
</x-app-layout>
