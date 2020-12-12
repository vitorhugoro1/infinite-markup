<?php

namespace App\Actions;

use App\Enum\PartnerInformationStatusEnum;
use App\Models\PartnerInformation;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use App\Exceptions\PartnerInformationNeedFile;

class StorePartnerInformation
{
    public function store(array $inputs, User $user)
    {
        if (!is_a($inputs['file'], UploadedFile::class)) {
            throw new PartnerInformationNeedFile('The file input need be an UploadedFile instance.');
        }

        return PartnerInformation::create([
            'filename' => basename($inputs['file']->store('markups')),
            'user_id' => $user->id,
            'partner_id' => $inputs['partner'],
            'status' => (string) PartnerInformationStatusEnum::queued()
        ]);
    }
}
