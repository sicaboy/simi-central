@extends('newsletter.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex justify-center mb-6">
                <img src="{{ config('shared-saas.central.logo_url') }}" alt="Logo" class="h-10">
            </div>
            
            <h2 class="text-2xl font-bold text-center mb-6">{{ __('newsletter.email_verification') }}</h2>

            <div id="verification-result">
                <!-- Verification status will be loaded here by JavaScript -->
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    <p class="text-center">{{ __('newsletter.verifying') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const token = '{{ $token }}';
    const verificationResultDiv = document.getElementById('verification-result');
    const verifyUrl = '{{ route("api.newsletter.verify") }}';

    // 定义翻译文本 (理想情况下应从后端传递或通过翻译库获取)
    const translations = {
        verification_success: '{{ __("newsletter.verification_success") }}',
        manage_subscription: '{{ __("newsletter.manage_subscription") }}',
        verification_failed: '{{ __("newsletter.verification_failed") }}',
        back_to_home: '{{ __("newsletter.back_to_home") }}',
        verify_api_success: '{{ __("newsletter.verify_api_success", ["message" => ""]) }}', // Placeholder for dynamic message
        verify_api_error: '{{ __("newsletter.verify_api_error", ["message" => ""]) }}' // Placeholder for dynamic message
    };

    if (token) {
        fetch(`${verifyUrl}?token=${token}`)
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                let htmlContent = '';
                if (status === 200) {
                    const successMsg = translations.verify_api_success.replace(':message', body.message || 'Success!');
                    htmlContent = `
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <p class="text-center">${successMsg}</p>
                        </div>
                        <p class="text-center mb-6">${translations.verification_success}</p>
                        <div class="flex justify-center">
                            <a href="{{ route('newsletter.manage', ['token' => $token]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                ${translations.manage_subscription}
                            </a>
                        </div>
                    `;
                } else {
                    const errorMsg = translations.verify_api_error.replace(':message', body.message || 'Invalid token!');
                    htmlContent = `
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <p class="text-center">${errorMsg}</p>
                        </div>
                        <p class="text-center mb-6">${translations.verification_failed}</p>
                        <div class="flex justify-center">
                            <a href="/" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                ${translations.back_to_home}
                            </a>
                        </div>
                    `;
                }
                verificationResultDiv.innerHTML = htmlContent;
            })
            .catch(error => {
                console.error('Error verifying token:', error);
                 const errorMsg = translations.verify_api_error.replace(':message', 'An unexpected error occurred.');
                verificationResultDiv.innerHTML = `
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <p class="text-center">${errorMsg}</p>
                    </div>
                     <p class="text-center mb-6">${translations.verification_failed}</p>
                    <div class="flex justify-center">
                        <a href="/" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            ${translations.back_to_home}
                        </a>
                    </div>
                `;
            });
    } else {
        // Handle case where token is missing (though controller should prevent this view)
        verificationResultDiv.innerHTML = `
             <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                 <p class="text-center">{{ __('newsletter.invalid_link') }}</p>
             </div>
             <div class="flex justify-center">
                 <a href="/" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                     ${translations.back_to_home}
                 </a>
             </div>
        `;
    }
});
</script>
@endpush