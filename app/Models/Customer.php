<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\IdentificationType;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'identification_type',
        'identification_number',
        'name',
        'trade_name',
        'email',
        'phone',
        'mobile_phone',
        'country',
        'province',
        'city',
        'address',
    ];

    protected $casts = [
        'identification_type' => IdentificationType::class,
    ];

    protected $appends = ['identification_type_label'];

    /**
     * Accessor para obtener el label del tipo de identificación.
     */
    public function getIdentificationTypeLabelAttribute(): string
    {
        return $this->identification_type?->label();
    }
}
