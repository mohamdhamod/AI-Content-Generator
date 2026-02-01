<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
| This file contains the backend routes
| Protected by middleware and user role admin
|
*/


Route::get('/',[Controllers\Dashboard\DashboardController::class,'index'])->name('dashboard');

Route::resource('roles',Controllers\Dashboard\RoleController::class);

// Users Management
Route::resource('users', Controllers\Dashboard\UsersController::class);
Route::put('users/change_password/{id}', [Controllers\Dashboard\UsersController::class, 'change_password'])
    ->name('users.change_password');


Route::resource('config_titles',Controllers\Dashboard\ConfigTitlesController::class);

Route::resource('config_images',Controllers\Dashboard\ConfigImagesController::class);
Route::resource('config_email_links',Controllers\Dashboard\ConfigEmailsLinksController::class);

Route::resource('configurations',Controllers\Dashboard\ConfigurationsController::class);
Route::post('configurations/{id?}/updateActiveStatus',[Controllers\Dashboard\ConfigurationsController::class,'updateActiveStatus'])->name('configurations.updateActiveStatus');

Route::resource('countries',Controllers\Dashboard\CountryController::class);
Route::post('countries/{id?}/updateActiveStatus',[Controllers\Dashboard\CountryController::class,'updateActiveStatus'])->name('countries.updateActiveStatus');

// Subscription Plans Management (Digistore24 Integration)
Route::resource('manage-subscriptions', Controllers\Dashboard\ManageSubscriptionsController::class)->names('subscriptions.dashboard.manage');
Route::post('manage-subscriptions/{id?}/updateActiveStatus', [Controllers\Dashboard\ManageSubscriptionsController::class, 'updateActiveStatus'])->name('subscriptions.dashboard.manage.updateActiveStatus');

// Subscription Features Management (nested under subscriptions)
Route::resource('manage-subscriptions.features', Controllers\Dashboard\SubscriptionFeaturesController::class)->names('subscriptions.manage.features')->except(['show', 'create', 'edit']);
Route::post('manage-subscriptions/{subscription}/features/{id}/updateActiveStatus', [Controllers\Dashboard\SubscriptionFeaturesController::class, 'updateActiveStatus'])->name('subscriptions.manage.features.updateActiveStatus');

// User Subscriptions Management
Route::prefix('subscriptions')->name('subscriptions.')->group(function(){
    Route::get('/', [Controllers\Dashboard\SubscriptionsController::class, 'index'])->name('dashboard.index');
    Route::get('/pending', [Controllers\Dashboard\SubscriptionsController::class, 'pending'])->name('dashboard.pending');
    Route::post('/{subscriptionId}/activate', [Controllers\Dashboard\SubscriptionsController::class, 'activate'])->name('dashboard.activate');
    Route::put('/{userSubscription}/payment-status', [Controllers\Dashboard\SubscriptionsController::class, 'updatePaymentStatus'])->name('dashboard.payment-status.update');
    Route::get('/{subscriptionId}', [Controllers\Dashboard\SubscriptionsController::class, 'show'])->name('dashboard.show');
});

// Specialties Management
Route::resource('specialties', Controllers\Dashboard\SpecialtiesController::class);
Route::post('specialties/{id?}/updateActiveStatus', [Controllers\Dashboard\SpecialtiesController::class, 'updateActiveStatus'])->name('specialties.updateActiveStatus');

// Topics Management (nested under specialties)
Route::resource('specialties.topics', Controllers\Dashboard\TopicsController::class)->except(['show', 'create', 'edit']);
Route::post('specialties/{specialty}/topics/{id}/updateActiveStatus', [Controllers\Dashboard\TopicsController::class, 'updateActiveStatus'])->name('specialties.topics.updateActiveStatus');


