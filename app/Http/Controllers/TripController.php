<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests\TripRequest;
use App\Http\Resources\TripResource;
use App\Services\TripService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{
    public function __construct(private TripService $tripService)
    {
        if(Auth::guard('user')->user()) {
            $this->middleware('permission:trips_get',['only' => ['getAll']]);
        }

        $this->middleware('permission:trips_create', ['only' => ['create']]);
        $this->middleware('permission:trips_update', ['only' => ['update', 'addMedia', 'deleteMedia']]);
        $this->middleware('permission:trips_create|trips_update', ['only' => ['addMedia', 'deleteMedia']]);

        $this->middleware('permission:trips_delete', ['only' => ['delete']]);
    }

    public function getAll(Request $request)
    {
        $trips = $this->tripService->getAll($request);
        return $this->successResponse(
            $this->resource($trips, TripResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function topTrip(Request $request)
    {
        $trips = $this->tripService->topTrip($request);
        return $this->successResponse(
            $this->resource($trips, TripResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($tripId, Request $request)
    {
        $trip = $this->tripService->find($tripId, $request);

        return $this->successResponse(
            $this->resource($trip, TripResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(TripRequest $request)
    {   dd('kk');
        $validatedData = $request->validated();
        $trip = $this->tripService->create($validatedData);
        if ($request->file('image') && $request->file('image')->isValid()) {
            $trip->addMedia($request->file('image'))->toMediaCollection('trip');
        }
        return $this->successResponse(
            $this->resource($trip, TripResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(TripRequest $request, $tripId)
    {
        $validatedData = $request->validated();
        $this->tripService->update($validatedData, $tripId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($tripId)
    {
        $this->tripService->delete($tripId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function getMedia($id)
    {
        $media = $this->tripService->getAllMedia($id);
        return $this->successResponse(
            $media,
            'mediaFetchedSuccessfully'
        );
    }

    public function addMedia(Request $request, $id)
    {
        DB::beginTransaction();

        if ($request->file('media') && $request->file('media')->isValid()) {
            $this->tripService->createMedia($id, $request->file('media'), $request->type);
        }
        DB::commit();
        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    public function deleteMedia($tripId, $mediaId)
    {
        $this->tripService->deleteMediaForId($tripId, $mediaId);
        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function updateStatus(Request $request, $tripId)
    {
        $this->tripService->updateStatus($request, $tripId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }
}
