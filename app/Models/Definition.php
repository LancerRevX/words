<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable('text', 'source_id')]
class Definition extends Model
{
    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }

    public function examples(): HasMany
    {
        return $this->hasMany(Example::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }
}
