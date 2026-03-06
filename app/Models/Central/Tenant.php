<?php

namespace App\Models\Central;

/**
 * App\Models\Tenant
 */
class Tenant extends \Sicaboy\SharedSaas\Models\Central\Tenant
{
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'is_ready' => 'boolean',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'is_ready',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\TenantFactory::new();
    }
}
