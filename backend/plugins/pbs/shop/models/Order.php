<?php

namespace Pbs\Shop\Models;

use Winter\Storm\Database\Model;

class Order extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = "pbs_shop_orders";

    protected $guarded = [];

    public $rules = [
        "first_name" => "required",
        "last_name" => "required",
        "email" => "required|email",
        "address" => "required",
    ];

    public $hasMany = [
        "items" => [\Pbs\Shop\Models\OrderItem::class, "key" => "order_id"]
    ];
}
