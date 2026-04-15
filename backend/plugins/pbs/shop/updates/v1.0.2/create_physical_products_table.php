<?php
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("pbs_shop_physical_products", function (Blueprint $table) {
            $table->increments("id");
            $table->decimal("weight", 10, 2)->default(0);
            $table->decimal("width", 10, 2)->default(0);
            $table->decimal("height", 10, 2)->default(0);
            $table->decimal("length", 10, 2)->default(0);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("pbs_shop_physical_products"); }
};
