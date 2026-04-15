<?php

namespace Pbs\Shop\Models;

use Winter\Storm\Database\Model;

class OrderItem extends Model
{
    public $table = "pbs_shop_order_items";

    protected $guarded = [];

    public $belongsTo = [
        "order"   => [\Pbs\Shop\Models\Order::class],
        "product" => [\Pbs\Shop\Models\Product::class]
    ];
}
