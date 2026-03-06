@extends('newsletter.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex justify-center mb-6">
                <img src="{{ config('shared-saas.central.logo_url') }}" alt="Logo" class="h-10">
            </div>
            
            <h2 class="text-2xl font-bold text-center mb-6">{{ __('newsletter.manage_your_subscription') }}</h2>
            
            <div class="mb-6">
                <p class="text-center text-gray-600">{{ __('newsletter.email') }}: <span class="font-semibold">{{ $email }}</span></p>
                <p class="text-center text-gray-600">
                    {{ __('newsletter.status') }}: 
                    <span class="font-semibold {{ $status === 'subscribed' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $status === 'subscribed' ? __('newsletter.subscribed') : __('newsletter.unsubscribed') }}
                    </span>
                </p>
            </div>
            
            @if ($status === 'subscribed')
                <form id="categories-form" class="mb-6">
                    <input type="hidden" name="token" value="{{ $token }}">
                    <h3 class="text-lg font-semibold mb-3">{{ __('newsletter.subscription_categories') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('newsletter.select_categories') }}</p>
                    
                    <div class="flex justify-start space-x-4 mb-3">
                        <button type="button" id="select-all" class="text-sm text-blue-600 hover:text-blue-800">
                            {{ __('newsletter.select_all') }}
                        </button>
                        <button type="button" id="select-none" class="text-sm text-blue-600 hover:text-blue-800">
                            {{ __('newsletter.select_none') }}
                        </button>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        @foreach ($availableCategories as $key => $label)
                            <div class="flex items-center">
                                <input type="checkbox" id="{{ $key }}" name="categories[]" value="{{ $key }}" 
                                    {{ in_array($key, $categories) ? 'checked' : '' }} 
                                    class="h-5 w-5 text-blue-600 category-checkbox">
                                <label for="{{ $key }}" class="ml-2 text-gray-700">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="flex justify-center">
                        <button type="submit" id="update-categories" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mr-4">
                            {{ __('newsletter.update_subscription') }}
                        </button>
                        <button type="button" id="unsubscribe" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                            {{ __('newsletter.unsubscribe') }}
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                    <p class="text-center">{{ __('newsletter.already_unsubscribed') }}</p>
                </div>
                <div class="flex justify-center">
                    <button type="button" id="resubscribe" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        {{ __('newsletter.resubscribe') }}
                    </button>
                </div>
            @endif
            
            <div id="response-message" class="mt-4 hidden">
                <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded hidden"></div>
                <div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded hidden"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 更新订阅类别
        const categoriesForm = document.getElementById('categories-form');
        if (categoriesForm) {
            categoriesForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const token = document.querySelector('input[name="token"]').value;
                const checkboxes = document.querySelectorAll('input[name="categories[]"]:checked');
                const categories = Array.from(checkboxes).map(cb => cb.value);
                
                updateCategories(token, categories);
            });
        }
        
        // 全选按钮
        const selectAllBtn = document.getElementById('select-all');
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function() {
                const checkboxes = document.querySelectorAll('.category-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
            });
        }
        
        // 全不选按钮
        const selectNoneBtn = document.getElementById('select-none');
        if (selectNoneBtn) {
            selectNoneBtn.addEventListener('click', function() {
                const checkboxes = document.querySelectorAll('.category-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            });
        }
        
        // 退订按钮
        const unsubscribeBtn = document.getElementById('unsubscribe');
        if (unsubscribeBtn) {
            unsubscribeBtn.addEventListener('click', function() {
                if (confirm('{{ __('newsletter.confirm_unsubscribe') }}')) {
                    const token = document.querySelector('input[name="token"]').value;
                    unsubscribe(token);
                }
            });
        }
        
        // 重新订阅按钮
        const resubscribeBtn = document.getElementById('resubscribe');
        if (resubscribeBtn) {
            resubscribeBtn.addEventListener('click', function() {
                const token = '{{ $token }}';
                resubscribe(token);
            });
        }
    });
    
    // 更新订阅类别
    function updateCategories(token, categories) {
        fetch('{{ route("api.newsletter.updateCategories") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ token, categories })
        })
        .then(response => response.json())
        .then(data => {
            showMessage(data.message, true);
        })
        .catch(error => {
            showMessage('{{ __('newsletter.update_failed') }}', false);
            console.error('Error:', error);
        });
    }
    
    // 退订
    function unsubscribe(token) {
        fetch('{{ route("api.newsletter.unsubscribe") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ token })
        })
        .then(response => response.json())
        .then(data => {
            showMessage(data.message, true);
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        })
        .catch(error => {
            showMessage('{{ __('newsletter.operation_failed') }}', false);
            console.error('Error:', error);
        });
    }
    
    // 重新订阅
    function resubscribe(token) {
        fetch('{{ route("api.newsletter.resubscribe") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ token })
        })
        .then(response => response.json())
        .then(data => {
            showMessage(data.message, true);
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        })
        .catch(error => {
            showMessage('{{ __('newsletter.operation_failed') }}', false);
            console.error('Error:', error);
        });
    }
    
    // 显示消息
    function showMessage(message, isSuccess) {
        const responseMessage = document.getElementById('response-message');
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');
        
        responseMessage.classList.remove('hidden');
        
        if (isSuccess) {
            successMessage.textContent = message;
            successMessage.classList.remove('hidden');
            errorMessage.classList.add('hidden');
        } else {
            errorMessage.textContent = message;
            errorMessage.classList.remove('hidden');
            successMessage.classList.add('hidden');
        }
        
        // 3秒后隐藏消息
        setTimeout(() => {
            responseMessage.classList.add('hidden');
        }, 3000);
    }
</script>
@endpush