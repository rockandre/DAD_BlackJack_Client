@component('mail::message')
# Welcome to BlackJack, {{$user->name}} !!

Please activate your account!

@component('mail::button', ['url' => $url])
Activate account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent