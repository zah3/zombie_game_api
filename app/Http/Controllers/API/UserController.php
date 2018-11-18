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
    LoginRequest, RegisterRequest
};
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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

        $success['message'] = 'success';
        $success['token'] =  $user->createToken('GameToken')-> accessToken;

        return response()->json(['success' => $success], StatusResponse::STATUS_OK);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->all());

        return response()->json(['success' => new UserResource(User::find($user->id))]);
    }


}