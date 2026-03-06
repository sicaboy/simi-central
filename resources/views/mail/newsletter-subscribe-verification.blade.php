@component('mail::message')
# Confirm your subscription

You are receiving this email because you have subscribed to our newsletter. Please click the button below to confirm your subscription.

@component('mail::button', ['url' => $url])
Confirm Subscription
@endcomponent

If you did not subscribe to our newsletter, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
