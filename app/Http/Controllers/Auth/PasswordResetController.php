<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-05-11
 * Time: 08:35
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetSuccess;
use App\PasswordReset;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:6,1')->only('index');
    }

    /**
     * Resets password
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required | string | email',
            'password' => 'required | string | confirmed',
            'token' => 'required | string'
        ]);
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        abort_if(
            $passwordReset === null,
            404,
            PasswordResetSuccess::MESSAGE_ERROR_INVALID_TOKEN
        );

        $user = User::withEmail($request->email)->first();

        abort_if(
            $user === null,
            404,
            PasswordResetSuccess::MESSAGE_ERROR_CANNOT_FIND_EMAIL
        );

        $user->password = Hash::make($request->input('password'));
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess());
        return response()->json($user);
    }
}