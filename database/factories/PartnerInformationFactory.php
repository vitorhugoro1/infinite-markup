<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\PartnerInformation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerInformationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PartnerInformation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'partner_id' => Partner::factory(),
            'filename' => "{$this->faker->company}.xml",
            'is_processed' => false,
            'processed_data' => null,
            'processed_at' => null
        ];
    }
}
