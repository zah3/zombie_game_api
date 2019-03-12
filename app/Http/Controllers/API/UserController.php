<?php
/**
 * Created by PhpStorm.
 * @author  zachariasz
 * Date: 2018-10-28
 * Time: 16:46
 */

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers\StatusResponse;
use App\Http\Requests\{
    LoginRequest, UserRegisterRequest, UpdateUserRequest
};
use App\Http\Resources\UserResource;
use App\User;
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
        $user = User::withUsername($request->input('username'))->first();

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
            return response()->json(['message' => 'Failed to create new token.'], StatusResponse::STATUS_BAD_REQUEST);
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
     * @return UserResource
     */
    public function register(UserRegisterRequest $request)
    {
        $newUser = [
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'is_active' => false
        ];
        $user = new User();
        $user->username = $newUser['username'];
        $user->password = $newUser['password'];
        $user->is_active = $newUser['is_active'];
        $user->save();

        return UserResource::make(User::find($user->id));
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
        }    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        return response()->json(UserResource::collection(User::with(['roles'])->get()), StatusResponse::STATUS_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param UserRegisterRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(UserRegisterRequest $request)
    {
        $this->authorize('create', User::class);
        $user = User::create(
            [
                'username' => $request->input('username'),
                'password' => Hash::make($request->input('password')),
                'is_active' => false,
            ]
        );

        return response()->json(['message' => 'success', 'data' => new UserResource($user)]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(User $user)
    {
        $this->authorize('read', $user);

        return response()->json(UserResource::make($user));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        if ($request->input('username') !== $user->username) {
            $user->username = $request->input('username');
            $user->save();
            return response()->json(['message' => 'Updated']);
        } else {
            return response()->json(['error' => 'nothing has changed']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response(null, 204);
    }

}
