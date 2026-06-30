<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\DocumentUploadController;
use App\Http\Controllers\LearningHubController;

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');



Route::get('/contact', [ContactController::class, 'create'])->name('contact');//la sesizari sa imi afiseze pagina
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');//ca sa pot trimite sesizari

Route::get('/',function(){
return view('welcome');
})->name('welcome'); // ca sa ma intorc la pagina de welcome de la sesizari

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});



Route::middleware('auth')->group(function () {
    Route::get('/learning-hub', [LearningHubController::class, 'index'])->name('learning-hub.index');
    Route::post('/learning-hub/upload', [LearningHubController::class, 'upload'])->name('learning-hub.upload');
    Route::post('/learning-hub/save-score', [LearningHubController::class, 'saveQuizScore'])->name('learning-hub.save-score');
    Route::post('/learning-hub/generate-audio', [LearningHubController::class, 'generateAudio'])->name('learning-hub.generate-audio');
});

Route::middleware('auth')->group(function () {
    Route::get('/learning-hub/history', [LearningHubController::class, 'history'])->name('learning-hub.history');
});

require __DIR__ . '/auth.php';