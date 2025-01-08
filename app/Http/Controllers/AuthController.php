<?php

namespace App\Http\Controllers;

use App\Http\Resources\UsersResource;
use App\Models\RoleUserModel;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
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
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Code sent to your phone."),
     *             @OA\Property(property="code", type="integer", example=123456),
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/UserResource")
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
            'password' => 'required',
        ]);

        $code = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'verification_code' => $code,
        ]);

        RoleUserModel::create([
            'user_id' => $user->id,
            'role_id' => 2,
        ]);
        // Отправка кода пользователю (здесь должна быть интеграция с SMS-сервисом)
        // sendSms($request->phone, $code);

        return response()->json([
            'message' => 'Code sent to your phone.',
            'user' => new UsersResource($user),
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
     *             @OA\Property(property="password", type="string", example="password123", description="Password of the user")
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
            'password' => 'required',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Если пароль верный, создаём токен
        $token = JWTAuth::fromUser($user);

        // Очистка verification_code, если необходимо
        $user->verification_code = null;
        $user->save();

        return response()->json([
            'token' => $token,
            'user' => new UsersResource($user),
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
            $user->verification_code = null;
            $user->save();
            return response()->json(['message' => 'Code verified.']);
        }

        return response()->json(['error' => 'Invalid code'], 401);
    }

}
