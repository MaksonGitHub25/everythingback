<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class CustomersController extends Controller
{
    public function __construct()
    {
        $this->key = "qXsi4JxzLr7fbghhi4b-1_AOKA5Y1vbzNbMXugwjQbc";
    }

    public function getData(): \Illuminate\Http\JsonResponse
    {
        $customers = DB::table('customers')->get();
        return response()->json($customers)->header('Content-Type', 'application/json');
    }

    public function saveUserData(Request $request)
    {
        $name = $request->input('name');
        $age = $request->input('age');
        $email = $request->input('email');
        $login = $request->input('login');
        $password = $request->input('password');

        DB::table('customers')->insert([
            'name' => $name,
            'age' => $age,
            'email' => $email,
            'login' => $login,
            'password' => $password,
        ]);
    }

    public function registerUser(Request $request)
    {
        $name = $request->input('name');
        $age = $request->input('age');
        $email = $request->input('email');
        $login = $request->input('login');
        $password = $request->input('password');

        $payload = [
            'name' => $name,
            'age' => $age,
            'email' => $email,
            'login' => $login,
            'password' => $password
        ];

        $jwt = JWT::encode($payload, $this->key, 'HS256');

        return response()->json(['jwtToken' => $jwt]);
    }

    public function loginUserByJWT(Request $request)
    {
        $jwt_string = $request->input('jwtToken');
        $decoded = JWT::decode($jwt_string, new Key($this->key, 'HS256'));

        return response()->json(['data' => $decoded]);
    }

    public function loginUserByUserData(Request $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');

        $customers = DB::table('customers')->get();
        $customers = json_decode(json_encode($customers));

        $foundNeededUser = false;
        for ($i = 0; $i < count($customers); $i++)
        {
            if ($customers[$i]->login === $login and $customers[$i]->password === $password) {
                $foundNeededUser = true;
                return response()->json(['userData' => $customers[$i]]);
            }
        }

        if (! $foundNeededUser) {
            return response()->json(['error' => "User haven't already registered!"]);
        }
    }
}
