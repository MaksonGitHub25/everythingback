<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\SuccessSignUp;
use Illuminate\Support\Facades\Request;

class MailController extends Controller
{
    public function sendSuccessSignUpEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        Mail::to('pozitivmaks541@gmail.com')->send(new SuccessSignUp());

        return response()->json(['success' => true]);
    }
}
