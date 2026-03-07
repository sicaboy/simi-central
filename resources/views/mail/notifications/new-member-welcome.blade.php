@component('mail::message')
**Hi {{ $notifiable->name }},**

Welcome to **{{ config('shared-saas.central.name') }}**.

You are now part of the **{{ $team->name }}** workspace, where your team can manage virtual staging, image enhancement, and related property marketing workflows.

Start by signing in and continuing work inside the workspace.

@component('mail::button', ['url' => config('shared-saas.central.url')])
Get Started
@endcomponent

If you need help getting started, reply to this email and the Simi team will assist you.

Regards,

{{ config('shared-saas.central.name') }}
@endcomponent
