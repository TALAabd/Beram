<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Repositories\TripFeatureRepository;

class TripFeatureService
{
    public function __construct(private TripFeatureRepository $trip_featureRepository)
    {
    }

    public function getAll()
    {
        return $this->trip_featureRepository->getAll();
    }

    public function find($trip_featureId)
    {
        return $this->trip_featureRepository->find($trip_featureId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $trip_feature = $this->trip_featureRepository->create($validatedData);

        DB::commit();

        return $trip_feature;
    }

    public function update($validatedData, $trip_featureId)
    {
        $trip_feature = $this->trip_featureRepository->find($trip_featureId);

        DB::beginTransaction();

        $this->trip_featureRepository->update($validatedData, $trip_feature);

        DB::commit();

        return true;
    }

    public function delete($trip_featureId)
    {
        $trip_feature = $this->trip_featureRepository->find($trip_featureId);

        DB::beginTransaction();

        $this->trip_featureRepository->delete($trip_feature);

        DB::commit();

        return true;
    }
}
