<?php

namespace App\Http\Controllers\API;
use Faker\Core\File;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function getData(): \Illuminate\Http\JsonResponse
    {
        // $products = DB::table('products')->get();
        // $response = [];

        // for ($i=0; $i < count($products); $i++) {
        //     $product = $products[$i];
        //     $product->comments = json_decode($product->comments);

        //     array_push($response, $product);
        // }

        return response()->json(['fuck' => 'fuck u'])->header('Content-Type', 'application/json');
    }

    public function getProduct($productId)
    {
        $products = DB::table('products')->get();
        $successFind = false;

        for ($i = 0; $i < count($products); $i++)
        {
            if ($products[$i]->uniqueProductId === $productId) {
                $successFind = true;
                return response()->json(['success' => $successFind, 'data' => $products[$i]]);
            }
        }

        if (!$successFind) {
            return response()->json(['success' => $successFind]);
        }
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
        $uniqueProductId = $request->input('uniqueProductId');
        $comments = $request->input('comments');

        $products = DB::table('products')->get();
        $alreadyHaveThisProduct = false;

        for ($i = 0; $i < count($products); $i++)
        {
            if (
                $products[$i]->title === $title &&
                $products[$i]->description === $description &&
                $products[$i]->price == $price
            ) {
                $alreadyHaveThisProduct = true;
            }
        }

        if ($alreadyHaveThisProduct) {
            return response()->json(
                ['success' => false, 'errorMessage' => 'Product with this credential already exist']
            );
        }

        DB::table('products')->insert([
            'title' => $title,
            'description' => $description,
            'photo_id' => $fileName,
            'creator' => $creator,
            'price' => $price,
            'uniqueProductId' => $uniqueProductId,
            'comments' => $comments,
        ]);

        return response()->json(['success' => true, 'data' => ['title' => $title, 'description' => $description, 'photo_id' => $fileName, 'creator' => $creator, 'price' => $price, 'uniqueProductId' => $uniqueProductId, 'comments' => $comments]]);
    }

    public function getImage($filename)
    {
        $file = Storage::get('/photos/' . $filename);
        $type = Storage::mimeType('/photos/' . $filename);

        return response($file, 200)->header('Content-Type', $type);
    }

    public function deleteProduct($productId): \Illuminate\Http\JsonResponse
    {
        if (DB::table('products')->where('uniqueProductId', $productId)->exists()) {
            DB::table('products')->where('uniqueProductId', $productId)->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'errorMessage' => "Product with this uniqueProductId doesn't exist"]);
    }

    public function addCommentToProduct(Request $request, $productId): \Illuminate\Http\JsonResponse
    {
        if (!DB::table('products')->where('uniqueProductId', $productId)->exists()) {
            return response()->json(['success' => false]);
        }

        $newComment = [
            'name' => $request->name,
        	'date' => $request->date,
            'picture' => $request->picture,
            'text' => $request->text,
            'uniqueCommentId' => $request->uniqueCommentId,
        ];

        $product = DB::table('products')
                    ->where('uniqueProductId', $productId)
                    ->first();

        $allComments = json_decode($product->comments);
        array_push($allComments, $newComment);

        DB::table('products')
            ->where('uniqueProductId', $productId)
            ->update(array('comments' => $allComments));

        return response()->json(['success' => true, 'message' => "Comment was added successful!"]);
    }

    public function deleteComment($commentId): \Illuminate\Http\JsonResponse
    {
        $products = DB::table('products')->get();
        $productWithThisCommentId = '';

        for ($i=0; $i < count($products); $i++) {
            $product = $products[$i];
            $comments = json_decode($product->comments);

            for ($j=0; $j < count($comments); $j++) {
                $comment = $comments[$j];

                if ($comment->uniqueCommentId === $commentId) {
                    $productWithThisCommentId = $product->uniqueProductId;
                }
            }
        }

        if ($productWithThisCommentId === '') {
            return response()->json(['success' => false, 'message' => "Product with this comment or this commentId doesn't exist"]);
        }

        $productWithThisComment = DB::table('products')
            ->where('uniqueProductId', $productWithThisCommentId)
            ->first();

        $newComments = json_decode($productWithThisComment->comments);

        for ($i=0; $i < count($newComments); $i++) {
            $newComment = $newComments[$i];

            if ($newComment->uniqueCommentId === $commentId) {
                array_splice($newComments, $i, 1);
            }
        }

        DB::table('products')
            ->where('uniqueProductId', $productWithThisCommentId)
            ->update(array('comments' => $newComments));

        return response()->json(['success' => true, 'message' => 'Deleted comment with Id '.$commentId]);
    }
}
