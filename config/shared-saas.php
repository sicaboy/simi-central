<?php

use Sicaboy\SharedSaas\Models\Central\Tenant;
use Sicaboy\SharedSaas\SharedSaas;

return [
    'mode' => SharedSaas::MODE_CENTRAL,
    'commercial' => [
        'url' => env('COMMERCIAL_URL', 'https://www.simi.com.au'),
    ],
    'central' => [
        'models' => [
            'user' => \App\Models\Central\User::class,
            'team' => \App\Models\Central\Team::class,
            'membership' => \App\Models\Central\Membership::class,
            'team_invitation' => \App\Models\Central\TeamInvitation::class,
        ],
        'policies' => [
            'team' => \Sicaboy\SharedSaas\Policies\TeamPolicy::class,
        ],
        'name' => 'Simi',
        'url' => env('CENTRAL_URL', 'https://app.simi.com.au'),
        'naked_domain' => env('CENTRAL_NAKED_DOMAIN', 'simi.com.au'),
        'custom_domain_fallback_origin' => env('CUSTOM_DOMAIN_FALLBACK_ORIGIN', 'custom.simi.com.au'),
        'tenant_list_url' => env('CENTRAL_TENANT_LIST_URL', 'https://app.simi.com.au/stores'),
        'subscription_url' => env('CENTRAL_SUBSCRIPTION_URL', 'https://app.simi.com.au/subscriptions/{id}'),
        'account_url' => env('CENTRAL_ACCOUNT_URL', 'https://app.simi.com.au/account'),
        'logout_url' => env('CENTRAL_LOGOUT_URL', 'https://app.simi.com.au/logout'),
        'team_url' => env('CENTRAL_TEAM_URL', 'https://app.simi.com.au/teams/{id}'),
        'logo_url' => env('CENTRAL_LOGO_URL', 'https://app.simi.com.au/img/logo.png'),
        'logo_url_dark' => env('CENTRAL_LOGO_URL_DARK', 'https://app.simi.com.au/img/logo-dark.png'),
        'logo_url_grey' => env('CENTRAL_LOGO_URL_GREY', 'https://app.simi.com.au/img/logo-grey.png'),
        'waitlist_mode' => env('CENTRAL_WAITLIST_MODE', false),
    ],
    'tenant' => [
        // e.g. config('app.name') will return value from db column Tenant::NAME
        'storage_to_config_map' => [
            Tenant::NAME => ['app.name', 'mail.from.name'],
            Tenant::NO_REPLY_EMAIL => 'mail.from.address',
            Tenant::SUPPORT_EMAIL => 'mail.support.address',
            Tenant::APP_KEY => 'app.key',
            //        Tenant::GOOGLE_CLIENT_ID => 'services.google.client_id',
            //        Tenant::GOOGLE_CLIENT_SECRET => 'services.google.client_secret',
            //        Tenant::GOOGLE_REDIRECT => 'services.google.redirect',
            //        Tenant::FACEBOOK_CLIENT_ID => 'services.facebook.client_id',
            //        Tenant::FACEBOOK_CLIENT_SECRET => 'services.facebook.client_secret',
            //        Tenant::FACEBOOK_REDIRECT => 'services.facebook.redirect',
            //        Tenant::GITHUB_CLIENT_ID => 'services.github.client_id',
            //        Tenant::GITHUB_CLIENT_SECRET => 'services.github.client_secret',
            //        Tenant::GITHUB_REDIRECT => 'services.github.redirect',
            //        Tenant::PASSPORT_PUBLIC_KEY => 'passport.public_key',
            //        Tenant::PASSPORT_PRIVATE_KEY => 'passport.private_key',
        ],
    ],
];
