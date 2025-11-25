<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController; // <-- import the controller here

// Other routes...

Route::get('/posts/subjects-by-institution', [QuestionController::class, 'getSubjectsByInstitution'])
    ->name('api.posts.subjects-by-institution');