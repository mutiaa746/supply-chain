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
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\WatchlistController;  
use App\Http\Controllers\CompareController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RouteSimulationController;
use App\Http\Controllers\VisualizationController;


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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/login/user', [LoginController::class, 'loginUser'])->name('login.user.post');
    Route::post('/login/admin', [LoginController::class, 'loginAdmin'])->name('login.admin.post');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/countries', [CountryController::class, 'index'])->name('countries');
    Route::get('/countries/{id}', [CountryController::class, 'show'])->name('countries.show');
    

    // ========== WEATHER ==========
    Route::get('/weather', [WeatherController::class, 'index'])->name('weather');
    Route::get('/weather/refresh', [WeatherController::class, 'refresh'])->name('weather.refresh');
    Route::get('/weather/seed', [WeatherController::class, 'seedFromAPI'])->name('weather.seed');
    Route::get('/weather/refresh-all', [App\Http\Controllers\WeatherController::class, 'refreshAll'])->name('weather.refresh-all');
    Route::get('/weather/refresh-batch', [App\Http\Controllers\WeatherController::class, 'refreshBatch'])->name('weather.refresh-batch');

    // ========== ECONOMIC ==========
    Route::get('/economic', [EconomicIndicatorController::class, 'index'])->name('economic');

    // ========== EXCHANGE ==========
    Route::get('/exchange', [ExchangeRateController::class, 'index'])->name('exchange');
    Route::get('/exchange/fetch', [ExchangeRateController::class, 'fetchRates'])->name('exchange.fetch');
    Route::get('/currency', [ExchangeRateController::class, 'index'])->name('currency');
    Route::get('/currency/fetch', [ExchangeRateController::class, 'fetchRates'])->name('currency.fetch');

    // ========== NEWS ==========
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/news/fetch/{country}', [NewsController::class, 'fetch'])->name('news.fetch');

    // ========== PORTS ==========
    Route::get('/ports', [PortController::class, 'index'])->name('ports');
    Route::get('/ports/fetch', [PortController::class, 'fetchPorts'])->name('ports.fetch');
    Route::get('/ports/map', [\App\Http\Controllers\PortMapController::class, 'index'])->name('ports.map');

    // ========== RISK ==========
    Route::get('/risk', [RiskScoreController::class, 'index'])->name('risk');

    // ========== COMPARE ==========
    Route::get('/compare', [App\Http\Controllers\CompareController::class, 'index'])->name('compare');
    Route::get('/compare/result', [App\Http\Controllers\CompareController::class, 'result'])->name('compare.result');
  
        // ========== ROUTE SIMULATION========== 
    Route::get('/route-simulation', [RouteSimulationController::class, 'index'])->name('route-simulation');
    Route::post('/route-simulation/calculate', [RouteSimulationController::class, 'calculate'])->name('route-simulation.calculate');
   
    // ========== WATCHLIST ==========
    Route::get('/watchlist', [\App\Http\Controllers\WatchlistController::class, 'index'])->name('watchlist');
    Route::post('/watchlist', [\App\Http\Controllers\WatchlistController::class, 'store'])->name('watchlist.store');
    Route::delete('/watchlist/{id}', [\App\Http\Controllers\WatchlistController::class, 'destroy'])->name('watchlist.destroy');
    Route::post('/watchlist/toggle', [\App\Http\Controllers\WatchlistController::class, 'toggle'])->name('watchlist.toggle');
  
    // ========== VISUALIZATION ==========
    Route::get('/visualization', [App\Http\Controllers\VisualizationController::class, 'index'])->name('visualization');   
    Route::get('/update-countries-data', function () {
    $service = new \App\Services\CountryService();
    $results = $service->updateAllCountries();
    
    return response()->json([
        'success' => true,
        'message' => 'Data countries berhasil diupdate!',
        'data' => $results
    ]);
});

    // ========== PROFILE ==========
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/create', [AdminController::class, 'usersCreate'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'usersStore'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'usersEdit'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'usersUpdate'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'usersDelete'])->name('admin.users.delete');
    
    Route::get('/ports', [AdminController::class, 'ports'])->name('admin.ports');
    Route::get('/ports/create', [AdminController::class, 'portsCreate'])->name('admin.ports.create');
    Route::post('/ports', [AdminController::class, 'portsStore'])->name('admin.ports.store');
    Route::get('/ports/{id}/edit', [AdminController::class, 'portsEdit'])->name('admin.ports.edit');
    Route::put('/ports/{id}', [AdminController::class, 'portsUpdate'])->name('admin.ports.update');
    Route::delete('/ports/{id}', [AdminController::class, 'portsDelete'])->name('admin.ports.delete');
    
    Route::get('/articles', [AdminController::class, 'articles'])->name('admin.articles');
    Route::get('/articles/create', [AdminController::class, 'articlesCreate'])->name('admin.articles.create');
    Route::post('/articles', [AdminController::class, 'articlesStore'])->name('admin.articles.store');
    Route::get('/articles/{id}/edit', [AdminController::class, 'articlesEdit'])->name('admin.articles.edit');
    Route::put('/articles/{id}', [AdminController::class, 'articlesUpdate'])->name('admin.articles.update');
    Route::delete('/articles/{id}', [AdminController::class, 'articlesDelete'])->name('admin.articles.delete');
});

    Route::get('/test/risk/all', function () {
        $service = new \App\Services\RiskScoreService(
            new \App\Services\WeatherService(),
            new \App\Services\CurrencyService(),
            new \App\Services\NewsService()
        );
        return response()->json($service->calculateAllRisks());
    });

    
    Route::get('/debug-weather', function () {
        try {
        $country = App\Models\Country::where('country_code', 'ID')->first();

        if (!$country) {
            return response()->json(['error' => 'Country not found']);
        }

        $response = Illuminate\Support\Facades\Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => $country->latitude,
            'longitude' => $country->longitude,
            'current_weather' => true,
            'timezone' => 'auto'
        ]);

        $data = $response->json();

        $weather = App\Models\WeatherData::create([
            'country_id' => $country->id,
            'temperature' => $data['current_weather']['temperature'] ?? 0,
            'wind_speed' => $data['current_weather']['windspeed'] ?? 0,
            'weathercode' => $data['current_weather']['weathercode'] ?? 0,
            'description' => 'Test from debug'
        ]);

        return response()->json([
            'success' => true,
            'country' => $country->country_name,
            'weather' => $weather,
            'api' => $data
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
    });
    // Update populasi dari REST Countries API
    Route::get('/update-population', function () {
        $service = new \App\Services\CountryService();
        return response()->json($service->updateCountryData());
    });

  
    Route::get('/update-economic', function () {
        $service = new \App\Services\CountryService();
        return response()->json($service->updateWorldBankData());
    });

    Route::get('/test-route', function () {
    return response()->json([
        'success' => true,
        'message' => 'Test route berhasil!',
        'all_routes' => collect(Route::getRoutes())->map(function($route) {
            return $route->uri();
        })->toArray()
    ]);
});

