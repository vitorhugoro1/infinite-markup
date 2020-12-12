<?php

namespace Tests\Feature;

use App\Actions\ProcessMarkupFileData;
use App\Enum\PartnerInformationStatusEnum;
use App\Models\Partner;
use App\Models\PartnerInformation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Spatie\QueueableAction\Testing\QueueableActionFake;

class PartnerInformationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_be_rendered()
    {
        $this->actingAs(
            User::factory()->create()
        );

        $this->get(route('partner-information.index'))->assertOk();
        $this->get(route('partner-information.create'))->assertOk();
    }

    public function test_can_create_and_queue_information()
    {
        Queue::fake();
        Storage::fake();

        $partner = Partner::factory()->create();
        $author = User::factory()->create();

        $this->actingAs($author);

        $file = UploadedFile::fake()
            ->createWithContent('person.xml', file_get_contents(__DIR__ . "/../stubs/valid-xml.xml"));

        $response = $this->post(route('partner-information.store'), [
            'file' => $file,
            'partner' => $partner->id,
            'async' => true
        ]);

        $response->assertRedirect(route('partner-information.index'));

        $this->assertDatabaseHas('partner_information', [
            'filename' => $file->hashName(),
            'partner_id' => $partner->id,
            'user_id' => $author->id,
            'status' => (string) PartnerInformationStatusEnum::queued()
        ]);

        Storage::assertExists("markups/" . $file->hashName());
        QueueableActionFake::assertPushed(ProcessMarkupFileData::class);
    }

    public function test_can_create_and_sync_process_information()
    {
        $partner = Partner::factory()->create();
        $author = User::factory()->create();

        $this->actingAs($author);

        $file = UploadedFile::fake()
            ->createWithContent('person.xml', file_get_contents(__DIR__ . "/../stubs/valid-xml.xml"));

        $response = $this->post(route('partner-information.store'), [
            'file' => $file,
            'partner' => $partner->id,
            'async' => false
        ]);

        $response->assertRedirect(route('partner-information.index'));

        $this->assertDatabaseHas('partner_information', [
            'filename' => $file->hashName(),
            'partner_id' => $partner->id,
            'user_id' => $author->id,
            'status' => (string) PartnerInformationStatusEnum::processed()
        ]);

        $information = PartnerInformation::query()
            ->where('user_id', $author->id)
            ->where('partner_id', $partner->id)
            ->where('filename', $file->hashName())
            ->first();

        $this->assertNotEmpty($information->processed_data);
        $this->assertArrayStructure(
            [
                'person' => [
                    'personid',
                    'personname',
                    'phones' => [
                        'phone'
                    ]
                ]
            ],
            $information->processed_data
        );

        unlink(storage_path("app/markups/" . $file->hashName()));
    }

    public function test_list_stored_upload_informations()
    {
        $author = User::factory()->create();

        $informations = PartnerInformation::factory(5)->create([
            'user_id' => $author
        ]);

        $this->actingAs(
            $author
        );

        $this->get(route('partner-information.index'))
            ->assertSee(
                $informations->first()->filename
            );
    }

    public function test_need_be_logged_to_see_uploads()
    {
        $informations = PartnerInformation::factory(5)->create();

        $this->get(route('partner-information.index'))
            ->assertDontSee($informations->first()->filename)
            ->assertStatus(302);

        $this->assertGuest();
    }
}
