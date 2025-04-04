<?php

use App\Http\Controllers\GeminiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/idiot', function() {
    return Inertia::render('Idiot');
});

Route::get('/about', function() {
    return Inertia::render('About');
})->name('about');

//Route::get('/dashboard', function() {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function() {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/', function() {
        return Inertia::render('Home');
    })->name('home');

    Route::post('/gemini-document',
        [GeminiController::class, 'generateDocuments'])
        ->name('gemini.documents');
    Route::post('/gemini-from-text',
        [GeminiController::class, 'generateDocumentsFromText'])
        ->name('gemini.text.documents');

    Route::delete('/delete-documents',
        [GeminiController::class, 'deleteDocuments'])
        ->name('gemini.documents.delete');
});

require __DIR__.'/auth.php';
