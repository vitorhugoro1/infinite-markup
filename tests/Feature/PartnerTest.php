<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PartnerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_be_rendered()
    {
        $this->actingAs(
            User::factory()->create()
        );

        $this->get(route('partner.index'))->assertOk();
    }

    public function test_list_stored_partners()
    {
        $partners = Partner::factory(10)->create();

        $this->actingAs(
            User::factory()->create()
        );

        $this->get(route('partner.index'))
            ->assertSee(
                $partners->first()->name
            );
    }

    public function test_need_be_logged_to_see_partners()
    {
        $partners = Partner::factory(5)->create();

        $this->get(route('partner.index'))
            ->assertDontSee($partners->first()->name)
            ->assertStatus(302);

        $this->assertGuest();
    }

    public function test_can_create_an_partner()
    {
        $this->actingAs(
            User::factory()->create()
        );

        $input = [
            'name' => $this->faker->company
        ];

        $this->post(route('partner.store'), $input)
            ->assertRedirect(route('partner.index'));

        $this->assertDatabaseHas('partners', $input);
    }

    public function test_validate_an_partner_create_form()
    {
        $this->actingAs(
            User::factory()->create()
        );

        $input = [
            'name' => ''
        ];

        $this->post(route('partner.store'), $input)
            ->assertSessionHasErrors([
                'name'
            ]);
    }
}
