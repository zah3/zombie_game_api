<?php
/**
 * Created by PhpStorm.
 * User: zachariasz
 * Date: 2019-05-11
 * Time: 08:35
 */

namespace App\Http\Controllers\Auth;


use App\Entities\Constants\Helpers\ExceptionMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Notifications\PasswordChangeNotification;
use App\PasswordReset;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordChangeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:10,1')->only('store');
    }

    /**
     * POST /api/password/reset
     * Resets password
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email|email|max:255',
            'password' => 'required|min:8|max:20',
            'confirm_password' => 'required|same:password',
            'token' => 'required|string:255',
        ]);
        $user = User::whereEmail($request->email)->first();

        abort_if(
            $user === null,
            404,
            PasswordChangeNotification::MESSAGE_ERROR_CANNOT_FIND_EMAIL
        );

        $passwordReset = $user->passwordReset;

        abort_if(
            $passwordReset === null,
            422,
            PasswordChangeNotification::MESSAGE_ERROR_USER_NOT_HAVE_GENERATED_TOKEN
        );

        abort_if(
            !Hash::check($request->input('token'), $passwordReset->token),
            422,
            PasswordChangeNotification::MESSAGE_ERROR_INVALID_TOKEN
        );

        abort_if(
            $passwordReset->created_at->diffInMinutes(now()) >= PasswordReset::LIMIT_IN_MINUTES,
            422,
            ExceptionMessage::PASSWORD_RESET_TOKEN_CREATED_MORE_THEN_LIMIT
        );

        $user->password = Hash::make($request->input('password'));
        $user->save();
        $user->passwordReset()->delete();
        $user->notify(new PasswordChangeNotification());
        return response()->json(UserResource::make($user), 201);
    }
}