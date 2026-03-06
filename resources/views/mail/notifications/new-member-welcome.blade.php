@component('mail::message')
**Hi {{ $notifiable->name }},**

Welcome to **{{ config('shared-saas.central.name') }}** – your all-in-one digital platform designed to elevate your local business.

As a member of the {{ $team->name }} team, you now have access to tools that simplify eGift card sales, appointment bookings, service feedback, and online store management.

Start exploring the features and take your business to new heights.

@component('mail::button', ['url' => config('shared-saas.central.url')])
Get Started
@endcomponent

If you have any questions or need assistance, please don't hesitate to reach out to our support team.

Regards,

{{ config('shared-saas.central.name') }}
@endcomponent