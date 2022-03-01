<?php

namespace Database\Factories;

use App\Models\CurriculumVitae;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurriculumVitaeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CurriculumVitae::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->word,
        'last_name' => $this->faker->word,
        'gender,10' => $this->faker->word,
        'phone' => $this->faker->word,
        'email' => $this->faker->word,
        'address' => $this->faker->text,
        'profile' => $this->faker->text,
        'description' => $this->faker->text,
        'user_id' => $this->faker->randomDigitNotNull,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'deleted_by' => $this->faker->randomDigitNotNull
        ];
    }
}
