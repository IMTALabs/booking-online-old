<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Booking\BookingController;
use App\Http\Controllers\Api\Categorie\CategorieController;
use App\Http\Controllers\Api\Client\ClientController;
use App\Http\Controllers\Api\OpeningHour\OpeningHourController;
use App\Http\Controllers\Api\Promotion\PromotionController;
use App\Http\Controllers\Api\Service\ServiceController;
use App\Http\Controllers\Api\Staff\StaffController;
use App\Http\Controllers\Api\Statistic\StatisticsController;
use App\Http\Controllers\Api\StoreInformation\StoreInformationController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

Route::post('/set-locale/{locale}', function ($locale) {
    Session::put('locale', $locale);

    return response()->json(['message' => 'Locale set to ' . $locale]);
});

Route::middleware(['auth:sanctum', 'checkadmin', 'language','throttle'])->group(function () {
    // Services
    Route::apiResource('services', ServiceController::class)->only(['update', 'destroy'])->middleware('throttle:60,1');
    Route::apiResource('services', ServiceController::class)->except(['update', 'destroy']);

    // Categories
    Route::apiResource('categories', CategorieController::class)->only(['update', 'destroy'])->middleware('throttle:60,1');
    Route::apiResource('categories', CategorieController::class)->except(['update', 'destroy']);

    // User Admin
    Route::apiResource('admin_users', UserController::class)->only(['update', 'destroy'])->middleware('throttle:60,1');
    Route::apiResource('admin_users', UserController::class)->except(['update', 'destroy']);

    // Store Informations
    Route::apiResource('stores', StoreInformationController::class)->only(['update', 'destroy'])->middleware('throttle:60,1');
    Route::apiResource('stores', StoreInformationController::class)->except(['update', 'destroy']);
    // Opening Hours
    Route::prefix('opening-hours')->group(function () {
        Route::get('/list', [OpeningHourController::class, 'index']);
        Route::get('/{storeId}', [OpeningHourController::class, 'show']);
        Route::post('/post/{storeId}', [OpeningHourController::class, 'store']);
        Route::post('/update/{storeId}', [OpeningHourController::class, 'update'])->middleware('throttle:60,1');
        Route::delete('delete/{id}', [OpeningHourController::class, 'destroy'])->middleware('throttle:60,1');
        //xóa nhanh những ngày đã qua
        Route::delete('quick_delete/{storeId}', [OpeningHourController::class, 'quickDestroy']);
        // thêm 5 ngày mở cửa liên tiếp
        Route::post('/post_5day/{storeId}', [OpeningHourController::class, 'store5']);
    });
    // Booking Management
    Route::apiResource('bookings', BookingController::class)->only(['update', 'destroy'])->middleware('throttle:60,1');
    Route::apiResource('bookings', BookingController::class)->except(['update', 'destroy']);

    // khuyến mãi
    Route::apiResource('discount', PromotionController::class);
    // thống kê
    //tổng số booking
    Route::prefix('statistics')->group(function () {
        Route::get('/total-bookings', [StatisticsController::class, 'getTotalBookings']);
        // tỷ lệ lấp đầy thời gian
        Route::get('/occupancy-rate', [StatisticsController::class, 'getOccupancyRate']);
        // Số lần đặt chỗ của các user
        Route::get('/user-bookings', [StatisticsController::class, 'getUserBookings']);
        // Giá trị trung bình đơn hàng
        Route::get('/average-booking-value', [StatisticsController::class, 'getAverageBookingValue']);
        // Tỷ lệ từ bỏ đặt chỗ
        Route::get('/abandonment-rate', [StatisticsController::class, 'getAbandonmentRate']);
        //Tổng doanh thu từ các lượt đặt chỗ.
        Route::get('/gettotal-revenue', [StatisticsController::class, 'getTotalRevenue']);
        // doanh thu theo dịch vụ
        Route::get('/gettotal-service', [StatisticsController::class, 'getServiceRevenueByStore']);
    });
});

Route::prefix('client')->middleware(['language'])->group(function () {
    Route::get('/list_time', [ClientController::class, 'chooseTime']);
    Route::get('/get-date-working-of-user', [ClientController::class, 'GetDateWorkingOfUser']);
    Route::get('/list-schedule', [ClientController::class, 'getWorkingHoursByUserAndStore']);
    Route::get('/list-user', [ClientController::class, 'getUsersByStoreInformation']);
    Route::get('/list-service', [ClientController::class, 'listService']);
    Route::get('/list-store', [ClientController::class, 'listStore']);
    Route::post('/store_booking', [BookingController::class, 'store']);
});
//nhân viên
Route::middleware(['auth:sanctum', 'language'])->group(function () {
    Route::get('/showprofile', [StaffController::class, 'showProfile']);
    Route::post('/profile/update', [StaffController::class, 'updateProfile']);
});

Route::middleware('auth:sanctum', 'checkuser')->prefix('user')->group(function () {
    // xem lịch làm
    Route::get('/see-schedule', [StaffController::class, 'seeSchedule']);
    // thêm lịch làm user
    Route::post('/schedules', [StaffController::class, 'createSchedule']);
    // xem tất cả booking
    Route::get('/listbooking', [StaffController::class, 'getEmployeeBookings']);
    // xem giờ mở cửa của cửa hàng
    Route::get('/see-opeening-hours', [StaffController::class, 'viewStoreOpeningHours']);
});

Route::middleware('language')->prefix('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});
Route::get('test', [\App\Http\Controllers\TestController::class, 'test']);
// Route::get('test2', [StaffController::class, 'getMail']);
