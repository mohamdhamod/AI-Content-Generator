<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\Auth\OtpLoginController;
use App\Http\Controllers\Auth\RegisterLinkController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['guest'])->group(function () {
    Route::post('/register/start', [RegisterLinkController::class, 'start'])
        ->middleware(['throttle:login-otp-request'])
        ->name('register.start');

    Route::get('/register/complete', [RegisterLinkController::class, 'complete'])
        ->name('register.complete');

    Route::get('/login/otp', function () {
        return view('auth.login-otp');
    })->name('login.otp');

    Route::post('/login/otp', [OtpLoginController::class, 'request'])
        ->middleware(['throttle:login-otp-request'])
        ->name('login.otp.request');

    Route::post('/login/otp/verify', [OtpLoginController::class, 'verify'])
        ->middleware(['throttle:login-otp-verify'])
        ->name('login.otp.verify');
});

Route::resource('about-us', Controllers\AboutController::class);
Route::get('/privacy-policy', [Controllers\PrivacyPolicyController::class, 'index'])->name('privacy-policy.index');
Route::get('/terms-conditions', [Controllers\TermsConditionsController::class, 'index'])->name('terms-conditions.index');
Route::get('/check-login-status', [Controllers\HomeController::class, 'checkLoginStatus'])->name('check.login.status');

// Team Invitation Accept (Public - before auth check)
Route::get('/teams/invitation/accept', [Controllers\TeamCollaborationController::class, 'acceptInvitationByToken'])
    ->name('teams.invitation.accept-public')
    ->withoutMiddleware(['auth']);

Route::get('/profile', function () {
    return view('auth.profile');
})->middleware(['auth', 'verified'])->name('profile.index');

