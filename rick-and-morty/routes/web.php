<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterController;


Route::get('/home', function () {
    return redirect()->route('home');
});

Route::get('/characters/ajax', [CharacterController::class, 'loadCharactersAjax'])->name('characters.ajax');

Route::get('/', [CharacterController::class, 'index'])->name('home');
Route::get('/characters/{id}', [CharacterController::class, 'show'])->name('characters.show');
Route::get('/characters', action: [CharacterController::class, 'myCharacters'])->name('characters.index');

Route::middleware('auth')->group(function () {
    Route::post('/characters', [CharacterController::class, 'store'])->name('characters.store');
    Route::delete('/characters/{id}', [CharacterController::class, 'destroy'])->name('characters.destroy');
    Route::get('/characters/{id}/edit', [CharacterController::class, 'edit'])->name('characters.edit');
    Route::put('/characters/{id}', [CharacterController::class, 'update'])->name('characters.update');
});

Route::get('/about', fn() => view('about'))->name('about');
Auth::routes();