<?php

namespace FmcExample\GoogleLogin\Http\Controllers;

use App\Http\Controllers\Controller;
use Faker\Factory as Faker;
use FmcExample\GoogleLogin\Models\User;
use FmcExample\GoogleLogin\services\JwtService;
use Laravel\Socialite\Facades\Socialite;


class GoogleLoginController extends Controller
{
    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function googleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Faker::create()->password(),
                    'google_id' => $googleUser->getId(),
                ]);
            }

            $userArray = $user->toArray();
            $token = $this->jwtService->createToken($userArray);
            return response()->json([
                'message' => "Đăng nhập thành công",
                'status' => 200,
                'token' => $token
            ]);

        } catch (\Exception $exception) {
            $error = $exception->getMessage();
            return response()->json([
                'message' => $error,
                'status' => 400,
            ]);
        }
    }
}
