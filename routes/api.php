<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| DEMO API
|--------------------------------------------------------------------------
|
| Demo written to simulate a real world phone verifier API.
|
*/

Route::get('/v1/phone/{phoneNumber}', function ($phoneNumber) {
    $countriesData = [
        '1' => ['country_code' => 'US', 'country_name' => 'United States', 'rate' => 0.01],
        '351' => ['country_code' => 'PT', 'country_name' => 'Portugal', 'rate' => 0.023],
    ];

    $validNumbers = ['351226078380','16464846201'];
    $invalidNumbers = ['3512260783809', '16465556611'];

    $phoneNumber = \preg_replace('/\D/', '', $phoneNumber);

    
    $message = [
        "is_valid" => false,
        "query" => $phoneNumber,
        "local_format" => "",
        "international_format" => "",
        "country_code" => "",
        "country_name" => "",
        "type" => null,
        "rate" => null,
    ];

    // Malformed number
    if (strlen($phoneNumber) < 3) {
        return response()->json(['error' => 'A phone number should have at least 3 digits.'], 400);
    }
    
    // Number validated and ruled INVALID
    if (in_array($phoneNumber, $invalidNumbers)) {
        return response()->json($message);
    }

    // Number validated and ruled VALID
    if (in_array($phoneNumber, $validNumbers)) {
        $phoneSettings = [];

        foreach ($countriesData as $country => $countryData) {
            $len = strlen($country);

            if (substr($phoneNumber, 0, $len) == $country) {
                $countryData['local_format'] = substr($phoneNumber, $len);
                $phoneSettings = $countryData;
            }
        }

        $message = [
                "is_valid" => true,
                "query" => $phoneNumber,
                "local_format" => $phoneSettings['local_format'],
                "international_format" => '+'.$phoneNumber,
                "country_code" => $phoneSettings['country_code'],
                "country_name" => $phoneSettings['country_name'],
                "type" => 'landline',
                "rate" => $phoneSettings['rate'],
        ];

        return response()->json($message);
    }

    return response()->json(['error' => 'Number not found. We have a small phone database ;)'], 404);
});
