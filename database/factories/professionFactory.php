<?php

namespace Database\Factories;

use App\Models\profession;
use Illuminate\Database\Eloquent\Factories\Factory;

class professionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = profession::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Brunty\Faker\BuzzwordJobProvider($faker));
        return [
            'name' => $faker->jobTitle()
        ];
    }
}
