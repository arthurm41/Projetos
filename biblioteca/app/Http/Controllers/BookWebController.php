<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\StockEntry;
use App\Models\StockWithdrawal;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookWebController extends Controller
{
    public function index(): View
    {
        $query = Book::with('subjects')->orderBy('title');

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($subjectId = request('subject_id')) {
            $query->whereHas('subjects', fn($q) => $q->where('subjects.id', $subjectId));
        }

        match (request('status')) {
            'zero' => $query->where('current_stock', 0),
            'low'  => $query->whereColumn('current_stock', '<', 'minimum_stock')->where('current_stock', '>', 0),
            'ok'   => $query->whereColumn('current_stock', '>=', 'minimum_stock'),
            default => null,
        };

        $books    = $query->paginate(15)->withQueryString();
        $subjects = Subject::orderBy('name')->get();

        return view('books.index', compact('books', 'subjects'));
    }

    public function create(): View
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        $subjects = Subject::orderBy('name')->get();

        return view('books.create', compact('subjects'));
    }

    public function store(StoreBookRequest $request): RedirectResponse
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        $data       = $request->validated();
        $subjectIds = $data['subject_ids'];
        unset($data['subject_ids']);

        $book = Book::create($data);
        $book->subjects()->sync($subjectIds);

        return redirect()->route('books.index')
            ->with('success', 'Livro cadastrado com sucesso.');
    }

    public function edit(Book $book): View
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        $subjects = Subject::orderBy('name')->get();

        return view('books.edit', compact('book', 'subjects'));
    }

    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        $data       = $request->validated();
        $subjectIds = $data['subject_ids'];
        unset($data['subject_ids']);

        $book->update($data);
        $book->subjects()->sync($subjectIds);

        return redirect()->route('books.index')
            ->with('success', 'Livro atualizado com sucesso.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        DB::transaction(function () use ($book) {
            // Delete movements without triggering observers (stock is irrelevant when the book is gone)
            StockEntry::withoutEvents(fn () => $book->stockEntries()->delete());
            StockWithdrawal::withoutEvents(fn () => $book->stockWithdrawals()->delete());
            $book->delete(); // cascades: book_requisitions, book_subject
        });

        return redirect()->route('books.index')
            ->with('success', 'Livro excluído com sucesso.');
    }
}
