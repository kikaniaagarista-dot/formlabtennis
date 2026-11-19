<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'qris_image',
        'payment_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
