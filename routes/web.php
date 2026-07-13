<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\EconomicIndicatorController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\RiskScoreController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| ROUTE UTAMA
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN (MEMERLUKAN LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/countries', [CountryController::class, 'index'])->name('countries');
    Route::get('/countries/{id}', [CountryController::class, 'show'])->name('countries.show');
    Route::get('/weather', [WeatherController::class, 'index'])->name('weather');
    Route::get('/economic', [EconomicIndicatorController::class, 'index'])->name('economic');
    Route::get('/exchange', [ExchangeRateController::class, 'index'])->name('exchange');
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/ports', [PortController::class, 'index'])->name('ports');
    Route::get('/ports/map', [PortController::class, 'map'])->name('ports.map');
    Route::get('/risk', [RiskScoreController::class, 'index'])->name('risk');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ROUTE TESTING
|--------------------------------------------------------------------------
*/

use App\Services\CountryService;
use App\Services\WeatherService;
use App\Services\CurrencyService;
use App\Services\NewsService;
use App\Services\RiskScoreService;
use App\Services\PortService;

Route::get('/test/countries', function () {
    $service = new CountryService();
    return response()->json($service->fetchAndUpdateCountries());
});

Route::get('/test/risk/all', function () {
    $service = new RiskScoreService(
        new WeatherService(),
        new CurrencyService(),
        new NewsService()
    );
    return response()->json($service->calculateAllRisks());
});

Route::get('/test/currency/{from}/{to}', function ($from, $to) {
    $service = new CurrencyService();
    return response()->json(['rate' => $service->getExchangeRate($from, $to)]);
});

Route::get('/test/news/{code}', function ($code) {
    $service = new NewsService();
    return response()->json($service->fetchNews($code));
});
Route::get('/fetch-all', function () {
    $countryService = new \App\Services\CountryService();
    $currencyService = new \App\Services\CurrencyService();
    $newsService = new \App\Services\NewsService();
    $riskService = new \App\Services\RiskScoreService(
        new \App\Services\WeatherService(),
        new \App\Services\CurrencyService(),
        new \App\Services\NewsService()
    );
    
    $results = [];

    $results['countries'] = $countryService->fetchAndUpdateCountries();
    

    $currencyService->fetchExchangeRates('USD');
    $results['exchange'] = 'Exchange rates updated';
 
    $countries = \App\Models\Country::limit(5)->get();
    foreach ($countries as $country) {
        $newsService->fetchNews($country->country_code);
    }
    $results['news'] = 'News fetched for ' . $countries->count() . ' countries';

    $riskService->calculateAllRisks();
    $results['risk'] = 'Risk calculated for all countries';
    
    return response()->json([
        'success' => true,
        'message' => 'All data fetched successfully!',
        'results' => $results
    ]);
});

    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Users
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/create', [App\Http\Controllers\AdminController::class, 'usersCreate'])->name('admin.users.create');
    Route::post('/users', [App\Http\Controllers\AdminController::class, 'usersStore'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [App\Http\Controllers\AdminController::class, 'usersEdit'])->name('admin.users.edit');
    Route::put('/users/{id}', [App\Http\Controllers\AdminController::class, 'usersUpdate'])->name('admin.users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\AdminController::class, 'usersDelete'])->name('admin.users.delete');
    
    // Ports
    Route::get('/ports', [App\Http\Controllers\AdminController::class, 'ports'])->name('admin.ports');
    Route::get('/ports/create', [App\Http\Controllers\AdminController::class, 'portsCreate'])->name('admin.ports.create');
    Route::post('/ports', [App\Http\Controllers\AdminController::class, 'portsStore'])->name('admin.ports.store');
    Route::get('/ports/{id}/edit', [App\Http\Controllers\AdminController::class, 'portsEdit'])->name('admin.ports.edit');
    Route::put('/ports/{id}', [App\Http\Controllers\AdminController::class, 'portsUpdate'])->name('admin.ports.update');
    Route::delete('/ports/{id}', [App\Http\Controllers\AdminController::class, 'portsDelete'])->name('admin.ports.delete');
    

    Route::get('/articles', [App\Http\Controllers\AdminController::class, 'articles'])->name('admin.articles');
    Route::get('/articles/create', [App\Http\Controllers\AdminController::class, 'articlesCreate'])->name('admin.articles.create');
    Route::post('/articles', [App\Http\Controllers\AdminController::class, 'articlesStore'])->name('admin.articles.store');
    Route::get('/articles/{id}/edit', [App\Http\Controllers\AdminController::class, 'articlesEdit'])->name('admin.articles.edit');
    Route::put('/articles/{id}', [App\Http\Controllers\AdminController::class, 'articlesUpdate'])->name('admin.articles.update');
    Route::delete('/articles/{id}', [App\Http\Controllers\AdminController::class, 'articlesDelete'])->name('admin.articles.delete');
});
require __DIR__.'/auth.php';