<?php

namespace Database\Factories;

use App\Dto\Joke\JokeContentDTO;
use App\Enums\Joke\JokeStatus;
use App\Enums\Joke\JokeType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Joke>
 */
class JokeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'source' => fake()->word(),
            'source_id' => (string) fake()->unique()->randomNumber(),
            'category' => fake()->word(),
            'type' => JokeType::SINGLE->value,
            'status' => JokeStatus::PROCESSED->value,
            'content' => [
                JokeContentDTO::PARTS_PREFIX.'1' => fake()->sentence(),
            ],
        ];
    }

    /**
     * Set joke type and adjust content.
     */
    public function setJokeType(JokeType $type): static
    {
        return $this->state(fn () => [
            'type' => $type->value,
            'content' => match ($type) {
                JokeType::SINGLE => [
                    JokeContentDTO::PARTS_PREFIX.'1' => fake()->sentence(),
                ],
                JokeType::TWO_PART => [
                    JokeContentDTO::PARTS_PREFIX.'1' => fake()->sentence(),
                    JokeContentDTO::PARTS_PREFIX.'2' => fake()->sentence(),
                ],
            },
        ]);
    }
}
