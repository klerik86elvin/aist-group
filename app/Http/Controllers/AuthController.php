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
        $this->middleware('auth:api', ['except' => ['login','register']]);
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

        $user = Auth::user();
        try {
            $userService = app(UserService::class);
            // Используйте $userService
        } catch (\Exception $e) {
            // Обработка исключения
            dd($e->getMessage()); // Вывод сообщения об ошибке
        }
        return response()->json(["user" => $this->userService->getUserById($user->id), "token" => $token]);
    }
    public function register(Request $request)
    {
       $validated = $request->validate([
            'first_name' => ['required'] ,
            'last_name' => ['required'] ,
            'middle_name' => ['required'] ,
            'email' => ['required', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);
       $validated['password'] = Hash::make($validated['password']);
       $user = $this->userService->createUser($validated);
       return response()->json($user, 201);
    }
    public function me()
    {
        return response()->json($this->userService->getAuthUser());
    }
    public function getTickets()
    {
        return $this->userService->getTickets();
    }
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Successfully logged out'],204);
    }
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
