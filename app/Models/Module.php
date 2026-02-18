<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    // Tambahkan 'slug' ke dalam fillable
    protected $fillable = ['project_id', 'title', 'slug', 'order'];

    /**
     * Relasi balik: Module ini milik Project apa?
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relasi: Satu Module memiliki banyak Snippet
     */
    public function snippets(): HasMany
    {
        return $this->hasMany(Snippet::class);
    }
}