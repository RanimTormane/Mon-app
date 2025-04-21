<x-mail::message>
# Change password request

Click on the botton below to change password.

<x-mail::button :url="'http://localhost:4200/response-reset-password?token=' . $token">
Reset Password
</x-mail::button>
If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
