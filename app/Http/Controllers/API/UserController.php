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
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\{
    Auth,
    DB,
    Hash
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
        $user = User::where('username','=',$request->input('username'))->first();
        DB::beginTransaction();
        try {
            $createdToken = $user->createToken(User::GAME_TOKEN);
            $token = $createdToken->token;
            //expires time
            $token->expires_at = Carbon::now()->addDay(1);
            $token->save();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create new token'],StatusResponse::STATUS_BAD_REQUEST);
        }
        
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
        $newUser = [
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'is_active' => false
        ];
        $user = User::create($newUser);
        return response()->json(['data' => new UserResource(User::find($user->id))]);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(){
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json(['message' =>'logout success'],StatusResponse::STATUS_OK);
        } else {
            return response()->json(['error' =>'Something goes wrong. You cannot logout.'], StatusResponse::STATUS_UNAUTHORIZED);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        return response()->json( new UserCollection(User::with(['roles'])->all()), StatusResponse::STATUS_OK);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->authorize('store', User::class);

        return response()->json(['message'=> 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     */
    public function show(User $user)
    {
        $this->authorize('show', $user);

        return response()->json(['message'=> 'success']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $user
     * @return array
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        return response()->json(['message'=> 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return array
     */
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);

        return response()->json(['message'=> 'success']);
    }

    public function admins()
    {
        $users = UserCollection::make(User::with('roles')->admins()->get());
        return response()->json($users);
    }
}
