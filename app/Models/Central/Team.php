<?php

namespace App\Models\Central;

/**
 * App\Models\Team
 */
class Team extends \Sicaboy\SharedSaas\Models\Central\Team
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];
}
