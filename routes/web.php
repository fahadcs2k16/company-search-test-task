<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanySearchService;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return redirect()->route('companies.search');
});

Route::get('/companies/search', [CompanySearchService::class, 'search'])->name('companies.search');
Route::get('/companies/{country}/{id}', [CompanySearchService::class, 'show'])->name('companies.show');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
