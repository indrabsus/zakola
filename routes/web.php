<?php

use App\Http\Controllers\AuthController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Kurikulum\Angkatan;
use App\Livewire\Kurikulum\Jurusan;
use Illuminate\Support\Facades\Route;


//Login Page
Route::get('/',[AuthController::class,'loginpage'])->name('loginpage');
Route::get('/logout',[AuthController::class,'logout'])->name('logout');
//Proses Login
Route::post('loginauth',[AuthController::class,'login'])->name('loginauth');


Route::group(['middleware' => ['auth']], function(){
    // Admin Dashboard
    Route::group(['middleware' => ['cekrole:admin']], function(){
        Route::get('admin/dashboard',Dashboard::class)->name('admin.dashboard');
        Route::get('admin/jurusan',Jurusan::class)->name('admin.jurusan');
        Route::get('admin/angkatan',Angkatan::class)->name('admin.angkatan');
    });
});
