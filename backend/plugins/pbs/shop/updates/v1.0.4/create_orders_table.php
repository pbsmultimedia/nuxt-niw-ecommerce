<?php
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('pbs_shop_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->text('address');
            $table->decimal('total', 15, 2)->default(0);
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('pbs_shop_order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->index();
            $table->integer('product_id')->unsigned()->index();
            $table->integer('quantity')->unsigned()->default(1);
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('pbs_shop_order_items');
        Schema::dropIfExists('pbs_shop_orders');
    }
};
