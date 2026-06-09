<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title', 'isbn', 'author',
        'publisher', 'edition', 'current_stock', 'minimum_stock',
    ];

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }

    public function stockEntries(): HasMany
    {
        return $this->hasMany(StockEntry::class);
    }

    public function stockWithdrawals(): HasMany
    {
        return $this->hasMany(StockWithdrawal::class);
    }

    public function requisitions(): HasMany
    {
        return $this->hasMany(BookRequisition::class);
    }

    public function isLowStock(): bool
    {
        return $this->current_stock < $this->minimum_stock;
    }
}
