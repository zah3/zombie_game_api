@component('mail::message')
Hello!

You are receiving this email because we received a password reset request for your account.

If you did not request a password reset, no further action is required.

Your token to reset password is:

{{ $token }}

Regards,
Zombie_Game

@endcomponent