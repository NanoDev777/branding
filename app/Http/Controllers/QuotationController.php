<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quotation;

class QuotationController extends Controller
{
	protected $quotations;

    public function __construct(Quotation $quotations){
    	$this->quotations = $quotations;
    }

    public function index() {
       $data = $this->quotations->getQuotations();
       return response()->json(["data" => $data], 200);
    }

    public function show($id) {

    }

    public function store(Request $request) {
    	$data = $this->quotations->saveQuotation($request);
    	if ($data) {
    		return response()->json(['data' => 'todo bien!'], 200);
    	} else {
    		return response()->json(['data' => 'todo mal!'], 500);
    	}
    }

    public function update(Request $request) {

    }

}
