<?php namespace Pbs\Shop\Models;

use Winter\Storm\Database\Model;
use \Winter\Storm\Database\Traits\Validation;

class DigitalProduct extends Model {
    
    public $table = 'pbs_shop_digital_products';
    protected $guarded = [];
    /*
    public $rules = [
        'file_url' => 'required|url',
    ];
    */

    public $morphOne = [
        'product' => [\Pbs\Shop\Models\Product::class, 'name' => 'productable']
    ];

    /**
     * @return \Winter\Storm\Database\Relations\MorphOne
     */
    public function product()
    {
        return $this->morphOne(\Pbs\Shop\Models\Product::class, 'productable');
    }
}
