<?php

use App\Http\Controllers\Dashboard\ContactMessageController;
use App\Http\Controllers\Dashboard\CvController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\LearningController;
use App\Http\Controllers\Dashboard\PortfolioController;
use App\Http\Controllers\Dashboard\PortfolioInfoController;
use App\Http\Controllers\Dashboard\PortfolioSettingsController;
use App\Http\Controllers\Dashboard\ServerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPortfolioController::class, 'show'])->name('portfolio.public');
Route::post('/portfolio/unlock', [PublicPortfolioController::class, 'unlock'])
    ->middleware('throttle:10,1')
    ->name('portfolio.unlock');
Route::post('/contact', [PublicPortfolioController::class, 'sendMessage'])
    ->middleware('throttle:5,1')
    ->name('portfolio.contact');

Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', DashboardController::class)->name('index');

    Route::resource('servers', ServerController::class)->except(['show']);
    Route::post('servers/{server}/check', [ServerController::class, 'checkNow'])->name('servers.check');
    Route::post('servers/check-all', [ServerController::class, 'checkAll'])->name('servers.check-all');

    // Portfolio sub-sections (before resource to prevent {project} param conflict)
    Route::get('portfolio/settings', [PortfolioSettingsController::class, 'edit'])->name('portfolio.settings.edit');
    Route::put('portfolio/settings', [PortfolioSettingsController::class, 'update'])->name('portfolio.settings.update');

    Route::get('portfolio/info', [PortfolioInfoController::class, 'index'])->name('portfolio.info');
    Route::put('portfolio/info/hero', [PortfolioInfoController::class, 'updateHero'])->name('portfolio.info.update-hero');
    Route::post('portfolio/info/skills', [PortfolioInfoController::class, 'storeSkill'])->name('portfolio.info.skills.store');
    Route::put('portfolio/info/skills/{skill}', [PortfolioInfoController::class, 'updateSkill'])->name('portfolio.info.skills.update');
    Route::delete('portfolio/info/skills/{skill}', [PortfolioInfoController::class, 'destroySkill'])->name('portfolio.info.skills.destroy');
    Route::post('portfolio/info/experiences', [PortfolioInfoController::class, 'storeExperience'])->name('portfolio.info.experiences.store');
    Route::put('portfolio/info/experiences/{experience}', [PortfolioInfoController::class, 'updateExperience'])->name('portfolio.info.experiences.update');
    Route::delete('portfolio/info/experiences/{experience}', [PortfolioInfoController::class, 'destroyExperience'])->name('portfolio.info.experiences.destroy');

    Route::resource('portfolio', PortfolioController::class)->parameters(['portfolio' => 'project'])->except(['show']);
    Route::delete('portfolio/images/{image}', [PortfolioController::class, 'destroyImage'])->name('portfolio.images.destroy');

    Route::get('learning', [LearningController::class, 'index'])->name('learning.index');
    Route::post('learning', [LearningController::class, 'store'])->name('learning.store');
    Route::put('learning/{topic}', [LearningController::class, 'update'])->name('learning.update');
    Route::delete('learning/{topic}', [LearningController::class, 'destroy'])->name('learning.destroy');

    Route::get('cv', [CvController::class, 'index'])->name('cv.index');
    Route::post('cv', [CvController::class, 'store'])->name('cv.store');
    Route::delete('cv', [CvController::class, 'destroy'])->name('cv.destroy');

    Route::get('messages', [ContactMessageController::class, 'index'])->name('messages.index');
    Route::delete('messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
