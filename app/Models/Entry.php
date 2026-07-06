<?php

namespace App\Models;

use App\Enums\EntryClass;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

#[Fillable('spelling', 'pronounciation', 'class')]
class Entry extends Model
{
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function definitions(): HasMany
    {
        return $this->hasMany(Definition::class);
    }

    #[Override]
    protected function casts()
    {
        return ['class' => EntryClass::class];
    }
}
