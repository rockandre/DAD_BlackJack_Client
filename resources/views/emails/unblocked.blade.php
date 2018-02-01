@component('mail::message')
User {{$user->name}}, you have been unblocked!

and the reason is {{$user->reason_reactivated}}

@endcomponent