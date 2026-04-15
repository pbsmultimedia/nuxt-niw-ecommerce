<?php

namespace Pbs\Shop\Models;

use Winter\Storm\Database\Model;

class Cart extends Model
{
    public $table = 'pbs_shop_carts';

    protected $guarded = [];

    public $hasMany = [
        'items' => [\Pbs\Shop\Models\CartItem::class, 'key' => 'cart_id', 'delete' => true]
    ];
    
    public $belongsTo = [
        'user' => [\Winter\User\Models\User::class]
    ];
}