// Update user location via AJAX
Route::post('/profile/update-location', function (Illuminate\Http\Request $request) {
    $request->validate([
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    $user = auth()->user();
    $user->latitude = $request->latitude;
    $user->longitude = $request->longitude;
    $user->save();

    return response()->json([
        'success' => true,
        'message' => __('translation.location.location_updated'),
        'latitude' => $user->latitude,
        'longitude' => $user->longitude,
    ]);
})->middleware(['auth', 'verified'])->name('profile.update-location');

// Content Generator Routes
Route::middleware(['auth', 'verified', 'subscription.active'])->prefix('generate')->name('content.')->group(function () {
    Route::get('/', [Controllers\ContentGeneratorController::class, 'index'])->name('index');
    Route::post('/generate', [Controllers\ContentGeneratorController::class, 'generate'])->middleware('throttle:content-generation')->name('generate');
    Route::get('/history', [Controllers\ContentGeneratorController::class, 'history'])->name('history');
    Route::get('/recent', [Controllers\ContentGeneratorController::class, 'recentHistory'])->name('recent');
    Route::get('/favorites', [Controllers\ContentGeneratorController::class, 'favorites'])->name('favorites');
    Route::get('/result/{id}', [Controllers\ContentGeneratorController::class, 'show'])->name('show');
    Route::delete('/history/{id}', [Controllers\ContentGeneratorController::class, 'destroy'])->name('destroy');
    Route::get('/specialty/{specialtyId}/topics', [Controllers\ContentGeneratorController::class, 'getTopics'])->name('specialty.topics');
    Route::get('/result/{id}/export-pdf', [Controllers\ContentGeneratorController::class, 'exportPdf'])->middleware('throttle:pdf-export')->name('export.pdf');
    Route::get('/result/{id}/export-pptx', [Controllers\ContentGeneratorController::class, 'exportPowerPoint'])->middleware('throttle:pdf-export')->name('export.pptx');
    Route::get('/pptx-themes', [Controllers\ContentGeneratorController::class, 'getPowerPointThemes'])->name('pptx.themes');
    Route::post('/result/{id}/toggle-favorite', [Controllers\ContentGeneratorController::class, 'toggleFavorite'])->name('toggle.favorite');
    Route::get('/result/{id}/social-preview', [Controllers\ContentGeneratorController::class, 'getSocialPreview'])->middleware('throttle:social-preview')->name('social.preview');
    
    // AI Refinement Routes
    Route::get('/refinement/options', [Controllers\ContentRefinementController::class, 'getOptions'])->name('refinement.options');
    Route::post('/result/{id}/refine', [Controllers\ContentRefinementController::class, 'refine'])->middleware('throttle:content-generation')->name('refine');
    Route::post('/result/{id}/adjust-tone', [Controllers\ContentRefinementController::class, 'adjustTone'])->middleware('throttle:content-generation')->name('adjust-tone');
    Route::get('/result/{id}/version-history', [Controllers\ContentRefinementController::class, 'versionHistory'])->name('version-history');
    Route::post('/versions/compare', [Controllers\ContentRefinementController::class, 'compareVersions'])->name('versions.compare');
    Route::post('/result/{id}/restore-version', [Controllers\ContentRefinementController::class, 'restoreVersion'])->name('restore-version');
    
    // Phase 3: SEO Scoring Routes
    Route::post('/result/{id}/seo/analyze', [Controllers\SeoScoringController::class, 'analyzeSeo'])
        ->middleware('throttle:content-generation')
        ->name('seo.analyze');
    Route::get('/result/{id}/seo/report', [Controllers\SeoScoringController::class, 'getSeoReport'])->name('seo.report');
    Route::get('/result/{id}/seo/recommendations', [Controllers\SeoScoringController::class, 'getRecommendations'])->name('seo.recommendations');
    Route::post('/seo/batch-analyze', [Controllers\SeoScoringController::class, 'batchAnalyze'])->name('seo.batch-analyze');
    Route::get('/result/{id}/seo/compare', [Controllers\SeoScoringController::class, 'compareScores'])->name('seo.compare');
    
    // Phase 3: Content Calendar Routes
    Route::get('/calendar', [Controllers\ContentCalendarController::class, 'getCalendar'])->name('calendar.view');
    Route::post('/result/{id}/schedule', [Controllers\ContentCalendarController::class, 'scheduleContent'])
        ->middleware('throttle:content-generation')
        ->name('calendar.schedule');
    Route::post('/result/{id}/reschedule', [Controllers\ContentCalendarController::class, 'rescheduleContent'])
        ->middleware('throttle:content-generation')
        ->name('calendar.reschedule');
    Route::post('/result/{id}/publish', [Controllers\ContentCalendarController::class, 'publishContent'])
        ->middleware('throttle:content-generation')
        ->name('calendar.publish');
    Route::post('/result/{id}/archive', [Controllers\ContentCalendarController::class, 'archiveContent'])->name('calendar.archive');
    Route::get('/calendar/upcoming', [Controllers\ContentCalendarController::class, 'getUpcoming'])->name('calendar.upcoming');
    Route::get('/calendar/overdue', [Controllers\ContentCalendarController::class, 'getOverdue'])->name('calendar.overdue');
    Route::post('/calendar/batch-schedule', [Controllers\ContentCalendarController::class, 'batchSchedule'])
        ->middleware('throttle:content-generation')
        ->name('calendar.batch-schedule');
    Route::post('/result/{id}/notes', [Controllers\ContentCalendarController::class, 'updateNotes'])->name('calendar.notes');
});

Route::bind('contentType', function ($value) {
    return \App\Models\ContentType::where('slug', $value)
        ->orWhere('id', $value)
        ->firstOrFail();
});

Route::bind('specialty', function ($value) {
    return \App\Models\Specialty::where('slug', $value)
        ->orWhere('id', $value)
        ->firstOrFail();
});

// Digistore24 Subscription Routes
Route::middleware(['auth', 'verified'])->prefix('subscriptions')->name('subscriptions.')->group(function () {
    Route::get('/', [Controllers\HomeController::class, 'subscriptions'])->name('index');
    Route::get('/plans', [Controllers\HomeController::class, 'subscriptionPlans'])->name('plans');
});

// Digistore24 IPN Webhook (for payment notifications)
Route::post('/webhooks/digistore24', [Controllers\HomeController::class, 'digistore24Webhook'])
    ->name('webhooks.digistore24')
    ->withoutMiddleware(['web', 'csrf']);

// ==============================================================
// PHASE 4: Advanced Features Routes
// ==============================================================

Route::middleware(['auth', 'verified', 'subscription.active'])->group(function () {
    
    // ========== 1. Multilingual Generation Routes ==========
    Route::prefix('content')->name('content.')->group(function () {
        Route::post('/multilingual', [Controllers\MultilingualController::class, 'generateMultilingual'])
            ->middleware('throttle:content-generation')
            ->name('multilingual.generate');
        Route::post('/{id}/translate', [Controllers\MultilingualController::class, 'translateContent'])
            ->middleware('throttle:content-generation')
            ->name('translate');
        Route::get('/{id}/translations', [Controllers\MultilingualController::class, 'getTranslations'])
            ->name('translations.list');
        Route::get('/{id}/translation/{language}', [Controllers\MultilingualController::class, 'getTranslation'])
            ->name('translation.show');
        Route::delete('/{id}/translation/{language}', [Controllers\MultilingualController::class, 'deleteTranslation'])
            ->name('translation.delete');
    });
    Route::get('/languages', [Controllers\MultilingualController::class, 'getSupportedLanguages'])
        ->name('languages.supported');
    
    // ========== 2. Custom Templates Routes ==========
    Route::prefix('templates')->name('templates.')->group(function () {
        Route::get('/', [Controllers\TemplateController::class, 'index'])->name('index');
        Route::get('/popular', [Controllers\TemplateController::class, 'popular'])->name('popular');
        Route::get('/search', [Controllers\TemplateController::class, 'search'])->name('search');
        Route::post('/', [Controllers\TemplateController::class, 'store'])
            ->middleware('throttle:content-generation')
            ->name('store');
        Route::get('/{id}', [Controllers\TemplateController::class, 'show'])->name('show');
        Route::put('/{id}', [Controllers\TemplateController::class, 'update'])->name('update');
        Route::delete('/{id}', [Controllers\TemplateController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/duplicate', [Controllers\TemplateController::class, 'duplicate'])->name('duplicate');
        Route::post('/{id}/apply', [Controllers\TemplateController::class, 'apply'])
            ->middleware('throttle:content-generation')
            ->name('apply');
        Route::post('/{id}/share', [Controllers\TemplateController::class, 'shareWithTeam'])->name('share');
    });
    
    // ========== 3. Analytics Dashboard Routes ==========
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/overview', [Controllers\AnalyticsDashboardController::class, 'overview'])->name('overview');
        Route::get('/content/{id}', [Controllers\AnalyticsDashboardController::class, 'contentPerformance'])->name('content');
        Route::get('/trends', [Controllers\AnalyticsDashboardController::class, 'trends'])->name('trends');
        Route::get('/team/{id}', [Controllers\AnalyticsDashboardController::class, 'teamAnalytics'])->name('team');
        Route::post('/content/{id}/engagement', [Controllers\AnalyticsDashboardController::class, 'calculateEngagement'])->name('engagement');
        Route::post('/export', [Controllers\AnalyticsDashboardController::class, 'export'])->name('export');
    });
    
    // ========== 4. Team Collaboration Routes ==========
    Route::prefix('teams')->name('teams.')->group(function () {
        Route::get('/', [Controllers\TeamCollaborationController::class, 'index'])->name('index');
        Route::post('/', [Controllers\TeamCollaborationController::class, 'store'])
            ->middleware('throttle:content-generation')
            ->name('store');
        Route::get('/{id}', [Controllers\TeamCollaborationController::class, 'show'])->name('show');
        Route::post('/{id}/invite', [Controllers\TeamCollaborationController::class, 'inviteMember'])->name('invite');
        Route::delete('/{id}/members/{userId}', [Controllers\TeamCollaborationController::class, 'removeMember'])->name('remove-member');
        Route::get('/{id}/activity', [Controllers\TeamCollaborationController::class, 'getActivity'])->name('activity');
        Route::get('/{id}/statistics', [Controllers\TeamCollaborationController::class, 'getStatistics'])->name('statistics');
        Route::post('/{teamId}/members/{memberId}/resend-invitation', [Controllers\TeamCollaborationController::class, 'resendInvitation'])->name('resend-invitation');
    });
    
    // Team Invitations
    Route::get('/teams/invitation/accept', [Controllers\TeamCollaborationController::class, 'acceptInvitationByToken'])
        ->name('teams.invitation.accept-token');
    Route::get('/teams/invitations/pending', [Controllers\TeamCollaborationController::class, 'getPendingInvitations'])
        ->name('teams.invitations.pending');
    Route::post('/invitations/{id}/accept', [Controllers\TeamCollaborationController::class, 'acceptInvitation'])
        ->name('invitations.accept');
    Route::post('/invitations/{id}/decline', [Controllers\TeamCollaborationController::class, 'declineInvitation'])
        ->name('invitations.decline');
    
    // Content Assignment & Comments
    Route::post('/content/{id}/assign', [Controllers\TeamCollaborationController::class, 'assignContent'])
        ->name('content.assign');
    Route::put('/assignments/{id}/status', [Controllers\TeamCollaborationController::class, 'updateAssignmentStatus'])
        ->name('assignments.update-status');
    Route::get('/my-tasks', [Controllers\TeamCollaborationController::class, 'getMyTasks'])
        ->name('my-tasks');
    
    Route::get('/content/{id}/comments', [Controllers\TeamCollaborationController::class, 'getComments'])
        ->name('content.comments.index');
    Route::post('/content/{id}/comments', [Controllers\TeamCollaborationController::class, 'addComment'])
        ->name('content.comments.add');
    Route::post('/comments/{id}/reply', [Controllers\TeamCollaborationController::class, 'replyToComment'])
        ->name('comments.reply');
    Route::put('/comments/{id}/resolve', [Controllers\TeamCollaborationController::class, 'resolveComment'])
        ->name('comments.resolve');
});
