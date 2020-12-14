<?php

namespace App\Http\Controllers\Api;

use App\Models\PartnerInformation;

class PartnerInformationController
{
    public function __invoke()
    {
        return response()->json(
            PartnerInformation::with('partner')->get()
        );
    }
}
