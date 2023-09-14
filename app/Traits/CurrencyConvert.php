<?php

namespace App\Traits;

use App\Http\Services\CurrencyService;

trait CurrencyConvert
{
    protected function currencyConvert($currencySymbol = null)
    {
        $clientIP = request()->ip();

        // Make a cURL request to the ip-api.com API to get the user's country code
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/$clientIP");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        // Parse the JSON response to get the user's country code
        $data = json_decode($response, true);

        $userCountryCode = isset($data['countryCode']) ? $data['countryCode'] : $currencySymbol ?? 'US';

        $currencyDetails = CurrencyService::currencyConvert($userCountryCode);

        return $currencyDetails;
    }

    // protected function currencyConvert($Price)
    // {
    //     // Get the IP address of the client making the request
    //     $clientIP = request()->ip();

    //     // Make a cURL request to the ip-api.com API to get the user's country code
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/$clientIP");
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     $response = curl_exec($ch);
    //     curl_close($ch);

    //     // Parse the JSON response to get the user's country code
    //     $data = json_decode($response, true);
    //     $userCountryCode = $data['countryCode'];

    //     // Make a cURL request to a currency conversion API to get the converted price
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, "https://api.exchangeratesapi.io/latest?base=USD&symbols=$userCountryCode");
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     $response = curl_exec($ch);
    //     curl_close($ch);

    //     // Parse the JSON response to get the exchange rate for the user's country
    //     $data = json_decode($response, true);
    //     $exchangeRate = $data['rates'][$userCountryCode];

    //     // Convert the price to the user's currency
    //     $convertedPrice = $Price * $exchangeRate;

    //     // Format the converted price as a string
    //     $str = number_format($convertedPrice, 2);

    //     return $str;
    // }
}
