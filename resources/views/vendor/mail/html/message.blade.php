@component('mail::layout')
{{-- Header --}}
@slot('header')
    <tr>
        <td class="header">
            <a href="{{ route('home') }}" style="display: inline-block;">
                @if(config('shared-saas.central.logo_url'))
                    <img src="{{ config('shared-saas.central.logo_url') }}"
                         alt="{{ config('shared-saas.central.name') }}"
                         style="max-height: 50px; width: auto;">
                @else
                    {{ config('shared-saas.central.name') }}
                @endif
            </a>
        </td>
    </tr>
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
Powered by <a href="{{ config('shared-saas.central.url') }}">{{ config('shared-saas.central.name') }}</a>
@endcomponent
@endslot
@endcomponent
