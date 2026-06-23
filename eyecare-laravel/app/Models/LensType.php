<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LensType extends Model
{
    protected $fillable = ['name', 'material', 'coating'];

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }
}
