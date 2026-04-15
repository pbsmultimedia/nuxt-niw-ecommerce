<?php

namespace Pbs\Shop\Controllers;

use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;

/**
 * Orders Backend Controller
 */
class Orders extends Controller
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
        'pbs.shop.orders.manage_all',
    ];
}
