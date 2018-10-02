<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quotation extends Model
{
    protected $fillable = [
        'cite', 'customer', 'contact', 'phone', 'address', 'term', 'state', 'user_id',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'total');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saveQuotation($request)
    {
        $data = [
            'cite'     => $request['cite'],
            'customer' => $request['customer'],
            'contact'  => $request['contact'],
            'phone'    => $request['phone'],
            'address'  => $request['address'],
            'term'     => $request['term'],
            'user_id'  => $request['user_id'],
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
