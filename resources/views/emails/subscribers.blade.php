@component('mail::message')
    # Welcome to my newsletter
    Dear {{$email}},
    We look forward to communicating more with you. For more information visit our blog.
    @component('mail::button', ['url' => 'https://karlhill.com/portfolio'])
        Portfolio
    @endcomponent
    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
