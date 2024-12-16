<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="name", type="string", example="John Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Code sent to your phone."),
     *             @OA\Property(property="code", type="integer", example=123456)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Validation error")
     *         )
     *     )
     * )
     */

    public function register(Request $request)
    {
        $request->validate([
            'phone' => 'required|unique:users',
            'name' => 'required',
        ]);

        $code = rand(100000, 999999); // Генерация кода подтверждения

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'verification_code' => $code,
        ]);

        // Отправка кода пользователю (здесь должна быть интеграция с SMS-сервисом)
        // sendSms($request->phone, $code);

        return response()->json([
            'message' => 'Code sent to your phone.',
            'user' => new UsersResource($user),
            'code' => $code,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Log in a user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="phone", type="string", example="+1234567890", description="Phone number of the user"),
     *             @OA\Property(property="verification_code", type="string", example="123456", description="Verification code sent to the user's phone")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="phone", type="string", example="+1234567890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid credentials")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The phone field is required.")
     *         )
     *     )
     * )
     */

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'verification_code' => 'required',
        ]);

        $user = User::where('phone', $request->phone)
            ->where('verification_code', $request->verification_code)
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = JWTAuth::fromUser($user);
        $user->verification_code = null;
        $user->save();

        return response()->json([
            'token' => $token,
            'name' => $user->name,
            'phone' => $user->phone,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/verify-code",
     *     summary="Verify the user's verification code",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="phone", type="string", example="+1234567890", description="Phone number of the user"),
     *             @OA\Property(property="verification_code", type="string", example="123456", description="Verification code sent to the user's phone")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Code verified successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Code verified.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid verification code",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid code")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The phone field is required.")
     *         )
     *     )
     * )
     */

    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'verification_code' => 'required',
        ]);

        $user = User::where('phone', $request->phone)
            ->where('verification_code', $request->verification_code)
            ->first();

        if ($user) {
            return response()->json(['message' => 'Code verified.']);
        }

        return response()->json(['error' => 'Invalid code'], 401);
    }

}
