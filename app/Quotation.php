<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quotation extends Model
{
    protected $fillable = [
        'cite', 'customer', 'total',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'total');
    }

    public function saveQuotation($request)
    {
        $data = [
            'cite'     => $request['cite'],
            'customer' => $request['customer'],
            'total'    => $request['total'],
        ];
        DB::beginTransaction();
        try {
            $quotation = Quotation::create($data);
            $quotation->products()->attach($request['products']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
        return true;
    }
}
