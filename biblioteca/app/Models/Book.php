<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'subject_id', 'title', 'isbn', 'author',
        'publisher', 'edition', 'current_stock', 'minimum_stock',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function stockEntries(): HasMany
    {
        return $this->hasMany(StockEntry::class);
    }

    public function stockWithdrawals(): HasMany
    {
        return $this->hasMany(StockWithdrawal::class);
    }

    public function isLowStock(): bool
    {
        return $this->current_stock < $this->minimum_stock;
    }
}