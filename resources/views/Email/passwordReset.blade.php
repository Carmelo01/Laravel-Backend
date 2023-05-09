<x-mail::message>
# Change Password Request

Click on the button below to change password.

<!--<x-mail::button :url="'http://localhost:4200/faculty/change/password?token='.$token">-->
<x-mail::button :url="'https://4kmyst.bjmpbaliwag.com/faculty/change/password?token='.$token">
Reset Password
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
