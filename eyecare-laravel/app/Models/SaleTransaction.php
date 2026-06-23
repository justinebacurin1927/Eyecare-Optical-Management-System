<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'transaction_date', 'total_amount',
        'discount_amount', 'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'datetime',
            'total_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }
}
