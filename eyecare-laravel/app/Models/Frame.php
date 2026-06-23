<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Frame extends Model
{
    protected $fillable = ['name', 'brand', 'material', 'style', 'size'];

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }
}
