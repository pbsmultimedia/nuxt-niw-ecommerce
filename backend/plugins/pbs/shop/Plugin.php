<?php

namespace Pbs\Shop;

use Backend\Facades\Backend;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails(): array
    {
        return [
            'name'        => 'Shop',
            'description' => 'Polymorphic eCommerce Plugin',
            'author'      => 'Pbs',
            'icon'        => 'icon-shopping-cart'
        ];
    }

    public function registerNavigation(): array
    {
        return [
            'shop' => [
                'label'       => 'Shop',
                'url'         => Backend::url('pbs/shop/products'),
                'icon'        => 'icon-shopping-cart',
                'permissions' => ['pbs.shop.*'],
                'order'       => 500,
                
                'sideMenu' => [
                    'products' => [
                        'label'       => 'Products',
                        'icon'        => 'icon-cube',
                        'url'         => Backend::url('pbs/shop/products'),
                        'permissions' => ['pbs.shop.access_products'],
                    ],
                    'orders' => [
                        'label'       => 'Orders',
                        'icon'        => 'icon-clipboard',
                        'url'         => Backend::url('pbs/shop/orders'),
                        'permissions' => ['pbs.shop.access_orders'],
                    ],
                ]
            ],
        ];
    }

    public function register()
    {
        $this->registerConsoleCommand('products:seed', \Pbs\Shop\Console\SeedProducts::class);
    }
}
