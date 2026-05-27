<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookRequisition extends Model
{
    protected $fillable = [
        'book_id', 'requested_by', 'quantity', 'class_group', 'reason',
        'status', 'approved_by', 'approved_at', 'delivered_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'approved_at'  => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isApproved(): bool  { return $this->status === 'approved'; }
    public function isDelivered(): bool { return $this->status === 'delivered'; }
    public function isCancelled(): bool { return $this->status === 'cancelled'; }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'   => 'Pendente',
            'approved'  => 'Aprovada',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelada',
            default     => $this->status,
        };
    }
}
