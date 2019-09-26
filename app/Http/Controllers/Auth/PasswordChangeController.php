<?php

namespace App\Http\Controllers\Auth;

use App\Entities\Constants\Helpers\ExceptionMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Notifications\PasswordChangeNotification;
use App\Entities\PasswordReset;
use App\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordChangeController extends Controller
{

    /**
     * POST /api/password/change
     * Change password for a user
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
        $user = User::whereEmail($request->email)->firstOrFail();

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