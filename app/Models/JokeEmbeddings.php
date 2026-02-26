<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JokeEmbeddings extends Model
{
    use HasFactory;

    protected $fillable = [
        'joke_id',
        'embedding_768',
        'model',
    ];

    protected $casts = [
        'embedding_768' => 'array',
    ];

    /**
     * Joke relationship.
     */
    public function joke(): BelongsTo
    {
        return $this->belongsTo(Joke::class);
    }
}
