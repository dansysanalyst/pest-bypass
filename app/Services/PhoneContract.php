<?php

namespace App\Services;

use Illuminate\Support\Collection;

interface PhoneContract
{
    public function setBaseUrl(string $url = ""): self;

    public function validatePhoneNumber(string $phoneNumber): Collection;

    public function callCost(float $callDuration): null|float;
}
