<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'image', 'product_id',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
