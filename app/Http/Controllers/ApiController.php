<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CustomerRequest;

class ApiController extends Controller
{
    //
    public function store(CustomerRequest $request) {
        Customer::create([
            'company' => $request->company,
            'description' => $request->description,
            'name_customer' => $request->name_customer,
        ]);
        return response()->json([
            'status' => 200,
            'message' => 'customer creado correctamente',
        ], 200);
    }
}
