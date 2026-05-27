<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockWithdrawalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id'      => ['required', 'integer', 'exists:books,id'],
            'quantity'     => ['required', 'integer', 'min:1'],
            'class_group'  => ['nullable', 'string', 'max:100'],
            'reason'       => ['nullable', 'string', 'max:500'],
            'withdrawn_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.exists' => 'O livro informado não existe.',
            'quantity.min'   => 'A quantidade deve ser pelo menos 1.',
        ];
    }
}
