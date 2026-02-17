<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = ['project_id', 'title', 'order'];

    // Relasi balik: Module ini milik Project apa?
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // Relasi: Satu Module memiliki banyak Snippet
    public function snippets(): HasMany
    {
        return $this->hasMany(Snippet::class);
    }
}