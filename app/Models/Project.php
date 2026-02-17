<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = ['user_id', 'name', 'slug', 'description', 'tech_stack'];

    // Relasi: Satu Project memiliki banyak Module
    public function modules() {
    return $this->hasMany(Module::class)->orderBy('order', 'asc');
}
}