<x-mail::message>
User : {{$user->fname}} {{$user->lname}}
<br>
Task : {{$task->title}}
<br>
Notification : {{$notification->title}}
<br>
Message : {{$message}}



Thanks,<br>
{{ config('app.name') }}

<x-mail::button :url="$loginUrl">
Login
</x-mail::button>
</x-mail::message>
