<?php

namespace App\Http\Controllers\API;

use App\Facades\UserService;
use App\Http\Controllers\Controller;
use App\Http\Helpers\StatusResponse;
use App\Http\Requests\{
    LoginRequest, UserRegisterRequest
};
use App\Http\Resources\UserResource;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Http\{
    Response
};
use Illuminate\Support\Facades\{
    Auth,
    DB,
    Hash
};

class UserController extends Controller
{
    /**
     * POST /api/login
     * Log in existed user
     *
     * @param LoginRequest $request
     *
     * @return Response
     */
    public function login(LoginRequest $request)
    {
        $user = User::whereUsername($request->input('username'))->first();

        DB::beginTransaction();
        try {
            $createdToken = $user->createToken(User::GAME_TOKEN);
            $token = $createdToken->token;
            // Expires time
            $token->expires_at = Carbon::now()->addDay(1);
            $token->save();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create new token.'], 422);
        }

        $success['message'] = 'You are successfully login.';
        $success['token'] = $createdToken->accessToken;
        return response()->json($success, StatusResponse::STATUS_OK);
    }

    /**
     * POST /api/register
     * Registers a new user
     *
     * @param UserRegisterRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterRequest $request)
    {
        $newUser = [
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'email_verified_at' => null
        ];
        $user = new User();
        $user->email = $newUser['email'];
        $user->username = $newUser['username'];
        $user->password = $newUser['password'];
        $user->email_verified_at = $newUser['email_verified_at'];
        $user->save();

        UserService::sendEmailVerificationNotification($user);

        return \response()->json(['data' => UserResource::make(User::find($user->id))], 201);
    }

    /**
     * POST api/logout
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json(['message' => 'logout success'], StatusResponse::STATUS_OK);
        } else {
            return response()->json(['error' => 'Something goes wrong. You cannot logout.'], StatusResponse::STATUS_UNAUTHORIZED);
        }
    }
}
