@component('mail::message')
**Hi {{ $notifiable->name }},**

**{{ $user->name }}** ({{ $user->email }}) has joined the **{{ $team->name }}** team.

You can manage team access, permissions, and workspace activity in your {{ config('shared-saas.central.name') }} account.

Regards,<br>

{{ config('shared-saas.central.name') }}
@endcomponent
