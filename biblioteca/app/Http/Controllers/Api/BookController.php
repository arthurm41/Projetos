<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends ApiController
{
    public function index(): JsonResponse
    {
        $books = Book::with('subject')->get();
        return $this->success($books);
    }

    public function lowStock(): JsonResponse
    {
        $books = Book::whereRaw('current_stock < minimum_stock')
            ->with('subject')
            ->get();
        
        return $this->success($books, 'Livros com estoque baixo');
    }

    public function show(Book $book): JsonResponse
    {
        return $this->success($book->load('subject'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255|unique:books',
            'isbn' => 'required|string|max:20|unique:books',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:150',
            'edition' => 'nullable|string|max:20',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        $book = Book::create($validated);
        return $this->success($book, 'Livro criado com sucesso', 201);
    }

    public function update(Request $request, Book $book): JsonResponse
    {
        $validated = $request->validate([
            'subject_id' => 'exists:subjects,id',
            'title' => 'string|max:255|unique:books,title,' . $book->id,
            'isbn' => 'string|max:20|unique:books,isbn,' . $book->id,
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:150',
            'edition' => 'nullable|string|max:20',
            'minimum_stock' => 'integer|min:0',
        ]);

        $book->update($validated);
        return $this->success($book, 'Livro atualizado com sucesso');
    }
}