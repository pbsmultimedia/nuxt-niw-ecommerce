<?php
namespace Pbs\Shop\Models;
use Winter\Storm\Database\Model;
class PhysicalProduct extends Model {
    use \Winter\Storm\Database\Traits\Validation;
    public $table = 'pbs_shop_physical_products';
    protected $guarded = [];
    public $rules = [
        'weight' => 'numeric|min:0',
        'width'  => 'numeric|min:0',
        'height' => 'numeric|min:0',
        'length' => 'numeric|min:0',
    ];
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
