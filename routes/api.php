<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIsController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\KPIsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\InstagramAuthController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnalyticsController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('/apis',APIsController::class);
Route::post('/add', [APIsController::class, 'store']);
Route::delete('/delete/{api}',[APIsController::class, 'destroy']);
Route::put('/edit/{api}',[APIsController::class , 'update']);
Route::get('/show/{api}', [APIsController::class, 'show']);
Route::patch('/status/{api}', [APIsController::class, 'updateStatus']);
Route::get('/facebookData', [FacebookController::class, 'getFacebookData']);
Route::apiResource('/KPIs',KPIsController::class);
Route::post('/add-kpi', [KPIsController::class, 'store']);
Route::get('/dashboard', [DashboardController::class, 'index']);  // Return la list des clients en JSON
Route::get('/dashboard/client/{id}', [DashboardController::class, 'showClientDashboard']);  // Return un client spécifique en JSON

Route::post('/generate-pdf', [ExportController::class, 'generatePdf']);

Route::post('/export-chart-data', [ExportController::class, 'exportDashboardCsv']);
Route::get('/auth/instagram/callback', [InstagramAuthController::class, 'instagramCallback']);
Route::get('/auth/instagram', [InstagramAuthController::class, 'redirectToInstagram']);


Route::post('/store-instagram-client', [ClientsController::class, 'store']);
Route::get('/clients', [ClientsController::class, 'index']);
Route::delete('/clients/delete/{client}',[ClientsController::class, 'destroy']);
Route::get('client-dashboard/{id}', [ClientsController::class, 'show']);


Route::get('/clients/{clientId}/dashboard/{slug}', [ClientsController::class, 'getDashboard']);

//KPI
Route::get('/instagram/engagement', [PostsController::class, 'getEngagementData']);
Route::get('/test-engagement', [PostsController::class, 'transformEngagementData']);


Route::get('/engagement/{clientId}', [PostsController::class, 'calculateEngagementKPI']);

Route::get('/engagement/posts/{clientId}', [PostsController::class, 'getEngagementPerPost']);
Route::get('/kpis', [PostsController::class, 'showAllKpis']);
Route::get('/interactions/{clientId}',[PostsController::class,'getInteractions']);
Route::get('/evolution/{clientId}',[PostsController::class,'getEngagementEvolution']);
Route::get('/engagementByUser/{clientId}',[PostsController::class,'getEngagementByUser']);
Route::post('/posts-filter',[PostsController::class,'filterPosts']);



//user
Route::apiResource('/users',UserController::class);
Route::post('/add-user', [UserController::class, 'store']);
Route::put('/edit-user/{user}',[UserController::class , 'update']);
Route::get('/show-user/{user}', [UserController::class, 'show']);
Route::delete('/delete-user/{user}',[UserController::class,'destroy']);


Route::group([

    'middleware' => 'api',


], function ($router) {

    Route::post('login', [AuthController::class,'login']);
    Route::post('signup', [AuthController::class,'signup']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
    Route::post('/activate-user/{id}', [AdminController::class, 'activateUser']);
    Route::middleware('auth:sanctum')->get('/users-pending', [UserController::class, 'getPendingUsers']);

    
    
});


Route::middleware('auth:api')->group(function () {
    Route::get('/analytics/report', [AnalyticsController::class, 'getReport']);
});

