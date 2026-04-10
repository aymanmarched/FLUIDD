<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'client_id',
        'reference',
        'kind',
        'amount_original',
        'discount_percent',
        'amount_paid',
        'status',
        'paid_at',
    ];
}
