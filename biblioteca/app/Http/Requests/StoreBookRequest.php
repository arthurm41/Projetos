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
            'subject_id'    => ['required', 'integer', 'exists:subjects,id'],
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
            'subject_id.exists' => 'A matéria informada não existe.',
            'isbn.unique'       => 'Este ISBN já está cadastrado.',
        ];
    }
}
