<?php

namespace Tests\Unit;

use App\Actions\ProcessMarkupFileData;
use App\Enum\PartnerInformationStatusEnum;
use App\Exceptions\InformationFileNotExists;
use App\Exceptions\InformationIsProcessed;
use App\Models\PartnerInformation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Spatie\QueueableAction\Testing\QueueableActionFake;

class ProcessMarkupFileDataTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_process_people_file()
    {
        $partnerInformation = PartnerInformation::factory()->create([
            'filename' => 'people.xml',
        ]);

        /** @var \App\Actions\ProcessMarkupFileData */
        $action = app(ProcessMarkupFileData::class);

        $action->execute($partnerInformation);

        $this->assertTrue(
            $partnerInformation->status->equals(PartnerInformationStatusEnum::processed())
        );

        $this->assertArrayStructure(
            [
                'person' => [
                    '*' => [
                        'personid',
                        'personname',
                        'phones' => [
                            'phone'
                        ]
                    ]
                ]
            ],
            $partnerInformation->processed_data
        );
    }

    public function test_can_queue_an_execution()
    {
        Queue::fake();

        $partnerInformation = PartnerInformation::factory()->create([
            'filename' => 'people.xml',
        ]);

        /** @var \App\Actions\ProcessMarkupFileData */
        $action = app(ProcessMarkupFileData::class);

        $action->onQueue()->execute($partnerInformation);

        QueueableActionFake::assertPushed(ProcessMarkupFileData::class);
    }

    public function test_provided_markup_file_not_exists()
    {
        $partnerInformation = PartnerInformation::factory()->create([
            'filename' => "{$this->faker->company}.xml",
        ]);

        /** @var \App\Actions\ProcessMarkupFileData */
        $action = app(ProcessMarkupFileData::class);

        $this->expectException(InformationFileNotExists::class);
        $this->expectExceptionMessage('The provided file not exists.');

        $action->execute($partnerInformation);

        $this->assertTrue(
            $partnerInformation->status->equals(PartnerInformationStatusEnum::error())
        );
    }

    public function test_prevent_process_two_times()
    {
        $partnerInformation = PartnerInformation::factory()->create([
            'filename' => "people.xml",
            'status' => (string) PartnerInformationStatusEnum::processing()
        ]);

        /** @var \App\Actions\ProcessMarkupFileData */
        $action = app(ProcessMarkupFileData::class);

        $this->expectException(InformationIsProcessed::class);
        $this->expectExceptionMessage('The partner information is not queued.');

        $action->execute($partnerInformation);
    }
}
