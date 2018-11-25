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
    LoginRequest,
    RegisterRequest
};
use App\Http\Resources\UserResource;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\{
    Auth,
    DB
};

class UserController extends Controller{

    /**
     * Login api.
     *
     * @param LoginRequest $request
     * @return Response
     */
    public function login(LoginRequest $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $createdToken = $user->createToken(User::GAME_TOKEN);
            $token = $createdToken->token;
            //expires time
            $token->expires_at = Carbon::now()->addDay(1);
            $token->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create new token'],StatusResponse::STATUS_BAD_REQUEST);
        }
        DB::commit();

        $success['message'] = 'You are successfully login.';
        $success['token'] = $createdToken->accessToken;
        return response()->json($success, StatusResponse::STATUS_OK);
    }

    /**
     * Route for register user
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->all());

        return response()->json(['data' => new UserResource(User::find($user->id))]);
    }
}
