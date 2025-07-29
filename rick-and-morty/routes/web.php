<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterController;

Route::get('/', [CharacterController::class, 'index'])->name('home');
Route::get('/characters', [CharacterController::class, 'myCharacters'])->name('characters.index');
Route::post('/characters', [CharacterController::class, 'store'])->name('characters.store');
Route::get('/characters/{id}', [CharacterController::class, 'show'])->name('characters.show');
Route::delete('/characters/{id}', [CharacterController::class, 'destroy'])->name('characters.destroy');
Route::get('/about', fn() => view('about'))->name('about');

Auth::routes();