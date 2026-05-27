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
            'subject_id'    => ['sometimes', 'integer', 'exists:subjects,id'],
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
            'subject_id.exists' => 'A matéria informada não existe.',
            'isbn.unique'       => 'Este ISBN já está cadastrado em outro livro.',
        ];
    }
}
