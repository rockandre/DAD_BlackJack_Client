@component('mail::message')
User {{$user->name}}, you have been blocked!

and the reasson is {{$user->reason_blocked}}

@endcomponent