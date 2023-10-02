<?php

namespace App\Http\Controllers;

use App\DAO\UserDAO;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    private $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware(['auth:api'], ['except' => ['login','register']]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = JWTAuth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = auth()->user();

        return response()->json(["user" => $user, "token" => $token]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     @OA\Parameter(
     *          name="first_name",
     *          description="firstname Field",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="last_name",
     *          description="lastname Field",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="middle_name",
     *          description="middle Field",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="email",
     *          description="Email Field",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="password",
     *          description="Password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="password_confirmation",
     *          description="Password confirmation",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Display a credential User."),
     *     @OA\Response(response="201", description="Successful operation"),
     *     @OA\Response(response="400", description="Bad Request"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Forbidden")
     * )
     */
    public function register(Request $request)
    {
       $validated = $request->validate([
            'first_name' => ['required'] ,
            'last_name' => ['required'] ,
            'middle_name' => ['required'] ,
            'email' => ['required', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
            'balance' => ['nullable']
        ]);
       $validated['password'] = Hash::make($validated['password']);
       $user = $this->userService->createUser($validated);
       return response()->json($user, 201);
    }

    public function me()
    {
        return response()->json($this->userService->getAuthUser());
    }

    /**
     * @OA\Post(
     *
     *     path="/api/auth/logout",
     *
     *     security={
     *         {"bearer_token": {}}
     *     },
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         description="JWT",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(response="200", description="Display a credential User."),
     *     @OA\Response(response="201", description="Successful operation"),
     *     @OA\Response(response="400", description="Bad Request"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Forbidden")
     * )
     *
     */
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Successfully logged out'], 204);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     security={
     *         {"bearer_token": {}}
     *     },
     *     @OA\Response(response="200", description="Display a credential User."),
     *     @OA\Response(response="201", description="Successful operation"),
     *     @OA\Response(response="400", description="Bad Request"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Forbidden")
     * )
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}
