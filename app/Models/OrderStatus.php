<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $table = 'order_statuses';

    const SEARCHABLE = [
        'value',
    ];

    protected $fillable = [
        'value',
    ];

    public function orders() {
        return $this->hasMany(Order::class, 'order_status_id');
    }
}