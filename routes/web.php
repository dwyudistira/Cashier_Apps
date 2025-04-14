<?php

use App\Http\Controllers\Admin\PembelianController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Petugas\PembelianController as PetugasPembelianController;
use App\Http\Controllers\Petugas\ProdukController as PetugasProdukController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'admin'])->group(function () {
    //dashboard
    Route::get('/admin/home', [HomeController::class, 'admin'])->name('admin.dashboard');
    Route::get('/chart-data', [HomeController::class, 'getChartData']);
    //pembelian
    Route::get('/pembelian', [PembelianController::class, "index"])->name("admin.pembelian");
    Route::get('/pembelian-create', [PembelianController::class, "create"])->name("admin.pembelian.create");
    Route::post('/pembelian-store', [PembelianController::class, "store"])->name("admin.pembelian.store");
    Route::get('/pembelian-edit', [PembelianController::class, "edit"])->name("admin.pembelian.edit");
    Route::put('/pembelian-update', [PembelianController::class, "update"])->name("admin.pembelian.update");
    Route::delete('/pembelian-destroy', [PembelianController::class, "destroy"])->name("admin.pembelian.destroy");
    Route::get('/export-pembelian', [PembelianController::class, 'export'])->name('admin.pembelian.export');
    
    //product
    Route::get('/product', [ProdukController::class, "index"])->name("admin.product");
    Route::get('/product-create', [ProdukController::class, "create"])->name("admin.product.create");
    Route::post('/product-store', [ProdukController::class, "store"])->name("admin.product.store");
    Route::get('/product-edit/{id}', [ProdukController::class, "edit"])->name("admin.product.edit");
    Route::put('/product-update/{id}', [ProdukController::class, "update"])->name("admin.product.update");
    Route::put('/product/updateStock', [ProdukController::class, 'updateStock'])->name('admin.product.updateStock');
    Route::delete('/product-destroy/{id}', [ProdukController::class, "destroy"])->name("admin.product.delete");
    
    //user
    Route::get('/user', [UserController::class, "index"])->name("admin.user");
    Route::get('/user-create', [UserController::class, "create"])->name("admin.user.create");
    Route::post('/user-store', [UserController::class, "store"])->name("admin.user.store");
    Route::get('/user-edit/{id}', [UserController::class, "edit"])->name("admin.user.edit");
    Route::put('/user-update/{id}', [UserController::class, "update"])->name("admin.user.update");
    Route::delete('/user-delete/{id}', [UserController::class, "destroy"])->name("admin.user.destroy");
});

Route::middleware(['auth', 'petugas'])->group(function () {
    //Dashboard
    Route::get('/petugas/home', [HomeController::class, 'petugas'])->name('petugas.dashboard');

    //pembelian
    Route::get('/petugas/pembelian', [PetugasPembelianController::class, "index"])->name("petugas.pembelian");
    Route::get('/petugas/pembelian/create', [PetugasPembelianController::class, "create"])->name("petugas.pembelian.create");
    Route::post('/petugas/pembelian/detail-create', [PetugasPembelianController::class, "detail"])->name("petugas.pembelian.detail");
    
    //member
    Route::post('/petugas/pembelian/member', [PetugasPembelianController::class, 'storeMember'])->name('petugas.pembelian.member');
    Route::get('/petugas/pembelian/member', [PetugasPembelianController::class, 'memberPage'])->name('petugas.pembelian.memberPage');
    Route::get('/petugas/pembelian/receipt-member', [PetugasPembelianController::class, 'receiptMember'])->name('petugas.pembelian.receipt_member');
    Route::post('/pembelian/simpan-member', [PetugasPembelianController::class, 'simpanMember'])->name('petugas.pembelian.simpan_member');

    //non member
    Route::get('/petugas/pembelian/receipt', [PetugasPembelianController::class, 'receiptNonMember'])->name('petugas.pembelian.receipt');
    Route::post('/petugas/pembelian/receipt-store', [PetugasPembelianController::class, 'storeNonMember'])->name('petugas.pembelian.receipt_store');

    //Export 
    Route::get('/petugas/pembelian/export-pdf', [PetugasPembelianController::class, 'exportPdf'])->name('petugas.pembelian.export-pdf');
    Route::get('/export-pdf/{id}', [PetugasPembelianController::class, 'exportPdfId'])->name('petugas.pembelian.export-pdf-id');


    //product
    Route::get('/petugas/product', [PetugasProdukController::class, "index"])->name("petugas.product");
});

require __DIR__.'/auth.php';
