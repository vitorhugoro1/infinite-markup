<?php

namespace App\Actions;

use App\Enum\PartnerInformationStatusEnum;
use App\Exceptions\InformationFileNotExists;
use App\Models\PartnerInformation;
use App\Exceptions\InformationIsProcessed;
use Illuminate\Support\Facades\Storage;
use Spatie\QueueableAction\QueueableAction;

class ProcessMarkupFileData
{
    use QueueableAction;

    private ReadXmlToArray $xmlReader;

    public function __construct(ReadXmlToArray $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    public function execute(PartnerInformation $partnerInformation)
    {
        // Validate if is not processed
        if (!$partnerInformation->status->equals(PartnerInformationStatusEnum::queued())) {
            throw new InformationIsProcessed('The partner information is not queued.');
        }

        // Check if file exists
        if (!Storage::exists("markups/{$partnerInformation->filename}")) {
            $partnerInformation->update([
                'status' => (string) PartnerInformationStatusEnum::error()
            ]);

            throw new InformationFileNotExists('The provided file not exists.');
        }

        $partnerInformation->update([
            'status' => (string) PartnerInformationStatusEnum::processing()
        ]);

        $xmlArrayData = $this->xmlReader
            ->withFile(storage_path("app/markups/{$partnerInformation->filename}"))
            ->validateXmlFile()
            ->getXmlDocumentArray();

        $partnerInformation->update([
            'status' => (string) PartnerInformationStatusEnum::processed(),
            'processed_at' => now(),
            'processed_data' => $xmlArrayData
        ]);
    }
}
