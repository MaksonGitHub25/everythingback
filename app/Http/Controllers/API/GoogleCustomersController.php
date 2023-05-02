<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class GoogleCustomersController extends Controller
{
    public function __construct()
    {
        $this->key = "qXsi4JxzLr7fbghhi4b-1_AOKA5Y1vbzNbMXugwjQbc";
    }

    public function getData(): \Illuminate\Http\JsonResponse
    {
        $googleCustomers = DB::table('google_customers')->get();
        return response()->json($googleCustomers)->header('Content-Type', 'application/json');
    }

    public function register(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $email = $request->email;
        $picture = $request->picture;

        $googleCustomers = DB::table('google_customers')->get();

        $foundUserWithThisData = false;
        foreach ($googleCustomers as $customer) {
            if ($customer->id === $id) {
                $foundUserWithThisData = true;
            }
        }

        if ($foundUserWithThisData) {
            return response()->json(['data' => ['success' => false, 'errorMessage' => 'This user is already registered']]);
        }


        DB::table('google_customers')->insert([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'picture' => $picture,
        ]);

        $payload = [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'picture' => $picture,
        ];

        $jwt = JWT::encode($payload, $this->key, 'HS256');

        return response()->json(['data' => ['success' => true, 'isGoogleUser' => true, 'jwtToken' => $jwt]]);
    }

    public function login(Request $request)
    {
        $id = $request->id;

        $googleCustomers = DB::table('google_customers')->get();

        $userData;
        foreach ($googleCustomers as $customer) {
            if ($customer->id === $id) {
                $userData = $customer;
            }
        }

        if (empty($userData)) {
            return response()->json(['errorMessage' => 'User is not signed up']);
        }

        $payload = [
            'id' => $userData->id,
            'name' => $userData->name,
            'email' => $userData->email,
            'picture' => $userData->picture,
        ];

        $jwt = JWT::encode($payload, $this->key, 'HS256');

        return response()->json(['data' => ['isGoogleUser' => true, 'jwtToken' => $jwt]]);
    }

    public function loginByJWT(Request $request)
    {
        $jwt_string = $request->input('googleJWTToken');
        $decoded = JWT::decode($jwt_string, new Key($this->key, 'HS256'));

        return response()->json(['data' => $decoded]);
    }
}
