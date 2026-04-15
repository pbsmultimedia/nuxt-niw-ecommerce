<?php

use Illuminate\Support\Facades\Route;

Route::get("/api", function () {
    return [
        "status" => "success",
        "message" => "Hello from WinterCMS (Theme Level Route)"
    ];
});
