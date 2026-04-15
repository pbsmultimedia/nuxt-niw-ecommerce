<?php

namespace Pbs\Shop\Controllers;

use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;

/**
 * Products Backend Controller
 */
class Products extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    /**
     * @var array Permissions required to view this page.
     */
    protected $requiredPermissions = [
        'pbs.shop.products.manage_all',
    ];

    /**
     * Map productable_type class to its fields.yaml config path.
     * Single source of truth — field names are derived from the yaml, never hardcoded.
     */
    protected $productableFieldsMap = [
        \Pbs\Shop\Models\DigitalProduct::class => '$/pbs/shop/models/digitalproduct/fields.yaml',
        \Pbs\Shop\Models\PhysicalProduct::class => '$/pbs/shop/models/physicalproduct/fields.yaml',
    ];

    /**
     * Fires during both render AND save requests.
     * addPurgeable tells the model trait to strip these fields before building SQL,
     * so they never reach the products table.
     */
    public function formExtendModel($model)
    {
        $fields = $this->getProductableFieldNames($model);
        if (!empty($fields)) {
            $model->addPurgeable($fields);
        }
    }

    public function formExtendFields($form)
    {
        $type = $form->model->productable_type;
        if ($type && isset($this->productableFieldsMap[$type])) {
            $config = $form->makeConfig($this->productableFieldsMap[$type]);
            $form->addFields($config->fields);

            // Store the field names so formBeforeSave / formAfterSave can reference them
            $this->productableFieldNames = array_keys((array) $config->fields);
           // print_r($this->productableFieldNames);
        }
    }

    /**
     * Resolve productable field names from $model->productable_type.
     */
    protected function getProductableFieldNames($model): array
    {
        $type = $model->productable_type;
        if (!$type || !isset($this->productableFieldsMap[$type])) {
            return [];
        }

        $config = $this->asExtension('FormController')->makeConfig($this->productableFieldsMap[$type]);
        return array_keys((array) $config->fields);
    }



    /**
     * After Product is saved, persist the productable fields onto the morphed model.
     */
    public function formAfterSave($model)
    {
        $fields = $this->getProductableFieldNames($model);
        if (empty($fields)) {
            return;
        }

        // Force a fresh query — the cached relation may be null if it was
        // loaded before beforeCreate set productable_id
        // race condition..? analize this later
        $productable = $model->productable()->first();
        if (!$productable) {
            return;
        }

        // WinterCMS submits form data nested under the model name: Product[field_name]
        $formData = post(class_basename($model), []);
        $data = array_intersect_key($formData, array_flip($fields));

        foreach ($data as $key => $value) {
            $productable->$key = $value;
        }
        $productable->save();
    }
}

