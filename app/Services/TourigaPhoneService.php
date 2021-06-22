<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class TourigaPhoneService implements PhoneContract
{
    protected string $baseUrl = 'http://127.0.0.1:8000/api';
    private float $callFee = 0.049;
    protected Collection $data;

    /**
     * Set Base URL for the service.
     *
     * @param string $url URL
     *
     * @return self
     */
    public function setBaseUrl(string $url = ''): self
    {
        if (empty($url) === false) {
            $this->baseUrl = $url;
        }

        return $this;
    }
    
    /**
     * Validates a phone number by accessing the API.
     *
     * @param string $phoneNumber International Phone nr. (ex: 12123852066)
     *
     * @return \Illuminate\Support\Collection
     */
    public function validatePhoneNumber(string $phoneNumber): Collection
    {
        if (!$phoneNumber) {
            throw new Exception('A Phone Number must be informed');
        }

        $phoneNumber = \preg_replace('/\D/', '', $phoneNumber);

        $url = "{$this->baseUrl}/v1/phone/{$phoneNumber}";

        $response = Http::get($url);
 
        $response->throw();
       
        return $this->data = collect($response->json());
    }
    
    /**
     * Returns Calls Cost based on Rate and Duration.
     *
     * @param float $duration Call duration in minutes
     *
     * @return null|float
     */
    public function callCost(float $duration): null | float
    {
        if (empty($this->data['is_valid'])) {
            return null;
        }
  
        if ($this->data['rate'] == 0) {
            $this->data['rate'] = 1;
        }

        $duration = ceil($duration); // charge full minutes

        $cost = $this->callFee + ($this->data['rate'] * $duration);

        return round($cost, 2);
    }
}
