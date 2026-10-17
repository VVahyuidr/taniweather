<?php

use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - TaniWeather
| Sistem Informasi Monitoring Cuaca dan Potensi Pertanian di Lombok Timur
|--------------------------------------------------------------------------
*/

Route::get('/', [WeatherController::class, 'index'])->name('weather.dashboard');
Route::get('/dashboard', [WeatherController::class, 'index']);
Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');

Route::get('/prakiraan', [WeatherController::class, 'forecast'])->name('weather.forecast');
Route::get('/analitik', [WeatherController::class, 'analytics'])->name('weather.analytics');
Route::get('/potensi-pertanian', [WeatherController::class, 'agriculture'])->name('weather.agriculture');
Route::get('/tentang', [WeatherController::class, 'about'])->name('weather.about');

Route::get('/api/weather', [WeatherController::class, 'api'])->name('weather.api');
