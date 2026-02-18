<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Snippet extends Model
{
    protected $fillable = ['module_id', 'title', 'code', 'language'];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}