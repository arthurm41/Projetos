<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubjectWebController extends Controller
{
    public function index(): View
    {
        $subjects = Subject::withCount('books')->orderBy('name')->paginate(15);

        return view('subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        return view('subjects.create');
    }

    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        Subject::create($request->validated());

        return redirect()->route('subjects.index')
            ->with('success', 'Matéria cadastrada com sucesso.');
    }

    public function edit(Subject $subject): View
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        return view('subjects.edit', compact('subject'));
    }

    public function update(StoreSubjectRequest $request, Subject $subject): RedirectResponse
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        $subject->update($request->validated());

        return redirect()->route('subjects.index')
            ->with('success', 'Matéria atualizada com sucesso.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        if ($subject->books()->exists()) {
            return redirect()->route('subjects.index')
                ->with('error', 'Não é possível excluir: existem livros vinculados a esta matéria.');
        }

        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Matéria excluída com sucesso.');
    }
}
