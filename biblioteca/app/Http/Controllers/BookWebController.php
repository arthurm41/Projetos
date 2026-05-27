<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookWebController extends Controller
{
    public function index(): View
    {
        $books = Book::with('subject')->orderBy('title')->paginate(15);

        return view('books.index', compact('books'));
    }

    public function create(): View
    {
        $subjects = Subject::orderBy('name')->get();

        return view('books.create', compact('subjects'));
    }

    public function store(StoreBookRequest $request): RedirectResponse
    {
        Book::create($request->validated());

        return redirect()->route('books.index')
            ->with('success', 'Livro cadastrado com sucesso.');
    }

    public function edit(Book $book): View
    {
        $subjects = Subject::orderBy('name')->get();

        return view('books.edit', compact('book', 'subjects'));
    }

    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $book->update($request->validated());

        return redirect()->route('books.index')
            ->with('success', 'Livro atualizado com sucesso.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->stockEntries()->exists() || $book->stockWithdrawals()->exists()) {
            return redirect()->route('books.index')
                ->with('error', 'Não é possível excluir: existem movimentações para este livro.');
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Livro excluído com sucesso.');
    }
}
