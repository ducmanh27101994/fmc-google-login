<?php

namespace FmcExample\GoogleLogin\Http\Controllers;

use App\Http\Controllers\Controller;
use FmcExample\GoogleLogin\Models\User;
use FmcExample\GoogleLogin\services\GoogleAccessTokenValidator;
use Illuminate\Http\Request;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Exception;


class AuthController extends Controller
{
    protected $googleAccessTokenValidator;

    public function __construct(GoogleAccessTokenValidator $googleAccessTokenValidator)
    {
        $this->googleAccessTokenValidator = $googleAccessTokenValidator;
    }

    public function handleGoogleLogin(Request $request)
    {
        $accessToken = $request->input('token');
        if (empty($accessToken)) {
            return response()->json([
                'message' => 'Thiếu thông tin token người dùng',
                'status' => 400,
            ]);
        }

        try {
            $userInfoGoogle = $this->googleAccessTokenValidator->validateAndGetUserInfo($accessToken);
        } catch (Exception $exception) {
            $error = $exception->getMessage();
            return response()->json([
                'message' => $error,
                'status' => 400,
            ]);
        }

        $user = User::where('email', $userInfoGoogle['email'])->first();

        if (!$user) {
            $user = User::create([
                'name' => $userInfoGoogle['name'],
                'email' => $userInfoGoogle['email'],
                'password' => Hash::make(Faker::create()->password()),
                'google_id' => $userInfoGoogle['google_id'],
                'picture' => $userInfoGoogle['picture']
            ]);
        }

        try {
            $token = $user->createToken('google_token')->plainTextToken;
        } catch (Exception $exception) {
            $error = $exception->getMessage();
            return response()->json([
                'message' => $error,
                'status' => 400,
            ]);
        }

        return response()->json([
            'message' => "Đăng nhập thành công",
            'status' => 200,
            'token' => $token
        ]);
    }

    function example()
    {
        echo 'success';
    }
}
