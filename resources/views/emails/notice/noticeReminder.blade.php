<x-mail::message>
Dear {{$user->fname}} {{$user->lname}},

This is a reminder that the notice titled "<strong>{{$notice->title}}</strong>" was issued on {{$updated_at}} and has been specifically assigned to your attention. To ensure compliance and awareness, we request that you log into the system at your earliest convenience to review the notice.

Thank you
<br>
{{ config('app.name') }}

</x-mail::message>
