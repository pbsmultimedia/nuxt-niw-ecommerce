<?php namespace Pbs\Shop\Updates;

use Seeder;
use Pbs\Shop\Models\Product;
use Pbs\Shop\Models\PhysicalProduct;
use Pbs\Shop\Models\DigitalProduct;

class PbsShopSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data to avoid duplicates if re-run
        Product::truncate();
        PhysicalProduct::truncate();
        DigitalProduct::truncate();

        // 1. Create a Physical Product (Pro Drone)
        $physical1 = PhysicalProduct::create([
            'weight' => 1200,
            'width' => 350,
            'height' => 100,
            'length' => 350
        ]);

        Product::create([
            'title' => 'SkyMaster Pro Drone',
            'slug' => 'skymaster-pro-drone',
            'price' => 899.99,
            'description' => 'A professional-grade drone with 4K camera and 30-minute flight time.',
            'productable_id' => $physical1->id,
            'productable_type' => PhysicalProduct::class
        ]);

        // 2. Create another Physical Product (Backpack)
        $physical2 = PhysicalProduct::create([
            'weight' => 800,
            'width' => 300,
            'height' => 450,
            'length' => 200
        ]);

        Product::create([
            'title' => 'Urban Tech Backpack',
            'slug' => 'urban-tech-backpack',
            'price' => 79.50,
            'description' => 'Waterproof backpack with laptop compartment and solar charging port.',
            'productable_id' => $physical2->id,
            'productable_type' => PhysicalProduct::class
        ]);

        // 3. Create a Digital Product (eBook)
        $digital1 = DigitalProduct::create([
            'file_url' => 'https://example.com/downloads/nuxt3-guide.pdf',
            'download_limit' => 5
        ]);

        Product::create([
            'title' => 'Mastering Nuxt 3 (eBook)',
            'slug' => 'mastering-nuxt-3-ebook',
            'price' => 29.00,
            'description' => 'Everything you need to know to build high-performance web apps with Nuxt 3.',
            'productable_id' => $digital1->id,
            'productable_type' => DigitalProduct::class
        ]);

        // 4. Create a Digital Product (Design Kit)
        $digital2 = DigitalProduct::create([
            'file_url' => 'https://example.com/downloads/ui-kit.zip',
            'download_limit' => 10
        ]);

        Product::create([
            'title' => 'Premium UI Design Kit',
            'slug' => 'premium-ui-design-kit',
            'price' => 45.00,
            'description' => 'A comprehensive set of UI components for modern web and mobile apps.',
            'productable_id' => $digital2->id,
            'productable_type' => DigitalProduct::class
        ]);
    }
}
