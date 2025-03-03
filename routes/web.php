<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\EloquentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//URL::forceScheme('https');
URL::forceScheme('http');



// returns the home page with all eloquent
Route::get('/', EloquentController::class .'@index')->name('eloquents.index');
// returns the form for adding a eloquent
Route::get('/eloquents/create', EloquentController::class . '@create')->name('eloquents.create');
// adds a eloquent to the database
Route::post('/eloquents', EloquentController::class .'@store')->name('eloquents.store');
// returns a page that shows a full eloquent
Route::get('/eloquents/{eloquent}', EloquentController::class .'@show')->name('eloquents.show');
// returns the form for editing a eloquent
Route::get('/eloquents/{eloquent}/edit', EloquentController::class .'@edit')->name('eloquents.edit');
// updates a eloquent
Route::put('/eloquents/{eloquent}', EloquentController::class .'@update')->name('eloquents.update');
// deletes a eloquent
Route::delete('/eloquents/{eloquent}', EloquentController::class .'@destroy')->name('eloquents.destroy');


Route::post('/eloquents/sozdanall', [EloquentController::class, 'sozdanall'])->name('eloquents.sozdanall');
Route::post('/eloquents/deleteall', [EloquentController::class, 'deleteall'])->name('eloquents.deleteall');
Route::post('/eloquents/sohrgogl', [EloquentController::class, 'sohrgogl'])->name('eloquents.sohrgogl');

Route::get('/fetch', [EloquentController::class, 'fetch'])->name('eloquents.fetch');
Route::get('/fetch/{count}', [EloquentController::class, 'fetchcount'])->name('eloquents.fetchcount');
Route::get('/fetchcountreg', [EloquentController::class, 'fetchcountreg'])->name('eloquents.fetchcountreg');




