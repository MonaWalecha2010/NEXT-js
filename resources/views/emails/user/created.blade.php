<x-mail::message>
Hi {{ $user->fname }}

A new account has been created for you on {{ config('app.name') }} website.

@if( !$user->active )
Please keep this email until you receive your account activation email.
@endif

You can  login using the following details:

**Username:** {{ $user->email }}

**Password:** {{ $password }}

To authorize your work ,you will need to enter your unique user code, as shown below.
**Code:** {{ $code }}


Thanks,<br>
{{ config('app.name') }}

<x-mail::button :url="$loginUrl">
Login
</x-mail::button>

</x-mail::message>
