@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('shared-saas.central.url')])
            {{ config('shared-saas.central.name') }}
        @endcomponent
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
            © {{ date('Y') }} {{ config('shared-saas.central.name') }}. @lang('All rights reserved.')

            {{ config('shared-saas.central.name') }} - AI virtual staging and property image workflows for modern real estate teams.
        @endcomponent
    @endslot
@endcomponent
