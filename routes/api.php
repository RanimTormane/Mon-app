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
use App\Http\Controllers\GoogleAnalyticsController;
use App\Http\Controllers\GoogleAdsController;
use App\Http\Controllers\TraficStatsController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ProfileController;



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
    Route::get('profile', [AuthController::class,'me'])->middleware('auth:api');
    Route::post('/activate-user/{id}', [AdminController::class, 'activateUser']);
    Route::middleware('auth:sanctum')->get('/users-pending', [UserController::class, 'getPendingUsers']);
    Route::post('sentPasswordReserLink',[ResetPasswordController::class,'sendEmail']);
    Route::post('resetPassword',[ChangePasswordController::class,'resetPassword']);
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
   
});
Route::middleware('auth:api')->post('change-password', [UserController::class, 'changePassword']);


//google_analytics
Route::get('/kpi-data', [GoogleAnalyticsController::class, 'etlProcess']);
Route::get('/conversion-Rate', [GoogleAnalyticsController::class, 'getConversionGlobalRate']);
Route::get('/conversion-campagnes', [GoogleAnalyticsController::class, 'conversionParCampagne']);
Route::get('/conversions-by-traffic', [GoogleAnalyticsController::class, 'getConversionsByTrafficSource']);
Route::get('/conversions-by-lead-type', [GoogleAnalyticsController::class, 'getConversionsByLeadType']);
Route::get('/kpi/lead-score-evolution', [GoogleAnalyticsController::class, 'getLeadScoreEvolution']);
Route::post('/marketing-filter', [GoogleAnalyticsController::class, 'getFilteredData']);


//google_ads
Route::get('/Ads-data', [GoogleAdsController::class, 'etlProcessAds']);
Route::get('/CAC-Rate', [GoogleAdsController::class, 'getGlobalCAC']);
Route::get('/cac-by-campaign', [GoogleAdsController::class, 'getCACByCampaign']);
Route::get('/cac-by-month', [GoogleAdsController::class, 'getCACByMonth']);
Route::get('/cost-by-leads', [GoogleAdsController::class, 'getCostByLeads']);
Route::get('/ROAS', [GoogleAdsController::class, 'getGlobalROAS']);
Route::get('/ROAS-by-product', [GoogleAdsController::class, 'getROASByProduct']);
Route::get('/ROAS-by-campaign', [GoogleAdsController::class, 'getROASByCampaign']);
Route::get('/profit-by-campain', [GoogleAdsController::class, 'getProfitabilityByCampaign']);
Route::post('/finance-filter', [GoogleAdsController::class, 'filterFinanceData']);



//trafic-stats
Route::get('/Trafic-data', [TraficStatsController::class, 'etlProcessTrafic']);
Route::get('/visit-by-month', [TraficStatsController::class, 'getVisiterByMonth']);
Route::get('/sessionDuration-by-month', [TraficStatsController::class, 'getSessionDurationByMonth']);
Route::get('/bounce-rate-stats', [TraficStatsController::class, 'bounceRateStats']);
Route::get('/pages-vues-session', [TraficStatsController::class, 'getVisitPagesBySession']);
Route::get('/new-visitors-date', [TraficStatsController::class, 'getNewVisitors']);
Route::post('/traffic-filter',[TraficStatsController::class,'filterTraficData']);