@component('mail::message')
# {{ $title }}

@if(isset($subtitle) && $subtitle)
{{ $subtitle }}
@endif

@foreach($content as $line)
{{ $line }}
@endforeach

@if(isset($ctaText) && $ctaText && isset($ctaUrl) && $ctaUrl)
@component('mail::button', ['url' => $ctaUrl])
{{ $ctaText }}
@endcomponent
@endif

Regards,<br>
{{ config('shared-saas.central.name') }}
@endcomponent
