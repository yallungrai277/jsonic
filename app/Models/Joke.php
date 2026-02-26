<?php

namespace App\Models;

use App\Enums\Joke\JokeStatus;
use App\Enums\Joke\JokeType;
use App\Services\Embedding\EmbeddingService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Joke extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'source_id',
        'category',
        'type',
        'content',
        'status',
    ];

    protected $casts = [
        'content' => 'array',
        'type' => JokeType::class,
        'status' => JokeStatus::class,
    ];

    /**
     * Get all embeddings, regardless of the current active model.
     */
    public function embeddings(): HasMany
    {
        return $this->hasMany(JokeEmbeddings::class);
    }

    /**
     * Get singular embedding for the current active model.
     */
    public function embedding(): HasOne
    {
        return $this->hasOne(JokeEmbeddings::class)
            ->where('model', EmbeddingService::getModel());
    }

    /**
     * Category attribute.
     */
    public function category(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ? strtolower($value) : null,
        );
    }
}
