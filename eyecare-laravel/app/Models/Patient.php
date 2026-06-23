<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'middle_name', 'last_name',
        'birthdate', 'gender', 'phone_number', 'address',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
        ];
    }

    public function prescription(): HasOne
    {
        return $this->hasOne(Prescription::class);
    }

    public function saleTransactions(): HasMany
    {
        return $this->hasMany(SaleTransaction::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name);
    }

    public function getAgeAttribute(): int
    {
        return $this->birthdate->age;
    }
}
