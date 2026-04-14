<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = 'stock_movements';
    protected $primaryKey = 'movement_id';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;
}