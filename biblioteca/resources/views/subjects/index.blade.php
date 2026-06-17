<x-app-layout>
    {{-- Título da aba do navegador --}}
    <x-slot name="title">Matérias</x-slot>

    {{-- Cabeçalho com botão para cadastrar nova matéria --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Matérias</h2>
            {{-- Botão "+ Nova Matéria" — só aparece para o almoxarife (protegido na rota) --}}
            <a href="{{ route('subjects.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                + Nova Matéria
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        {{-- Tabela listando todas as matérias cadastradas --}}
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr class="text-left text-xs text-gray-500 uppercase">
                    <th class="px-6 py-3">Nome</th>         {{-- Nome da matéria --}}
                    <th class="px-6 py-3">Descrição</th>    {{-- Descrição opcional --}}
                    <th class="px-6 py-3 text-center">Livros</th>  {{-- Quantidade de livros vinculados --}}
                    <th class="px-6 py-3 text-right">Ações</th>    {{-- Botões editar/excluir --}}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subjects as $subject)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $subject->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $subject->description ?? '—' }}</td>
                    <td class="px-6 py-4 text-center">
                        {{-- Badge azul com o total de livros que usam esta matéria --}}
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                            {{ $subject->books_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            {{-- Botão "Editar" — leva para o formulário de edição da matéria --}}
                            <a href="{{ route('subjects.edit', $subject) }}"
                               class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-md hover:bg-blue-100 transition-colors">
                                Editar
                            </a>

                            {{-- Botão "Excluir" com modal de confirmação --}}
                            <div x-data="{ open: false }">
                                <button type="button" @click="open = true"
                                        class="px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                                    Excluir
                                </button>

                                {{-- Formulário oculto que é submetido quando o usuário confirma a exclusão --}}
                                <form x-ref="frm" method="POST" action="{{ route('subjects.destroy', $subject) }}">
                                    @csrf @method('DELETE')
                                </form>

                                {{-- Modal de confirmação de exclusão --}}
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
                                            <h3 class="text-base font-bold text-gray-900">Excluir matéria?</h3>
                                            <p class="text-sm text-gray-500 mt-1">Esta ação não pode ser desfeita.</p>
                                        </div>
                                        <div class="flex gap-3 mt-6">
                                            {{-- Botão "Não, voltar" — fecha o modal sem excluir --}}
                                            <button type="button" @click="open = false"
                                                    class="flex-1 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                                Não, voltar
                                            </button>
                                            {{-- Botão "Sim, excluir" — confirma e submete o formulário de exclusão --}}
                                            <button type="button" @click="$refs.frm.submit()"
                                                    class="flex-1 py-2.5 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                                                Sim, excluir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                {{-- Linha de estado vazio quando não há matérias cadastradas --}}
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                        Nenhuma matéria cadastrada.
                        <a href="{{ route('subjects.create') }}" class="text-blue-600 hover:underline ml-1">Cadastrar agora</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginação — aparece somente quando há mais de uma página --}}
        @if($subjects->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $subjects->links() }}</div>
        @endif
    </div>
</x-app-layout>
