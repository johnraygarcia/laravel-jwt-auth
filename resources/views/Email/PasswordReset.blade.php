@component('mail::message')
# Change Password Request

Click the button below to change your password.

@component('mail::button', ['url' => 'http://localhost:4200/set-new-password?token=' . $token])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
