@component('mail::message')
# Confirm your Simi updates

You are receiving this email because you signed up for Simi updates. Confirm your subscription to receive product updates, launch news, and practical tips for virtual staging and property image workflows.

@component('mail::button', ['url' => $url])
Confirm Subscription
@endcomponent

If you did not request this, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
