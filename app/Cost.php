<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cost extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'chilean', 'dollar', 'purchase', 'sale', 'transfer', 'amount', 'transport', 'iva',
    ];

    protected $hidden = [
        'deleted_at',
    ];
}
