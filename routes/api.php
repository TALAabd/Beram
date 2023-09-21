<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;


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



Route::group(['prefix' => 'v1'], function () {

    //Roles
    Route::middleware('auth:user')->group(function () {
        Route::resource('roles', RoleController::class)->except(['create', 'show', 'destroy']);
        Route::get('/get-permissions', [RoleController::class, 'getpermissions']);
    });

    Route::group(['prefix' => 'admin/cities'], function () {
        Route::get('/', [CityController::class, 'getAll'])->name('cities.index');
    });
    Route::group([
        'prefix' => 'admin/abouts','controller' => AboutController::class, ], function () {
        Route::get('/', 'getAll');
    });
    Route::group(['prefix' => 'admin', 'middleware' => 'auth:user'], function () {

        //Routes BY Currencies
        Route::group(['prefix' => 'currencies'], function () {
            Route::get('/', [CurrencyController::class, 'getAll'])->name('currencies.index');
            Route::post('/', [CurrencyController::class, 'create'])->name('currencies.store');
            Route::get('/{currency}', [CurrencyController::class, 'find'])->name('currencies.show');
            Route::put('/{currency}', [CurrencyController::class, 'update'])->name('currencies.update');
            Route::delete('/{currency}', [CurrencyController::class, 'delete'])->name('currencies.destroy');
        });

        Route::group([
            'prefix' => '/abouts','controller' => AboutController::class, ], function () {
            // Route::get('/', 'getAll');
            Route::get('/{id}', 'find');
            Route::post('/', 'create');
            Route::post('/{id}', 'update');
            Route::delete('/{id}', 'delete');
        });

        Route::group(['prefix' => 'feature'], function () {
            Route::get('/', [FeatureController::class, 'getAll']);
            Route::post('/', [FeatureController::class, 'create']);
            Route::get('/{currency}', [FeatureController::class, 'find']);
            Route::post('/{currency}', [FeatureController::class, 'update']);
            Route::delete('/{currency}', [FeatureController::class, 'delete']);
        });
        Route::group(['prefix' => 'trip'], function () {
            Route::get('/', [TripController::class, 'getAll'])->name('trip.index.admin');
            Route::post('/', [TripController::class, 'create'])->name('trip.store.admin');
            Route::get('/{trip}', [TripController::class, 'find'])->name('trip.shoe.admin');
            Route::post('/{trip}', [TripController::class, 'update'])->name('trip.update.admin');
            Route::delete('/{trip}', [TripController::class, 'delete'])->name('trip.delete.admin');
            Route::prefix('{trip}')->group(function () {
                Route::get('media', [TripController::class, 'getMedia'])->name('trip.media');
                Route::post('media', [TripController::class, 'addMedia'])->name('trip.addMedia');
                Route::delete('media/{mediaId}', [TripController::class, 'deleteMedia'])->name('trip.deleteMedia');
            });
        });

        //Routes BY Attributes
        Route::group(['prefix' => 'attributes'], function () {
            Route::get('/', [CoreAttributeController::class, 'index'])->name('attributes.index');
            Route::post('/', [CoreAttributeController::class, 'store'])->name('attributes.store');
            Route::get('/{attribute}', [CoreAttributeController::class, 'show'])->name('attributes.show');
            Route::put('/{attribute}', [CoreAttributeController::class, 'update'])->name('attributes.update');
            Route::delete('/{attribute}', [CoreAttributeController::class, 'destroy'])->name('attributes.destroy');
            Route::get('/{attribute}/terms', [CoreAttributeController::class, 'getAllTermsByattribute'])->name('attribute.terms');
        });

        Route::group(['prefix' => 'terms'], function () {
            Route::post('/', [CoreTermController::class, 'store'])->name('terms.store');
            Route::get('/{term}', [CoreTermController::class, 'show'])->name('terms.show');
            Route::put('/{term}', [CoreTermController::class, 'update'])->name('terms.update');
            Route::delete('/{term}', [CoreTermController::class, 'destroy'])->name('terms.destroy');
        });

        Route::group(['prefix' => 'countries'], function () {
            Route::get('/', [CountryController::class, 'getAll'])->name('countries.index');
            Route::post('/', [CountryController::class, 'create'])->name('countries.store');
            Route::get('/{country}', [CountryController::class, 'find'])->name('countries.find');
            Route::put('/{country}', [CountryController::class, 'update'])->name('countries.update');
            Route::delete('/{country}', [CountryController::class, 'delete'])->name('countries.destroy');
            Route::get('/{country}/cities', [CountryController::class, 'getAllCitiesByCountry'])->name('countries.cities');
        });


        Route::group(['prefix' => 'cities'], function () {

            Route::post('/', [CityController::class, 'create'])->name('cities.store');
            Route::get('/{city}', [CityController::class, 'find'])->name('cities.find');
            Route::put('/{city}', [CityController::class, 'update'])->name('cities.update');
            Route::delete('/{city}', [CityController::class, 'delete'])->name('cities.destroy');
            Route::prefix('{city}')->group(function () {
                Route::put('update-best-loaction', [CityController::class, 'updateBestLocation'])->name('cities.updateBestLocation');
                Route::post('media', [CityController::class, 'addMedia'])->name('cities.addMedia');
                Route::delete('media/{mediaId}', [CityController::class, 'deleteMedia'])->name('cities.deleteMedia');
            });
        });

        Route::group(['prefix' => 'banners'], function () {
            Route::get('/', [BannerController::class, 'getAll'])->name('banners.index');
            Route::post('/', [BannerController::class, 'create'])->name('banners.store');
            Route::get('/sections-available', [BannerController::class, 'getBannersSection'])->name('banners.sections-available');
            Route::get('/{banner}', [BannerController::class, 'find'])->name('banners.find');
            Route::post('/{banner}', [BannerController::class, 'update'])->name('banners.update');
            Route::delete('/{banner}', [BannerController::class, 'delete'])->name('banners.destroy');
            Route::prefix('{banner}')->group(function () {
                Route::delete('media/{mediaId}', [BannerController::class, 'deleteMedia'])->name('banners.deleteMedia');
            });
        });

        Route::group(['prefix' => 'stories'], function () {
            Route::get('/', [StoryController::class, 'getAll'])->name('stories.index');
            Route::post('/', [StoryController::class, 'create'])->name('stories.store');
            Route::get('/{story}', [StoryController::class, 'find'])->name('stories.find');
            Route::put('/{story}', [StoryController::class, 'update'])->name('stories.update');
            Route::delete('/{story}', [StoryController::class, 'delete'])->name('stories.destroy');

            Route::prefix('{story}')->group(function () {
                Route::get('items', [StoryController::class, 'getAllItems'])->name('stories.items');
            });
        });

        Route::group(['prefix' => 'story-items'], function () {
            Route::post('/', [StoryItemController::class, 'create'])->name('story-items.store');
            Route::get('/{storyItem}', [StoryItemController::class, 'find'])->name('story-items.find');
            Route::put('/{storyItem}', [StoryItemController::class, 'update'])->name('story-items.update');
            Route::delete('/{storyItem}', [StoryItemController::class, 'delete'])->name('story-items.destroy');
        });

        Route::group(['prefix' => 'business-settings'], function () {
            Route::get('/', [BusinessSettingController::class, 'getAll'])->name('business-settings.index');
            Route::post('/', [BusinessSettingController::class, 'create'])->name('business-settings.store');
            Route::get('/{businessSettingId}', [BusinessSettingController::class, 'find'])->name('business-settings.find');
            Route::put('/{businessSettingId}', [BusinessSettingController::class, 'update'])->name('business-settings.update');
            Route::delete('/{businessSettingId}', [BusinessSettingController::class, 'delete'])->name('business-settings.destroy');
        });
    });


    Route::group(['prefix' => 'customer'], function () {

        Route::get('/setting', [SettingController::class, 'getAll'])->name('setting');

        Route::group(['prefix' => 'favorite', 'middleware' => 'auth:customer'], function () {

            Route::group(['prefix' => 'hotel'], function () {
                Route::get('/get', [WishlistController::class, 'wishlist']);
                Route::delete('/{hotel}/remove', [WishlistController::class, 'favoriteRemove']);
                Route::post('/{hotel}/add', [WishlistController::class, 'favoriteAdd']);
            });

            Route::group(['prefix' => 'trip'], function () {
                Route::get('/get', [WishlistController::class, 'favorite']);
                Route::delete('/{trip}/remove', [WishlistController::class, 'RemoveTripFavorite']);
                Route::post('/{trip}/add', [WishlistController::class, 'AddTripFavorite']);
            });
            
            Route::group(['prefix' => 'restaurant'], function () {
                //
            });
        });

        Route::group(['prefix' => 'like', 'middleware' => 'auth:customer'], function () {

            Route::group(['prefix' => 'hotel'], function () {
                Route::get('/get', [LikeListController::class, 'likelist']);
                Route::delete('/{hotel}/remove', [LikeListController::class, 'likeRemove']);
                Route::post('/{hotel}/add', [LikeListController::class, 'likeAdd']);
            });

            Route::group(['prefix' => 'restaurant'], function () {
                //
            });
        });


        Route::group(['prefix' => 'book-mark', 'middleware' => 'auth:customer'], function () {

            Route::group(['prefix' => 'hotel'], function () {
                Route::get('/get', [BookMarkController::class, 'bookMarklist']);
                Route::delete('/{hotel}/remove', [BookMarkController::class, 'bookMarkRemove']);
                Route::post('/{hotel}/add', [BookMarkController::class, 'bookMarkAdd']);
            });

            Route::group(['prefix' => 'restaurant'], function () {
                //
            });
        });


        Route::group(['prefix' => 'notification', 'middleware' => 'auth:customer'], function () {
            Route::get('/get', [NotificationController::class, 'getAll']);
        });


        Route::group(['prefix' => 'review'], function () {

            Route::group(['prefix' => 'hotel'], function () {
                Route::get('/best', [ReviewController::class, 'topReviews']);
                Route::get('/{hotel}/get', [ReviewController::class, 'reviews']);

                Route::group(['middleware' => 'auth:customer'], function () {
                    Route::delete('/{hotel}/remove/{review}', [ReviewController::class, 'reviewRemove']);
                    Route::post('/{hotel}/add', [ReviewController::class, 'reviewAdd']);
                });
            });

            Route::group(['prefix' => 'restaurant'], function () {
                //
            });
        });

        Route::group(['prefix' => 'banners'], function () {
            Route::get('/', [BannerController::class, 'getAll']);
        });

        Route::group(['prefix' => 'trip'], function () {
            Route::get('/', [TripController::class, 'getAll'])->name('trip.index.customer');
            Route::get('/{trip}', [TripController::class, 'find'])->name('trip.show.customer');
            Route::get('/top/get', [TripController::class, 'topTrip']);
        });

        Route::group(['prefix' => 'stories'], function () {
            Route::get('/', [StoryController::class, 'getAll']);
        });

        Route::group(['prefix' => 'cities'], function () {
            Route::get('/best-location', [CityController::class, 'getBestLocation']);
        });

        Route::group(['prefix' => 'business-settings'], function () {
            Route::get('/', [BusinessSettingController::class, 'getAll']);
        });

        Route::group(['prefix' => 'config'], function () {
            Route::get('/home-page', [ConfigController::class, 'getAppHomePageData'])->name('app.home.page');
        });

        Route::group(['prefix' => 'config'], function () {
            Route::get('/control-panel-statistics', [ConfigController::class, 'getControlPanelStatistics']);
        });

        Route::group(['prefix' => 'attributes'], function () {
            Route::get('/terms', [CoreAttributeController::class, 'getAllAttributeWithTerms']);
        });
    });
});

