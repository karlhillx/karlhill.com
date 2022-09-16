@component('mail::message')
# Welcome to my newsletter

Dear {{$email}},<br><br>

I look forward to communicating more with you in the future. In the meantime please visit my portfolio for updates.

@component('mail::button', ['url' => 'https://karlhill.com/portfolio'])
Portfolio
@endcomponent

Thanks,<br><br>

{{ config('app.name') }}
@endcomponent
