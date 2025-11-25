<?php

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SubjectDateController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/questions/list', [PageController::class, 'list'])->name('questions.list');
Route::resource('questions', QuestionController::class);
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/search', [QuestionController::class, 'search'])->name('search');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/chatbot/{id?}', [ChatbotController::class, 'chatbot'])->name('chatbot');
    Route::post('/chat/send', [ChatbotController::class, 'sendMessage'])
    ->name('api.chat.send');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
});
Route::middleware(['web'])->group(function () {
    // Main page to display the form
    Route::get('/subject-dates/edit', [SubjectDateController::class, 'index'])->name('subject-dates.index');

    // API endpoints for fetching filter options and subject data
    Route::post('/api/subject-dates/classes', [SubjectDateController::class, 'getClasses']);
    Route::post('/api/subject-dates/subjects', [SubjectDateController::class, 'getSubjects']);

    // API endpoint for submitting updates
    Route::post('/subject-dates/update', [SubjectDateController::class, 'updateDates'])->name('subject-dates.update');
});

// Other routes...

Route::get('api/posts/subjects-by-institution', [QuestionController::class, 'getSubjectsByInstitution'])
    ->name('api.posts.subjects-by-institution');

require __DIR__.'/auth.php';

