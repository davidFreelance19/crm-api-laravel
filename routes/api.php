<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Models\Customer;
use App\Models\User;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->put('/user/{user}', function (Request $request, $id) {
    $user = User::find($id);
    if ($user->id !== +$id)
        return response()->json([
            'message' => 'Accion no valida',
        ], 403);
    if (!Hash::check($request->password, $user->getAuthPassword()))
        return response()->json([
            'message' => 'Password incorrecto',
        ], 401);
    $user->name = $request->name;
    $user->email = $request->email;
    if (empty($request->new_password)) {
        // No se proporcionó una nueva contraseña, mantener la contraseña actual
        $user->password = Hash::make($request->password);
    } else {
        // Se proporcionó una nueva contraseña, usarla para actualizar
        $user->password = Hash::make($request->new_password);
    }
    $user->save();
    return response()->json([
        'user' => $user
    ], 200);
});
Route::post('/create', [AuthController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'loginUser']);

Route::middleware('auth:sanctum')->post('/customer/create', function (Request $request) {
    $customer = Customer::create([
        'company' => $request->company,
        'description' => $request->description,
        'status' => $request->status,
        'name_customer' => $request->name_customer,
        'user_id' => $request->user()->id
    ]);
    return response()->json([
        'message' => 'customer created success',
        'customer' => $customer
    ], 200);
});
Route::middleware('auth:sanctum')->get('/customer/list', function (Request $request) {
    return $request->user()->customers;
});
Route::middleware('auth:sanctum')->delete('/customer/{customer}', function (Request $request, $id) {
    $customer = Customer::find($id);
    $user =  $request->user()->id;
    if ($user !== $customer->user_id) return response()->json([
        'message' => 'Accion no valida',
    ], 403);
    $customer->delete();
    return response()->json([
        'message' => 'Customer successfully removed',
    ], 200);
});

Route::middleware('auth:sanctum')->put('/customer/{customer}', function (Request $request, $id) {
    $customer = Customer::find($id);
    $user =  $request->user()->id;
    if ($user !== $customer->user_id) return response()->json([
        'message' => 'Accion no valida',
    ], 403);
    $customer->name_customer = $request->name_customer;
    $customer->company = $request->company;
    $customer->description = $request->description;
    $customer->status = $request->status;
    $customer->save();
    return response()->json([
        'message' => 'Customer successfully updated',
        'customer' => $customer,
    ], 200);
});
