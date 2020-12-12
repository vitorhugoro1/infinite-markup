<?php

namespace Tests\Feature;

use App\Models\PartnerInformation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class PartnerInformationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_be_rendered()
    {
        $this->actingAs(
            User::factory()->create()
        );

        $this->get(route('partner-information.index'))->assertOk();
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
