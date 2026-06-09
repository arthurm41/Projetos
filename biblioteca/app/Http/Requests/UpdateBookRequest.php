<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject_ids'   => ['required', 'array', 'min:1'],
            'subject_ids.*' => ['integer', 'exists:subjects,id'],
            'title'         => ['sometimes', 'string', 'max:255'],
            'isbn'          => ['sometimes', 'string', 'max:20', Rule::unique('books', 'isbn')->ignore($this->route('book'))],
            'author'        => ['nullable', 'string', 'max:200'],
            'publisher'     => ['nullable', 'string', 'max:150'],
            'edition'       => ['nullable', 'string', 'max:20'],
            'minimum_stock' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'subject_ids.required' => 'Selecione ao menos uma matéria.',
            'subject_ids.min'      => 'Selecione ao menos uma matéria.',
            'subject_ids.*.exists' => 'Uma das matérias selecionadas é inválida.',
            'isbn.unique'          => 'Este ISBN já está cadastrado em outro livro.',
        ];
    }
}
