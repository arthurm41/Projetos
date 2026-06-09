<?php

namespace App\Http\Controllers;

use App\Models\StockWithdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StockWithdrawalWebController extends Controller
{
    public function index(): View
    {
        $query = StockWithdrawal::with(['book.subjects', 'user'])->latest('withdrawn_at');

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('book', fn($b) => $b->where('title', 'like', "%{$search}%"))
                  ->orWhere('class_group', 'like', "%{$search}%");
            });
        }

        if ($from = request('date_from')) {
            $query->whereDate('withdrawn_at', '>=', $from);
        }

        if ($to = request('date_to')) {
            $query->whereDate('withdrawn_at', '<=', $to);
        }

        $withdrawals = $query->paginate(15)->withQueryString();

        return view('stock-withdrawals.index', compact('withdrawals'));
    }

    public function destroy(StockWithdrawal $stockWithdrawal): RedirectResponse
    {
        abort_unless(Auth::user()->hasRole('almoxarife'), 403);

        $stockWithdrawal->delete();

        return back()->with('success', 'Registro de saída excluído do histórico.');
    }
}
