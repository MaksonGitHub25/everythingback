<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function checkAdminData(Request $request): \Illuminate\Http\JsonResponse
    {
        $admins = DB::table('admins')->get();
        $adminWithReceivedDataExist = false;

        $login = $request->login;
        $password = $request->password;

        for ($i = 0; $i < count($admins); $i++) {
            if ($admins[$i]->login === $login && $admins[$i]->password === $password) {
                $adminWithReceivedDataExist = true;
            }
        }

        return response()->json(['adminWithReceivedDataExist' => $adminWithReceivedDataExist]);
    }
}
