@component('mail::message')
Hello, {{$user1->name}}

{{$user2->name}} just shared new file with you: {{$file->users_name}}

@component('mail::button',['url' => route('home')])
Open app
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
