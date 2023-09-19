<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Hotels\Http\Controllers\DashboardController\HotelsController;
use Modules\Hotels\Http\Controllers\DashboardController\RoomsController;
use Modules\Hotels\Http\Controllers\CustomerController\CustomerHotelsController;
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

        //Routes BY Hotels
        Route::group(['prefix' => 'hotels'], function () {

            Route::get('/', [HotelsController::class, 'index'])->name('hotels.index');
            Route::post('/', [HotelsController::class, 'store'])->name('hotels.store');
            Route::get('/{hotel}', [HotelsController::class, 'show'])->name('hotels.show');
            Route::put('/{hotel}', [HotelsController::class, 'update'])->name('hotels.update');
            Route::delete('/{hotel}', [HotelsController::class, 'destroy'])->name('hotels.destroy');
            Route::prefix('{hotel}')->group(function () {
                Route::get('media', [HotelsController::class, 'getMedia'])->name('hotels.media');
                Route::post('media', [HotelsController::class, 'addMedia'])->name('hotels.addMedia');
                Route::delete('media/{mediaId}', [HotelsController::class, 'deleteMedia'])->name('hotels.deleteMedia');
                Route::put('featured', [HotelsController::class, 'updateFeatured'])->name('hotels.featured');
                Route::put('status', [HotelsController::class, 'updateStatus'])->name('hotels.status');
                Route::get('rooms', [HotelsController::class, 'getRooms'])->name('hotels.rooms');
                Route::get('attributeTerms', [HotelsController::class, 'getAttributeTerms'])->name('hotel.terms.get');
                Route::put('terms', [HotelsController::class, 'updateTerms'])->name('hotel.terms.update');
            });
        });

        //Routes BY Rooms
        Route::group(['prefix' => 'rooms'], function () {
            Route::post('/', [RoomsController::class, 'store'])->name('rooms.store');
            Route::get('/{room}', [RoomsController::class, 'show'])->name('rooms.show');
            Route::put('/{room}', [RoomsController::class, 'update'])->name('rooms.update');
            Route::delete('/{room}', [RoomsController::class, 'destroy'])->name('rooms.destroy');
            Route::prefix('{room}')->group(function () {
                Route::get('media', [RoomsController::class, 'getMedia'])->name('rooms.media');
                Route::post('media', [RoomsController::class, 'addMedia'])->name('rooms.addMedia');
                Route::delete('media/{mediaId}', [RoomsController::class, 'deleteMedia'])->name('rooms.deleteMedia');
                Route::put('status', [RoomsController::class, 'updateStatus'])->name('rooms.status');

                Route::get('attributeTerms', [RoomsController::class, 'getAttributeTerms'])->name('room.terms.get');
                Route::put('terms', [RoomsController::class, 'updateTerms'])->name('room.terms.update');
            });
        });
    });


    //Routes BY Appication
    Route::group(['prefix' => 'customer'], function () {
        Route::group(['prefix' => 'hotels'], function () {
            Route::get('/all', [CustomerHotelsController::class, 'getAllHotels'])->name('customer.hotels');
            Route::get('/featured', [CustomerHotelsController::class, 'getFeaturedHotels'])->name('customer.hotels.featured');
            Route::get('/rooms', [CustomerHotelsController::class, 'getRoomsByHotel'])->name('customer.hotel.rooms');
            Route::prefix('{hotel}')->group(function () {
                Route::get('/media', [CustomerHotelsController::class, 'getMediaByHotel'])->name('customer.hotel.media'); //1
                Route::get('/details', [CustomerHotelsController::class, 'getDetailsByHotel'])->name('customer.hotel.details');
                // Route::get('/rooms', [CustomerHotelsController::class, 'getRoomsByHotel'])->name('customer.hotel.rooms');
            });
        });

        Route::group(['prefix' => 'rooms'], function () {
            Route::prefix('{room}')->group(function () {
                Route::get('/media', [CustomerHotelsController::class, 'getMediaByRoom'])->name('customer.room.media');//2
                Route::get('/details', [CustomerHotelsController::class, 'getDetailsByRoom'])->name('customer.room.details');//3
            });
        });


    });
});
