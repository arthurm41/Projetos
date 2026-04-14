<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockWithdrawal extends Model
{
    protected $fillable = [
        'book_id', 'user_id', 'quantity', 'stock_before',
        'stock_after', 'class_group', 'reason', 'withdrawn_at',
    ];

    protected $casts = ['withdrawn_at' => 'datetime'];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}