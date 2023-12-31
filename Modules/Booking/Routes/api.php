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

    Route::post('/trip/create-guest-booking',  [CustomerBookingController::class, 'createGuestBooking']);
    Route::post('/hotel/create-guest-booking', [CustomerBookingController::class, 'HotelGuestBooking']);

    Route::get('/search', [CustomerBookingController::class,'search'])->name('booking.search.hotel');
    Route::get('/trip-search', [CustomerBookingController::class,'search'])->name('booking.search.trip');

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
                Route::post('/save-booking-file', [BookingController::class, 'saveBookingFile']);
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

            });

            ############################################
            Route::group(['prefix' => 'resturant'], function () {
                Route::post('/create-booking',  [CustomerBookingController::class, 'createResturantBooking']);
            });

            ############################################
            Route::group(['prefix' => 'trip'], function () {
                Route::post('/create-booking',  [CustomerBookingController::class, 'createTripBooking']);
            });
        });
    });
});
