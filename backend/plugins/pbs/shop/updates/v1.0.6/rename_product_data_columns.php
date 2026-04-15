<?php

use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pbs_shop_products', function (Blueprint $table) {
            if (Schema::hasColumn('pbs_shop_products', 'product_data_id') && !Schema::hasColumn('pbs_shop_products', 'productable_id')) {
                $table->renameColumn('product_data_id', 'productable_id');
            }

            if (Schema::hasColumn('pbs_shop_products', 'product_data_type') && !Schema::hasColumn('pbs_shop_products', 'productable_type')) {
                $table->renameColumn('product_data_type', 'productable_type');
            }
        });
    }

    public function down()
    {
        Schema::table('pbs_shop_products', function (Blueprint $table) {
            if (Schema::hasColumn('pbs_shop_products', 'productable_id') && !Schema::hasColumn('pbs_shop_products', 'product_data_id')) {
                $table->renameColumn('productable_id', 'product_data_id');
            }

            if (Schema::hasColumn('pbs_shop_products', 'productable_type') && !Schema::hasColumn('pbs_shop_products', 'product_data_type')) {
                $table->renameColumn('productable_type', 'product_data_type');
            }
        });
    }
};
