<?php
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;
use Winter\Storm\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("pbs_shop_digital_products", function (Blueprint $table) {
            $table->increments("id");
            $table->string("file_url")->nullable();
            $table->integer("download_limit")->unsigned()->default(0);
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("pbs_shop_digital_products"); }
};
