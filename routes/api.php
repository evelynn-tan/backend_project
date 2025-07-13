<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\PublikasiController;
Route::post('/login', [AuthController::class, 'login'])->name('login');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//mesmastikan user yg login dgn token bisa akses ini
Route::middleware('auth:sanctum')->group(function () { 
// Publikasi 
Route::get('/publikasi', [PublikasiController::class, 'index']); 
Route::post('/publikasi', [PublikasiController::class, 'store']); 
Route::get('/publikasi/{id}', [PublikasiController::class, 'show']);
Route::put('/publikasi/{id}', [PublikasiController::class, 'update']);
Route::delete('/publikasi/{id}', [PublikasiController::class, 'destroy']);
Route::post('/logout', [AuthController::class, 'logout']);
}); 
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
