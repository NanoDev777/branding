<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Amount extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'quantity', 'price', 'product_id',
    ];

    protected $hidden = [
        'product_id', 'deleted_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
