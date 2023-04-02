<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductsController
{
    public function getData(): \Illuminate\Http\JsonResponse
    {
        $products = DB::table('products')->get();
        return response()->json($products)->header('Content-Type', 'application/json');
    }

    public function sendData(Request $request)
    {
        DB::table('products')->insert([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'photo_id' => $request->input('photo_id'),
            'creator' => $request->input('creator'),
            'price' => $request->input('price'),
        ]);
    }
}
