<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'title'         => ['required', 'string', 'max:255'],
            'isbn'          => ['required', 'string', 'max:20', 'unique:books,isbn'],
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
            'isbn.unique'          => 'Este ISBN já está cadastrado.',
        ];
    }
}
