<?php
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("pbs_shop_products", function (Blueprint $table) {
            $table->increments("id");
            $table->string("title");
            $table->string("slug")->index();
            $table->decimal("price", 15, 2)->default(0);
            $table->text("description")->nullable();
            $table->integer("product_data_id")->unsigned()->nullable();
            $table->string("product_data_type")->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("pbs_shop_products"); }
};
