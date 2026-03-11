<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    BcsController,
    PageController,
    QuestionController,
    ReadingController,
    CommentController,
    BookmarkController,
    CampaignController,
    ChatbotController,
    GuideController,
    HscController,
    ProfileController,
    QuestionCorrectionController,
    SubjectDateController,
    SitemapController,
    ToolController,
    TrackingController};

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/questions/list', [PageController::class, 'list'])->name('questions.list');

/*
|--------------------------------------------------------------------------
| Question Browsing
|--------------------------------------------------------------------------
*/

Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');

Route::get('/questions/{question}/{slug?}', [QuestionController::class, 'show'])->name('questions.show');

Route::get('/search', [QuestionController::class, 'search'])->name('search');

Route::get('/subject/{slug}', [QuestionController::class, 'subject'])->name('subject.show');

/*
|--------------------------------------------------------------------------
| Reading Mode
|--------------------------------------------------------------------------
*/

Route::get('/read/{institution}/{subject}/{question}/{slug?}', [ReadingController::class, 'show'])->name('reading.mode');

Route::get('/exam/{institution?}/{subject?}/{category?}', [ReadingController::class, 'exam'])->name('exam.show');
Route::get('/bcs', [BcsController::class, 'index'])->name('bcs.index');
Route::get('/bcs/{year?}/{category?}', [ReadingController::class, 'bcs'])->name('bcs.show');
Route::get('/hsc', [HscController::class, 'index'])->name('hsc.index');
Route::get('/hsc/{subject}/{year?}/{category?}', [HscController::class, 'hsc'])->name('hsc.show');

/*
|--------------------------------------------------------------------------
| Guide
|--------------------------------------------------------------------------
*/
Route::get('/guide/{slug}', [GuideController::class, 'show'])->name('guide.show');

/*
|--------------------------------------------------------------------------
| Tools & Pages
|--------------------------------------------------------------------------
*/

Route::get('/svg', [ToolController::class, 'svg'])->name('tools.svg');
Route::get('/flowchart', [ToolController::class, 'flowchart'])->name('tools.flowchart');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

/*
|--------------------------------------------------------------------------
| Tracking (Public)
|--------------------------------------------------------------------------
*/

Route::post('/auth/track-activity/{question}', [TrackingController::class, 'logActivity'])->name('track.activity');

Route::get('/suggestions', [TrackingController::class, 'getSuggestions'])->name('suggestions.index');

/*
|--------------------------------------------------------------------------
| Authenticated (No Cache)
|--------------------------------------------------------------------------
*/

//Intent
Route::get('/auth/user-intent', [TrackingController::class, 'getUserIntent']);

Route::middleware('no.cache.auth')->group(function () {

    Route::get('/auth/status', [ProfileController::class, 'getStatus'])->name('auth.status');

    Route::middleware(['auth', 'verified'])->group(function () {

        /*
        | Profile
        */
        Route::get('/auth/profile', [ProfileController::class, 'show'])->name('profile.show');

        Route::get('/auth/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        /*
        | Bookmarks
        */
        Route::get('/auth/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');

        Route::post('/bookmarks/toggle', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');

        /*
        | Question Creation / Editing
        */
        Route::get('/questions-create', [QuestionController::class, 'createBlade'])->name('questions.create');
        
        Route::post('/image-upload', [QuestionController::class, 'upload'])->name('api.image.upload');

        Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');

        Route::get('/auth/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');

        Route::put('/auth/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');

        /*
        | Comments
        */
        Route::post('/questions/{question}/comments', [CommentController::class, 'store'])->name('comments.store');

        /*
        | Chatbot
        */
        Route::get('/chatbot/{id?}', [ChatbotController::class, 'chatbot'])->name('chatbot');

        Route::post('/chat/send', [ChatbotController::class, 'sendMessage'])->name('api.chat.send');

        /*
        | Reading Tracking
        */
        Route::post('/auth/reading/track-view', [ReadingController::class, 'trackView']);
        Route::get('/auth/get-next-question/{question}', [ReadingController::class, 'getNextQuestion']);
        
        //Campaigns
        Route::get('/auth/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
        Route::post('/auth/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Only
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');

    /*
    | Campaigns
    */
    Route::resource('campaigns', CampaignController::class);

    Route::post('/auth/campaigns/{campaign}/toggle', [CampaignController::class, 'toggle'])->name('campaigns.toggle');

    /*
    | Subject Dates
    */
    Route::get('/subject-dates/edit', [SubjectDateController::class, 'index'])->name('subject-dates.index');

    Route::post('/api/subject-dates/classes', [SubjectDateController::class, 'getClasses']);
    Route::post('/api/subject-dates/subjects', [SubjectDateController::class, 'getSubjects']);

    Route::post('/subject-dates/update', [SubjectDateController::class, 'updateDates'])->name('subject-dates.update');

    /*
    | Question Verification
    */
    Route::get('/auth/verify', [QuestionCorrectionController::class, 'index']);
    Route::post('/auth/verify/{post}', [QuestionCorrectionController::class, 'update'])->name('questions.verify');

    Route::get('/auth/ai-suggest/{post}', [QuestionCorrectionController::class, 'getAiSuggestion'])->name('ai.suggest');

    Route::get('/auth/auto-topic', [QuestionCorrectionController::class, 'autoPopulateTopic']);
});

/*
|--------------------------------------------------------------------------
| API & System
|--------------------------------------------------------------------------
*/

Route::get('/sitemap', [SitemapController::class, 'generate']);

Route::get('/api/posts/subjects-by-institution', [QuestionController::class, 'getSubjectsByInstitution'])->name('api.posts.subjects-by-institution');

require __DIR__ . '/auth.php';