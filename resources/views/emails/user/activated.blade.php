<x-mail::message>
Hi {{ $user->fname }}

Your account has now been activated on the {{ config('app.name') }} website.

You can  login using the following details:

**Username:** {{ $user->email }}

**Password:** --As sent previously--

To authorize your work, you will need to enter your unique user code, as shown below.
**Code:** {{ $code }}


Thanks,<br>
{{ config('app.name') }}

<x-mail::button :url="$loginUrl">
Login
</x-mail::button>

</x-mail::message>
