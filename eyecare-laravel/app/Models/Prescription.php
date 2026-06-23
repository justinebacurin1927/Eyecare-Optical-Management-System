<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'sphere', 'cylinder', 'axis',
        'addition', 'pd', 'frame_id', 'lens_type_id', 'tint',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function frame(): BelongsTo
    {
        return $this->belongsTo(Frame::class);
    }

    public function lensType(): BelongsTo
    {
        return $this->belongsTo(LensType::class);
    }
}
