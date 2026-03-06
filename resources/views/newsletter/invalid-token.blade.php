@extends('newsletter.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex justify-center mb-6">
                <img src="{{ config('shared-saas.central.logo_url') }}" alt="Logo" class="h-10">
            </div>
            
            <h2 class="text-2xl font-bold text-center mb-6">{{ __('newsletter.invalid_link_title') }}</h2>
            
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <p class="text-center">{{ __('newsletter.link_expired') }}</p>
            </div>
            
            <p class="text-center mb-6">{{ __('newsletter.subscription_not_found') }}</p>
            
            <div class="flex justify-center">
                <a href="/" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    {{ __('newsletter.back_to_home') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection