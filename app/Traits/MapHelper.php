<?php

namespace App\Traits;

trait MapHelper
{
    // Haversine formula
    function haversineDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        $earthRadius = 6371;
        // $dLat and $dLon represent the differences in latitude and longitude,
        //  respectively, between the two points
        $dLat = deg2rad($latitudeTo - $latitudeFrom);
        $dLon = deg2rad($longitudeTo - $longitudeFrom);
        // $a represents an intermediate value used in the Haversine formula.
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * sin($dLon / 2) * sin($dLon / 2);
        // $c represents the central angle between the two points (in radians)
        $c = 2 * asin(sqrt($a));
        // $d represents the distance between the two points
        $d = $earthRadius * $c;

        //return distance in KM
        return $d;
    }
}
