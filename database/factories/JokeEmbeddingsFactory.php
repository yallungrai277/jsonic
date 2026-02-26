<?php

namespace Database\Factories;

use App\Models\Joke;
use App\Models\JokeEmbeddings;
use App\Services\Embedding\EmbeddingService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JokeEmbeddings>
 */
class JokeEmbeddingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JokeEmbeddings::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'joke_id' => Joke::factory(),
            EmbeddingService::getColumn() => json_encode(array_fill(0, EmbeddingService::getDimension(), 0.1)),
            'model' => EmbeddingService::getModel(),
        ];
    }
}
