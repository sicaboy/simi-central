<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Sicaboy\SharedSaas\Services\LocaleService;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        /** @var LocaleService $localeService */
        $localeService = app()->make(\Sicaboy\SharedSaas\Services\LocaleService::class);

        return array_merge(parent::share($request), [
            'theme' => config('theme'),
            'central' => config('shared-saas.central'),
            'locale' => app()->getLocale(),
            'available_locales' => $localeService->getAvailableLocales(),
        ]);
    }
}
