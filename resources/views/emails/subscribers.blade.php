@component('mail::message')

# Thanks for joining my newsletter


Hey {{$email}},

I look forward to communicating more with you in the future. I'll be sending you a newsletter every month with the latest news and updates.
In the meantime please visit my portfolio.


@component('mail::button', ['url' => 'https://karlhill.com/portfolio'])
Portfolio
@endcomponent

Thanks,


{{ config('app.name') }}

<pre>
{{$text}}
</pre>


@endcomponent
