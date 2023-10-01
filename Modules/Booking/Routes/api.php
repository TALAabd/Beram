<?php

use Modules\Booking\Http\Controllers\BookingController;
use Modules\Booking\Http\Controllers\HotelRoomBookingController;
use Modules\Booking\Http\Controllers\CustomerBookingController;
use Illuminate\Support\Facades\Route;


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

// header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
// header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length');
// header('Access-Control-Allow-Origin: *');

Route::group(['prefix' => 'v1'], function () {


    Route::group(['prefix' => 'admin', 'middleware' => 'auth:user'], function () {

        ############################################
        Route::group(['prefix' => 'bookings'], function () {

            Route::get('/recent',    [BookingController::class, 'getRecentBookings']);
            Route::get('/get/{status}', [BookingController::class, 'getBookings']);
            Route::get('/{booking}', [BookingController::class, 'find']);
            Route::put('/{booking}', [BookingController::class, 'update']);
            Route::put('/trip/{booking}', [BookingController::class, 'updateTrip']);

            Route::prefix('{booking}')->group(function () {
                Route::get('/details', [BookingController::class, 'getBookingDetails']);
                Route::put('/change-status', [BookingController::class, 'changeStatusBooking']);
                Route::put('/confirmed',     [BookingController::class, 'confirmedBooking']);
            });
            ############################################
            Route::group(['prefix' => 'hotel',], function () {
                //Route::post('/book',  [HotelRoomBookingController::class, 'create']);
            });

            ############################################
            Route::group(['prefix' => 'resturant'], function () {
                //Route::post('/book',  [RestaurantTableBookingController::class, 'create']);
            });
        });
    });




    //Routes BY Provider
    Route::group(['prefix' => 'customer', 'middleware' => 'auth:customer'], function () {

        Route::get('/search', [CustomerBookingController::class,'search']);
        Route::get('/trip-search', [CustomerBookingController::class,'search']);

        ############################################
        Route::group(['prefix' => 'bookings',], function () {
            Route::get('/', [CustomerBookingController::class, 'getAllByCustomer']);
            Route::get('/trips', [CustomerBookingController::class, 'getAllTripsByCustomer']);
            Route::get('/{status}', [CustomerBookingController::class, 'getAllByCustomerStatus']);

            Route::prefix('{booking}')->group(function () {
                Route::get('/details', [CustomerBookingController::class, 'getBookingDetails']);
                Route::put('/cancel', [CustomerBookingController::class, 'cancelBooking']);
            });



            ############################################
            Route::group(['prefix' => 'hotel',], function () {
                Route::post('/create-booking',  [CustomerBookingController::class, 'createHotelBooking']);
                Route::post('/create-guest-booking',  [CustomerBookingController::class, 'HotelGuestBooking']);

            });

            ############################################
            Route::group(['prefix' => 'resturant'], function () {
                Route::post('/create-booking',  [CustomerBookingController::class, 'createResturantBooking']);
            });

            ############################################
            Route::group(['prefix' => 'trip'], function () {
                Route::post('/create-booking',  [CustomerBookingController::class, 'createTripBooking']);
                Route::post('/create-guest-booking',  [CustomerBookingController::class, 'createGuestBooking']);
            });
        });
    });
});
