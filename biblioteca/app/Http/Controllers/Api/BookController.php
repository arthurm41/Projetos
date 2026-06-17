<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;

class BookController extends ApiController
{
    public function index(): JsonResponse
    {
        $books = Book::with('subjects')->orderBy('title')->get();

        return $this->success(BookResource::collection($books));
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = Book::create($request->validated());
        $book->load('subjects');

        return $this->success(new BookResource($book), 'Livro cadastrado com sucesso.', 201);
    }

    public function show(Book $book): JsonResponse
    {
        $book->load('subjects');

        return $this->success(new BookResource($book));
    }

    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $book->update($request->validated());
        $book->load('subjects');

        return $this->success(new BookResource($book), 'Livro atualizado com sucesso.');
    }

    public function destroy(Book $book): JsonResponse
    {
        if ($book->stockEntries()->exists() || $book->stockWithdrawals()->exists()) {
            return $this->error('Não é possível excluir: existem movimentações de estoque para este livro.', 422);
        }

        $book->delete();

        return $this->success(null, 'Livro excluído com sucesso.');
    }

    public function lowStock(): JsonResponse
    {
        $books = Book::with('subjects')
            ->whereColumn('current_stock', '<', 'minimum_stock')
            ->orderBy('current_stock')
            ->get();

        return $this->success(BookResource::collection($books), 'Livros com estoque abaixo do mínimo.');
    }
}
