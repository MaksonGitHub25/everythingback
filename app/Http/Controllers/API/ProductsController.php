<?php

namespace App\Http\Controllers\API;
use Faker\Core\File;
use http\Env\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function getData(): \Illuminate\Http\JsonResponse
    {
        $products = DB::table('products')->get();
        return response()->json($products)->header('Content-Type', 'application/json');
    }

    public function addNewProduct(Request $request): \Illuminate\Http\JsonResponse
    {
        $file = $request->file('file');
        Storage::disk('local')->put('photos', $file);
        $fileName = $file->hashName();

        $title = $request->input('title');
        $description = $request->input('description');
        $creator = $request->input('creator');
        $price = $request->input('price');


        DB::table('products')->insert([
            'title' => $title,
            'description' => $description,
            'photo_id' => $fileName,
            'creator' => $creator,
            'price' => $price,
        ]);

        return response()->json(['title' => $title, 'description' => $description, 'photo_id' => $fileName, 'creator' => $creator, 'price' => $price])->header('Content-Type', 'application/json');
    }

    public function getImage($filename)
    {
        $file = Storage::get('/photos/' . $filename);
        $type = Storage::mimeType('/photos/' . $filename);

        return response($file, 200)->header('Content-Type', $type);
    }
}
