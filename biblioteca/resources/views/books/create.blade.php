<x-app-layout>
    <x-slot name="title">Novo Livro</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('books.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-xl font-semibold text-gray-800">Cadastrar Livro</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('books.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-400 @enderror">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ISBN *</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}" required placeholder="978-00-000-0000-0"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('isbn') border-red-400 @enderror">
                    @error('isbn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Matéria(s) *
                        <span class="text-xs font-normal text-gray-400 ml-1">Selecione uma ou mais</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($subjects as $subject)
                        <label class="flex items-center gap-2.5 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-colors">
                            <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}"
                                   class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                   {{ in_array($subject->id, old('subject_ids', [])) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $subject->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('subject_ids') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
                        <input type="text" name="author" value="{{ old('author') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Editora</label>
                        <input type="text" name="publisher" value="{{ old('publisher') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Edição</label>
                        <input type="text" name="edition" value="{{ old('edition') }}" placeholder="ex: 3ª Edição"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estoque Mínimo</label>
                        <input type="number" name="minimum_stock" value="{{ old('minimum_stock', 10) }}" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Cadastrar Livro
                    </button>
                    <a href="{{ route('books.index') }}"
                       class="px-6 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
