<?php

namespace Tests\Unit;

use App\Actions\StorePartnerInformation;
use App\Models\Partner;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\PartnerInformationNeedFile;

class StorePartnerInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_an_information()
    {
        Storage::fake();

        $author = User::factory()->create();
        $partner = Partner::factory()->create();

        $file = UploadedFile::fake()
            ->createWithContent('person.xml', file_get_contents(__DIR__ . "/../stubs/valid-xml.xml"));

        $inputs = [
            'file' => $file,
            'partner' => $partner->id,
            'async' => true
        ];

        $action = new StorePartnerInformation;

        $partnerInformation = $action->store($inputs, $author);

        Storage::assertExists('markups/' . $file->hashName());

        $this->assertEquals($file->hashName(), $partnerInformation->filename);
        $this->assertEquals($partner->id, $partnerInformation->partner_id);
        $this->assertEquals($author->id, $partnerInformation->user_id);
    }

    public function test_file_input_need_be_uploaded_instance()
    {
        $author = User::factory()->create();
        $partner = Partner::factory()->create();

        $inputs = [
            'file' => '',
            'partner' => $partner->id,
            'async' => true
        ];

        $action = new StorePartnerInformation;

        $this->expectException(PartnerInformationNeedFile::class);

        $action->store($inputs, $author);
    }
}
