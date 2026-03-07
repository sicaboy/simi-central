<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Sicaboy\SharedSaas\Actions\Central\CreateTenantAction;
use Sicaboy\SharedSaas\Controllers\Central\StoreController as BaseStoreController;
use Sicaboy\SharedSaas\Models\Central\Domain;
use Sicaboy\SharedSaas\Models\Central\ReservedDomain;
use Sicaboy\SharedSaas\Models\Central\Tenant;
use Sicaboy\SharedSaas\Models\Central\User;
use Sicaboy\SharedSaas\Rules\DomainIsValid;

class StoreController extends BaseStoreController
{
    private const BUSINESS_SEARCH_URL = 'https://biz-search.slj.me/api/search';

    private const BUSINESS_DETAIL_URL = 'https://biz-search.slj.me/api/business';

    public function getList()
    {
        /** @var User $user */
        $user = auth()->user();
        $teams = $user->allTeams();
        if ($teams->count() === 0) {
            return redirect()->route('store.create');
        }

        $stores = [];
        foreach ($teams as $team) {
            if (! $team->tenant) {
                continue;
            }

            /** @var Tenant $tenant */
            $tenant = $team->tenant;
            $businessName = data_get($tenant->data, 'business_name');
            $businessNumber = data_get($tenant->data, 'business_number');

            $descriptionParts = array_values(array_filter([
                $businessName,
                $businessNumber,
                $tenant->primaryDomain()?->getFullDomain(),
            ]));

            $stores[] = [
                'title' => $tenant->name,
                'description' => implode(' • ', $descriptionParts),
                'imageUrl' => ! empty($tenant->{Tenant::FAVICON_URL})
                    ? $tenant->{Tenant::FAVICON_URL}
                    : (! empty($tenant->{Tenant::LOGO_URL})
                        ? $tenant->{Tenant::LOGO_URL}
                        : null),
                'url' => route('store.preparing', ['tenantId' => $tenant->id]),
            ];
        }

        return Inertia::render('Stores/List', compact('stores'));
    }

    public function getCreate()
    {
        if (config('shared-saas.central.waitlist_mode')) {
            return Inertia::location(route('waitlist'));
        }

        /** @var User $user */
        $user = auth()->user();
        $showBackButton = $user->allTeams()->count() > 0;

        return Inertia::render('Stores/Create', compact('showBackButton'));
    }

    public function postCreate(Request $request)
    {
        $nameToDomainNormalizer = DomainIsValid::nameToDomainNormalizer();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'business_number' => ['required', 'string', 'max:32'],
            'business_number_type' => ['nullable', 'string', 'max:16'],
            'business_id' => ['nullable', 'string', 'max:32'],
            'entity_type' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:16'],
            'postcode' => ['nullable', 'string', 'max:16'],
            'suburb' => ['nullable', 'string', 'max:255'],
            'subdomain' => [
                'required',
                'string',
                'alpha_dash',
                'min:4',
                'max:255',
                new DomainIsValid($nameToDomainNormalizer),
            ],
        ]);

        $subdomain = $nameToDomainNormalizer($validated['subdomain']);

        $tenant = (new CreateTenantAction)(
            data: [
                'name' => $validated['name'],
            ],
            subdomain: $subdomain,
        );

        $tenant->business_name = $validated['business_name'];
        $tenant->business_number = $validated['business_number'];
        $tenant->business_number_type = $validated['business_number_type'] ?: null;
        $tenant->business_id = $validated['business_id'] ?: null;
        $tenant->entity_type = $validated['entity_type'] ?: null;
        $tenant->state = $validated['state'] ?: null;
        $tenant->postcode = $validated['postcode'] ?: null;
        $tenant->suburb = $validated['suburb'] ?: null;
        $tenant->save();

        /** @var User $user */
        $user = auth()->user();
        $createTeam = new \Sicaboy\SharedSaas\Actions\Central\CreateTeam;
        $createTeam->createWithTenant($user, $tenant->name, $tenant->id);

        return Inertia::location(route('store.preparing', ['tenantId' => $tenant->id]));
    }

    public function searchBusiness(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:255'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $response = Http::timeout(10)->acceptJson()->get(self::BUSINESS_SEARCH_URL, [
            'q' => $validated['q'],
            'limit' => $validated['limit'] ?? 6,
        ]);

        if ($response->failed()) {
            return response()->json([
                'ok' => false,
                'error' => 'Business lookup is temporarily unavailable.',
            ], 502);
        }

        return response()->json($response->json());
    }

    public function showBusiness(string $id): JsonResponse
    {
        $response = Http::timeout(10)->acceptJson()->get(self::BUSINESS_DETAIL_URL.'/'.urlencode($id));

        if ($response->status() === 404) {
            return response()->json([
                'ok' => false,
                'error' => 'Business not found.',
            ], 404);
        }

        if ($response->failed()) {
            return response()->json([
                'ok' => false,
                'error' => 'Business detail lookup is temporarily unavailable.',
            ], 502);
        }

        return response()->json($response->json());
    }

    public function suggestSubdomain(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
        ]);

        $normalizer = DomainIsValid::nameToDomainNormalizer();
        $base = $normalizer($validated['name']);

        if ($base === '') {
            $base = 'workspace';
        }

        $candidate = $base;
        if (! $this->isSubdomainAvailable($candidate, $normalizer)) {
            $candidate = sprintf('%s-%04d', $base, random_int(1000, 9999));

            while (! $this->isSubdomainAvailable($candidate, $normalizer)) {
                $candidate = sprintf('%s-%04d', $base, random_int(1000, 9999));
            }
        }

        return response()->json([
            'ok' => true,
            'subdomain' => $candidate,
        ]);
    }

    private function isSubdomainAvailable(string $candidate, \Closure $normalizer): bool
    {
        $validator = Validator::make(
            ['subdomain' => $candidate],
            [
                'subdomain' => [
                    'required',
                    'string',
                    'alpha_dash',
                    'min:4',
                    'max:255',
                    new DomainIsValid($normalizer),
                ],
            ]
        );

        if ($validator->fails()) {
            return false;
        }

        return ReservedDomain::whereSubdomain($candidate)->doesntExist()
            && Domain::whereDomain($candidate)->doesntExist();
    }
}
