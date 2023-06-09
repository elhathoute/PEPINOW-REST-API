<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plante>
 */
class PlanteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

        'name'=>$this->faker->word(),
        'description'=>$this->faker->text(),
        'price'=>$this->faker->numberBetween(100,1000),
        'category_id'=>$this->faker->numberBetween(1,3),
        'image'=>$this->faker->image(),

        ];
    }
}
