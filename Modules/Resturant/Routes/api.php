<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Resturant\Http\Controllers\ResturantController;
use Modules\Resturant\Http\Controllers\MealController;
use Modules\Resturant\Http\Controllers\MenuController;
use Modules\Resturant\Http\Controllers\TableController;
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

        //Routes BY Resturants
        Route::group(['prefix' => 'resturants'], function () {

            Route::get('/', [ResturantController::class, 'index'])->name('resturants.index');
            Route::post('/', [ResturantController::class, 'store'])->name('resturants.store');
            Route::get('/{resturant}', [ResturantController::class, 'show'])->name('resturants.show');
            Route::put('/{resturant}', [ResturantController::class, 'update'])->name('resturants.update');
            Route::delete('/{resturant}', [ResturantController::class, 'destroy'])->name('resturants.destroy');
            // Route::bind('tag', function (string $slug) {
            //     return Modules\Resturant\Models\Resturant::findBySlug($slug);
            // });
            Route::prefix('{resturant}')->group(function () {
                Route::get('media', [ResturantController::class, 'getMedia'])->name('resturants.media');
                Route::post('media', [ResturantController::class, 'addMedia'])->name('resturants.addMedia');
                Route::delete('media/{mediaId}', [ResturantController::class, 'deleteMedia'])->name('resturants.deleteMedia');
                Route::put('featured', [ResturantController::class, 'updateFeatured'])->name('resturants.featured');
                Route::put('status', [ResturantController::class, 'updateStatus'])->name('resturants.status');
                Route::get('tables', [ResturantController::class, 'getTables'])->name('resturants.tables');
                Route::get('menus', [ResturantController::class, 'getMenus'])->name('resturants.menus');

                Route::get('attributeTerms', [ResturantController::class, 'getAttributeTerms'])->name('resturant.terms.get');
                Route::put('terms', [ResturantController::class, 'updateTerms'])->name('resturant.terms.update');
            });
        });

        //Routes BY Tables
        Route::group(['prefix' => 'tables'], function () {
            Route::post('/', [TableController::class, 'store'])->name('tables.store');
            Route::get('/{table}', [TableController::class, 'show'])->name('tables.show');
            Route::put('/{table}', [TableController::class, 'update'])->name('tables.update');
            Route::delete('/{table}', [TableController::class, 'destroy'])->name('tables.destroy');
            Route::prefix('{table}')->group(function () {
                Route::get('media', [TableController::class, 'getMedia'])->name('tables.media');
                Route::post('media', [TableController::class, 'addMedia'])->name('tables.addMedia');
                Route::delete('media/{mediaId}', [TableController::class, 'deleteMedia'])->name('tables.deleteMedia');
                Route::put('status', [TableController::class, 'updateStatus'])->name('tables.status');
            });
        });

        Route::group(['prefix' => 'menus'], function () {
            //Route::get('/', [MenuController::class, 'index'])->name('menus.index');
            Route::post('/', [MenuController::class, 'store'])->name('menus.store');
            Route::get('/{menu}', [MenuController::class, 'show'])->name('menus.show');
            Route::put('/{menu}', [MenuController::class, 'update'])->name('menus.update');
            Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
            Route::prefix('{menu}')->group(function () {
                Route::get('media', [MenuController::class, 'getMedia'])->name('menus.media');
                Route::post('media', [MenuController::class, 'addMedia'])->name('menus.addMedia');
                Route::delete('media/{mediaId}', [MenuController::class, 'deleteMedia'])->name('menus.deleteMedia');
                Route::get('meals', [MenuController::class, 'getMeals'])->name('menu.meals');
            });
        });

        Route::group(['prefix' => 'meals'], function () {
            Route::get('/', [MealController::class, 'index'])->name('meals.index');
            Route::post('/', [MealController::class, 'store'])->name('meals.store');
            Route::get('/{meal}', [MealController::class, 'show'])->name('meals.show');
            Route::put('/{meal}', [MealController::class, 'update'])->name('meals.update');
            Route::delete('/{meal}', [MealController::class, 'destroy'])->name('meals.destroy');
            Route::prefix('{meal}')->group(function () {
                Route::get('media', [MealController::class, 'getMedia'])->name('meals.media');
                Route::post('media', [MealController::class, 'addMedia'])->name('meals.addMedia');
                Route::delete('media/{mediaId}', [MealController::class, 'deleteMedia'])->name('meals.deleteMedia');
            });
        });

    });
});
