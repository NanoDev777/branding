<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
	  protected $fillable = [
	    'code', 'name', 'description', 'size', 'width', 'height', 'thickness', 'weight', 'price', 'category_id'
	  ];

    public function category() {
      return $this->belongsTo(Category::class);
    }

    public function images() {
      return $this->hasMany(Image::class);
    }

    public function packing() {
      return $this->hasOne(Packing::class);
    }

    public function colors() {
      return $this->belongsToMany(Color::class)->withPivot('size');
    }

    public function quotations() {
      return $this->belongsToMany(Quotation::class)->withPivot('quantity');
    }
}
