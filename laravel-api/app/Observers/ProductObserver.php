<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        Cache::forget('products');
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        Cache::forget('products');
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        Cache::forget('products');
    }
}
