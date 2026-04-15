<?php
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('pbs_shop_carts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->string('session_id')->unique()->index();
            $table->timestamps();
        });

        Schema::create('pbs_shop_cart_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cart_id')->unsigned()->index();
            $table->integer('product_id')->unsigned()->index();
            $table->integer('quantity')->unsigned()->default(1);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('pbs_shop_cart_items');
        Schema::dropIfExists('pbs_shop_carts');
    }
};
