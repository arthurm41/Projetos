<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookRequisition;
use App\Models\StockEntry;
use App\Models\StockWithdrawal;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBooks    = Book::count();
        $totalSubjects = Subject::count();
        $lowStockCount = Book::whereColumn('current_stock', '<', 'minimum_stock')->count();
        $zeroStockCount = Book::where('current_stock', 0)->count();

        $recentEntries = StockEntry::with('book')
            ->latest()
            ->limit(5)
            ->get();

        $recentWithdrawals = StockWithdrawal::with('book')
            ->latest()
            ->limit(5)
            ->get();

        $lowStockBooks = Book::with('subjects')
            ->whereColumn('current_stock', '<', 'minimum_stock')
            ->orderBy('current_stock')
            ->limit(5)
            ->get();

        $user = Auth::user();

        if ($user->hasRole('almoxarife')) {
            $pendingRequisitions = BookRequisition::with(['book', 'requester'])
                ->where('status', 'pending')
                ->latest()
                ->limit(5)
                ->get();
            $pendingCount = BookRequisition::where('status', 'pending')->count();

            $dispatchedRequisitions = BookRequisition::with(['book', 'requester'])
                ->where('status', 'approved')
                ->latest()
                ->limit(5)
                ->get();
            $dispatchedCount = BookRequisition::where('status', 'approved')->count();
        } else {
            $pendingRequisitions = BookRequisition::with(['book'])
                ->where('requested_by', $user->id)
                ->whereIn('status', ['pending', 'approved', 'dispatched'])
                ->latest()
                ->limit(5)
                ->get();
            $pendingCount = BookRequisition::where('requested_by', $user->id)
                ->whereIn('status', ['pending', 'approved', 'dispatched'])
                ->count();
            $dispatchedRequisitions = collect();
            $dispatchedCount = 0;
        }

        return view('dashboard', compact(
            'totalBooks',
            'totalSubjects',
            'lowStockCount',
            'zeroStockCount',
            'recentEntries',
            'recentWithdrawals',
            'lowStockBooks',
            'pendingRequisitions',
            'pendingCount',
            'dispatchedRequisitions',
            'dispatchedCount'
        ));
    }
}
