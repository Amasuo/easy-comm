<?php

namespace App\Observers;

use App\Models\Order;
use Str;

class OrderObserver
{
    /**
     * Handle the Order "creating" event.
     */
    public function creating(Order $order): void
    {
        $validReference = false;
        while (!$validReference) {
            $reference = strtoupper(Str::random(4)) . rand(101, 999);
            $existingOrder = Order::where('reference', $reference)->first();
            if (!$existingOrder) {
                $validReference = true;
            }
        }
        $order->reference = $reference;
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
