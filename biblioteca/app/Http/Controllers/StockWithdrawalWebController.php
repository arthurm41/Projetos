<?php

namespace App\Http\Controllers;

use App\Models\StockWithdrawal;
use Illuminate\View\View;

class StockWithdrawalWebController extends Controller
{
    public function index(): View
    {
        $withdrawals = StockWithdrawal::with(['book.subject', 'user'])
            ->latest()
            ->paginate(15);

        return view('stock-withdrawals.index', compact('withdrawals'));
    }
}