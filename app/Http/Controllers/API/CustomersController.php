<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function getData(): \Illuminate\Http\JsonResponse
    {
        $customers = DB::table('customers')->get();
        return response()->json($customers)->header('Content-Type', 'application/json');
    }

    public function sendData(Request $request)
    {
        DB::table('customers')->insert([
            'name' => $request->input('name'),
            'age' => $request->input('age'),
            'email' => $request->input('email'),
            'login' => $request->input('login'),
            'password' => $request->input('password'),
        ]);
    }
}
