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
        'is_filterable',
        'icon'
    ];

    protected $appends = [
        'num_orders'
    ];

    public function orders() {
        return $this->hasMany(Order::class, 'order_status_id');
    }

    public function getNumOrdersAttribute()
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        if ($user->is_admin) {
            $query = Order::where('order_status_id', $this->id);
        } else {
            $userStoresIds = $user->stores()->pluck('stores.id')->toArray();
            $query = Order::whereIn('store_id', $userStoresIds)
                ->where('order_status_id', $this->id);
        }
        return $query->count();
    }
}
