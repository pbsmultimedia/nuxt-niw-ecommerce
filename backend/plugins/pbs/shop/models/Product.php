<?php namespace Pbs\Shop\Models;

use Winter\Storm\Database\Model;

class Product extends Model {
    use \Winter\Storm\Database\Traits\Validation;
    public $table = 'pbs_shop_products';
    protected $guarded = [];
    public $rules = [
        'title' => 'required',
        'slug'  => 'required|unique:pbs_shop_products',
        'price' => 'required|numeric|min:0',
    ];
    public $morphTo = [
        'productable' => []
    ];

    /**
     * @return \Winter\Storm\Database\Relations\MorphTo
     */
    public function productable()
    {
        return $this->morphTo();
    }

    /**
     * Options for the productable type dropdown in the backend
     */
    public function getProductableTypeOptions()
    {
        return [
            \Pbs\Shop\Models\PhysicalProduct::class => 'Physical',
            \Pbs\Shop\Models\DigitalProduct::class => 'Digital',
        ];
    }

    /**
     * Human-readable label for the productable type
     */
    public function getProductableTypeLabelAttribute()
    {
        $options = $this->getProductableTypeOptions();
        return $options[$this->productable_type] ?? $this->productable_type;
    }

    /**
     * Fields that belong to each productable type (not to Product itself).
     * These will be forwarded to the related morphed model on save.
     */
    protected $productableFields = [
        \Pbs\Shop\Models\DigitalProduct::class => ['file_url', 'download_limit'],
        \Pbs\Shop\Models\PhysicalProduct::class => ['weight', 'dimensions'],
    ];

    /**
     * When creating a new Product with a productable_type, we must create
     * the related morphed model first (empty) so we can capture its ID and
     * set productable_id before the Product row is inserted.
     *
     * The actual field values (file_url, etc.) are saved onto the morphed
     * model afterwards in Products::formAfterSave(), once the form data
     * is available from the POST request.
     *
     * NOTE: there is a potential race condition if two products of the same
     * type are created simultaneously — analyse this later.
     */
    public function beforeCreate()
    {
        if ($this->productable_type && !$this->productable_id) {
            $modelClass = $this->productable_type;
            if (class_exists($modelClass)) {
                $relatedModel = new $modelClass;
                $relatedModel->save();
                $this->productable_id = $relatedModel->id;
            }
        }
    }
}
