<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Livewire\Admin\Dashboard;
use Illuminate\Support\Facades\Route;




//Login Page
Route::get('/',[AuthController::class,'loginpage'])->name('loginpage');
Route::get('/logout',[AuthController::class,'logout'])->name('logout');

//Proses Login
Route::post('loginauth',[AuthController::class,'login'])->name('loginauth');

Route::group(['middleware' => ['auth']], function(){
    Route::get('admin/dashboard', Dashboard::class)->name('admin.dashboard');
    $set = new Controller;
    // Now, move the Menu::all() and route definition here
    $data = $set->routeMenu();
    foreach ($data as $item) {
        // Determine the middleware based on $item->parent
        $middleware = 'cekrole:' . $item->nama_role;

        // Define the route without grouping
        Route::middleware($middleware)->get($item->path, $item->class)->name($item->name);
    };
});
