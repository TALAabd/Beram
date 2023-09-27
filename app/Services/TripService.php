<?php

namespace App\Services;

use App\Models\CityTrip;
use App\Models\FeatureValue;
use App\Models\Trip;
use App\Models\TripFeature;
use Illuminate\Support\Facades\DB;
use App\Repositories\TripRepository;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TripService
{
    // public function __construct(private TripRepository $tripRepository)
    // {
    // }

    public function getAll($request)
    {
        if (isset($request->lang)) {
            app()->setlocale($request->lang);
        }
        $page = (isset($request->page)) ? $request->page :  null;
        $per_page = 5;

        return $trips = (isset($page)) ? Trip::query()->filters()->simplePaginate($per_page) :  Trip::query()->filters()->orderBy('id', 'Desc')->get();
    }

    public function topTrip($request)
    {
        if (isset($request->lang)) {
            app()->setlocale($request->lang);
        }
        return Trip::take(6)->orderBy('id', 'Desc')->get();
    }

    public function recentlyTrips()
    {
        return Trip::orderBy('created_at', 'desc')->take(10)->orderBy('id', 'Desc')->get();
    }

    public function find($tripId, $request)
    {
        if (isset($request->lang)) {
            app()->setlocale($request->lang);
        }
        return Trip::find($tripId);
    }

    public function create($validatedData)
    {
        if (!isset($validatedData['lang'])) {
            $validatedData['lang'] = app()->getLocale();
        }
        DB::beginTransaction();

        //create trip
        $data[] = $validatedData;
        $trip = Trip::create($validatedData);
        $trip->setTranslation('name', $validatedData['lang'], $validatedData['name']);
        $trip->setTranslation('description', $validatedData['lang'], $validatedData['description']);

        //create trip feature
        if (isset($validatedData['feature'])) {
            foreach ($validatedData['feature'] as $feature) {
                $tripFeature = TripFeature::where('trip_id', $trip['id'])->where('feature_id', $feature['feature_id'])->first();
                if (!isset($tripFeature)) {
                    $tripFeature = TripFeature::create([
                        'trip_id'    => $trip['id'],
                        'feature_id' => $feature['feature_id'],
                    ]);
                }
                FeatureValue::create([
                    'tripfeatures_id' => $tripFeature['id'],
                    'value'           => $feature['value'],
                ]);
            }
        }

        //ceate trip direction
        if (isset($validatedData['cities'])) {
            foreach ($validatedData['cities'] as $city) {
                $tripCity = DB::table('cities_trips')->where('trip_id', $trip['id'])->where('city_id', $city['city_id'])->first();
                if (!isset($tripCity)) {
                    $citytrip = new CityTrip();
                    $citytrip->trip_id = $trip['id'];
                    $citytrip->city_id = $city['city_id'];
                    $citytrip->setTranslation('dis', $validatedData['lang'], $city['value']);
                    $citytrip->save();
                }
            }
        }
        DB::commit();

        return $trip;
    }

    public function update($validatedData, $tripId)
    {
        $trip = Trip::find($tripId);
        if (!isset($validatedData['lang'])) {
            $validatedData['lang'] = app()->getLocale();
        }

        DB::beginTransaction();

        $trip->setTranslation('name', $validatedData['lang'], $validatedData['name']);
        $trip->setTranslation('description', $validatedData['lang'], $validatedData['description']);
        $trip->price   = $validatedData['price'];
        $trip->date    = $validatedData['date'];
        $trip->period  = $validatedData['period'];
        $trip->contact = $validatedData['contact'];
        $trip->starting_city_id = $validatedData['starting_city_id'];
        $trip->save();

        //delete trip feature
        if (isset($validatedData['feature'])) {

            //get trip feature
            $tripFeatures = TripFeature::where('trip_id', $trip['id'])->get();
            if (isset($tripFeatures)) {
                foreach ($tripFeatures as $tripFeature) {
                    //delete value
                    FeatureValue::where('tripfeatures_id', $tripFeature->id)->delete();
                    $tripFeature->delete();
                }
            }

            foreach ($validatedData['feature'] as $feature) {

                $tripFeature = TripFeature::where('trip_id', $trip['id'])->where('feature_id', $feature['feature_id'])->first();
                if (!isset($tripFeature)) {
                    $tripFeature = TripFeature::create([
                        'trip_id'    => $trip['id'],
                        'feature_id' => $feature['feature_id'],
                    ]);
                }
                FeatureValue::create([
                    'tripfeatures_id' => $tripFeature['id'],
                    'value'           => $feature['value'],
                ]);
            }
        }

        // //create trip feature
        // foreach ($validatedData['feature'] as $feature) {
        //     foreach ($trip->feature as $f) {
        //         DB::table('feature_values')->where('tripfeatures_id', $f->pivot->id)
        //             ->update(['value' => $feature['value']]);
        //     }
        //     $trip->feature()->sync($feature['feature_id']);
        // }

        //update cities
        if (isset($validatedData['cities'])) {
            $cityIds = array_map(function ($item) {
                return $item["city_id"];
            }, $validatedData['cities']);
            $trip->city()->sync($cityIds);
            foreach ($validatedData['cities'] as $city) {
                $cityTrip = CityTrip::where('trip_id', $trip->id)->where('city_id', $city['city_id'])->first();
                if ($cityTrip) {
                    $cityTrip->setTranslation('dis', $validatedData['lang'], $city['value']);
                    $cityTrip->save();
                }
            }
        }
        DB::commit();

        return true;
    }

    public function delete($tripId)
    {
        $trip = Trip::find($tripId);

        DB::beginTransaction();

        $trip->delete();

        DB::commit();

        return true;
    }

    public function createMedia($tripId, $mediaFile, $type = 'media')
    {
        $hotel = Trip::find($tripId);
        if ($type == 'main') {
            $hotel->clearMediaCollection('trip');
            $hotel->addMedia($mediaFile)->toMediaCollection('trip');
        } elseif ($type == 'media') {
            $hotel->addMedia($mediaFile)->toMediaCollection('trips-media');
        }
    }

    public function getAllMedia($id)
    {
        $hotel = Trip::find($id);
        $media = $hotel->getMedia('trips-media');
        $thumbnails = $media->map(function ($item) {
            return [
                'id'  => $item->id,
                'url' => $item->getFullUrl()
            ];
        });
        return $thumbnails;
    }


    public function deleteMediaForId($tripId, $mediaId)
    {
        $hotel = Trip::find($tripId);
        $media = Media::where('id', $mediaId)->first();
        $mediaItem = $hotel->getMedia($media->collection_name)->firstWhere('id', $mediaId);
        $mediaItem->delete();
        return true;
    }
}
