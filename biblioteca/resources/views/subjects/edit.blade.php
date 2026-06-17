<x-app-layout>
    {{-- Título da aba do navegador --}}
    <x-slot name="title">Editar Matéria</x-slot>

    {{-- Cabeçalho com seta de voltar para a listagem de matérias --}}
    <x-slot name="header">
        <div class="flex items-center gap-3">
            {{-- Seta de voltar para a listagem de matérias --}}
            <a href="{{ route('subjects.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-xl font-semibold text-gray-800">Editar Matéria</h2>
        </div>
    </x-slot>

    <div class="max-w-lg">
        <div class="bg-white rounded-xl shadow-sm p-6">
            {{-- Formulário de edição da matéria — pré-preenchido com os dados atuais do banco --}}
            <form method="POST" action="{{ route('subjects.update', $subject) }}" class="space-y-5">
                @csrf @method('PUT')

                {{-- Campo: nome da matéria (pré-preenchido com valor atual) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                    <input type="text" name="name" value="{{ old('name', $subject->name) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Campo: descrição da matéria (pré-preenchida com valor atual) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $subject->description) }}</textarea>
                </div>

                <div class="flex gap-3">
                    {{-- Botão "Salvar" — confirma as alterações na matéria --}}
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">Salvar</button>
                    {{-- Botão "Cancelar" — descarta as alterações e volta para a listagem --}}
                    <a href="{{ route('subjects.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
