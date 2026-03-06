@component('mail::message')
**Hi {{ $notifiable->name }},**

**{{ $user->name }}** ({{ $user->email }}) has joined the **{{ $team->name }}** team.

You can manage your team members and their roles through the {{ config('shared-saas.central.name') }} admin panel.

Regards,<br>

{{ config('shared-saas.central.name') }}
@endcomponent