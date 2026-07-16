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
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->email == 'admin@example.com') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::get('/login', function () {
    return redirect()->route('login.user');
})->name('login');

Route::get('/login/user', [LoginController::class, 'showUserLoginForm'])->name('login.user');
Route::get('/login/admin', [LoginController::class, 'showAdminLoginForm'])->name('login.admin');

Route::post('/login/user', [LoginController::class, 'loginUser'])->name('login.user.post');
Route::post('/login/admin', [LoginController::class, 'loginAdmin'])->name('login.admin.post');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/countries', [CountryController::class, 'index'])->name('countries');
    Route::get('/countries/{id}', [CountryController::class, 'show'])->name('countries.show');
    Route::get('/weather', [WeatherController::class, 'index'])->name('weather');
    Route::get('/economic', [EconomicIndicatorController::class, 'index'])->name('economic');
    
    // EXCHANGE
    Route::get('/exchange', [ExchangeRateController::class, 'index'])->name('exchange');
    Route::get('/exchange/fetch', [ExchangeRateController::class, 'fetchRates'])->name('exchange.fetch');
    Route::get('/currency', [ExchangeRateController::class, 'index'])->name('currency');
    Route::get('/currency/fetch', [ExchangeRateController::class, 'fetchRates'])->name('currency.fetch');
    
    // NEWS
    Route::get('/news', [App\Http\Controllers\NewsController::class, 'index'])->name('news');
    Route::get('/news/fetch/{country}', [App\Http\Controllers\NewsController::class, 'fetch'])->name('news.fetch');    
    // PORTS
    Route::get('/ports', [PortController::class, 'index'])->name('ports');
    Route::get('/ports/map', [PortController::class, 'map'])->name('ports.map');
    
    // RISK
    Route::get('/risk', [RiskScoreController::class, 'index'])->name('risk');
    
    // TRACKING
    Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
    Route::post('/tracking/search', [TrackingController::class, 'search'])->name('tracking.search');
    
    // COMPARE
    Route::get('/compare', function () {
        $countries = \App\Models\Country::all();
        return view('compare.index', compact('countries'));
    })->name('compare');
    
    Route::get('/compare/result', function (\Illuminate\Http\Request $request) {
        $country1 = \App\Models\Country::with('riskScores')->find($request->country1);
        $country2 = \App\Models\Country::with('riskScores')->find($request->country2);
        return view('compare.result', compact('country1', 'country2'));
    })->name('compare.result');
    
    // WATCHLIST
    Route::get('/watchlist', function () {
        return view('watchlist.index');
    })->name('watchlist');
    
    // VISUALIZATION
    Route::get('/visualization', function () {
        return view('visualization.index');
    })->name('visualization');
    
    // PROFILE
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
});


Route::get('/test/risk/all', function () {
    $service = new \App\Services\RiskScoreService(
        new \App\Services\WeatherService(),
        new \App\Services\CurrencyService(),
        new \App\Services\NewsService()
    );
    return response()->json($service->calculateAllRisks());
});