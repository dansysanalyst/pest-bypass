<?php

use Ciareis\Bypass\Route;
use Ciareis\Bypass\Bypass;
use App\Services\PhoneContract;
use App\Services\TourigaPhoneService;
use Illuminate\Http\Client\RequestException;

/**
 *  Setup a service to avoid repeting it on every test
 */

beforeEach(function () {
    $this->service = new TourigaPhoneService();
});

/**
 * Must implement PhoneContract
 */
test('TourigaPhoneService is instance of PhoneContract', function () {
    expect($this->service)->toBeInstanceOf(PhoneContract::class);
});

it('returns VALID = TRUE for a valid phone number', function () {
    //--- Prepare---
    $phoneNumber = '351226078380';
    
    //Body returned by the API

    $body = [
      "is_valid" => true,
      "query" => "351226078380",
      "local_format" => "226078380",
      "international_format" => "+351226078380",
      "country_code" => "PT",
      "country_name" => "Portugal",
      "type" => "landline",
      "rate" => 0.023,
    ];

    //--- Act---
    
    //Serving a route 200 (OK) with the "prebaked" body.

    $bypass = Bypass::serve(
        Route::ok(uri: '/v1/phone/'.$phoneNumber, body: $body) //PHP Server - php -S <random port> - Self closes.
    );

    //Bypass URL served above
    $url = $bypass->getBaseUrl();

    //Tell the service to use Bypass URL instead of the real world URL
    $this->service->setBaseUrl($url);

    //Verify the phone number
    $response = $this->service->validatePhoneNumber($phoneNumber)
        ->all();

    //--- Confirm expectations ---

    //Assert that all routes created are being called
    $bypass->assertRoutes();
    
    expect($response)->toHaveCount(8)
        ->is_valid->ToBeTrue()
        ->country_code->ToBe('PT')
        ->type->toBe('landline')
        ->rate->toBeGreaterThan(0)->toBeFloat();
});

it('returns VALID = FALSE for an invalid phone number', function () {
    $phoneNumber = '16465556611';

    //Data copied from API Doc page
    $body = '{"is_valid":false, "query":"16465556611", "local_format":"", "international_format":"", "country_code":"", "country_name":"", "type":null, "rate":null}';

    $bypass = Bypass::serve(
        Route::ok(uri: '/v1/phone/'.$phoneNumber, body: $body)
    );

    $response = $this->service->setBaseUrl($bypass->getBaseUrl())
        ->validatePhoneNumber($phoneNumber)
        ->all();
    
    $bypass->assertRoutes();
    
    //The test phone number must match the query data on the demo body
    expect($body)->json()->query->toBe($phoneNumber);

    expect($response)->toHaveCount(8)
        ->is_valid->toBeFalse()
        ->country_code->ToBeEmpty()
        ->rate->toBeNull();
});

/**
 *  A malformed number should return a 400 error
 */
it('returns Bad Request for a malformed phone number', function () {
    $phoneNumber = '1';

    $bypass = Bypass::serve(
        Route::badRequest(uri: '/v1/phone/'.$phoneNumber, method: 'GET')
    );

    $this->service->setBaseUrl($bypass->getBaseUrl())
        ->validatePhoneNumber($phoneNumber);

    $bypass->assertRoutes();
})->throws(RequestException::class, 'HTTP request returned status code 400');


it('calculates the call cost for a valid number', function () {
    $phoneNumber = '351226078380';
    
    $body = [
          "is_valid" => true,
          "query" => "351226078380",
          "local_format" => "226078380",
          "international_format" => "+351226078380",
          "country_code" => "PT",
          "country_name" => "Portugal",
          "type" => "landline",
          "rate" => 0.023,
        ];
    
    $bypass = Bypass::serve(
        Route::ok(uri: '/v1/phone/'.$phoneNumber, body: $body)
    );

    $this->service->setBaseUrl($bypass->getBaseUrl())
        ->validatePhoneNumber($phoneNumber);
    
    $callCost = $this->service->callCost(duration: 20);

    expect($callCost)->toEqual(0.51);
});
