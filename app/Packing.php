<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Packing extends Model
{
    protected $fillable = [
        'width', 'height', 'thickness', 'weight', 'box', 'product_id',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
