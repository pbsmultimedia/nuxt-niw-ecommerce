<?php

namespace Pbs\Shop\Models;

use Winter\Storm\Database\Model;

class CartItem extends Model
{
    public $table = 'pbs_shop_cart_items';

    protected $guarded = [];

    public $belongsTo = [
        'cart'    => [\Pbs\Shop\Models\Cart::class],
        'product' => [\Pbs\Shop\Models\Product::class]
    ];
}