// Route::group([
//     'prefix' => '/notifications',
//     'controller' => NotificationController::class,
//     // 'middleware' => ''
// ], function () {
//     Route::get('/', 'getAll');
//     Route::get('/{id}', 'find');
//     Route::post('/', 'create');
//     Route::put('/{id}', 'update');
//     Route::delete('/{id}', 'delete');
// });

Route::group([
    'prefix'     => '/trips',
    'controller' => TripController::class,
    // 'middleware' => ''
], function () {
    Route::get('/', 'getAll');
    Route::get('/{id}', 'find');
    Route::post('/', 'create');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'delete');
});

Route::group([
    'prefix' => '/trip_features',
    'controller' => TripFeatureController::class,
    // 'middleware' => ''
], function () {
    Route::get('/', 'getAll');
    Route::get('/{id}', 'find');
    Route::post('/', 'create');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'delete');
});

Route::group([
    'prefix' => '/features',
    'controller' => FeatureController::class,
    // 'middleware' => ''
], function () {
    Route::get('/', 'getAll');
    Route::get('/{id}', 'find');
    Route::post('/', 'create');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'delete');
});

Route::group([
    'prefix' => '/settings',
    'controller' => SettingController::class,
    // 'middleware' => ''
], function () {
    Route::get('/', 'getAll');
    Route::get('/{id}', 'find');
    Route::post('/', 'create');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'delete');
});
