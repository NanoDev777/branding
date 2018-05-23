<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    protected $fillable = [
        'chilean', 'dollar', 'purchase', 'sale', 'transfer', 'amount', 'transport', 'iva',
    ];
}
