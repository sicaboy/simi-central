<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Plan
 *
 * @property int $id
 * @property int $position
 * @property int $tier
 * @property string $name
 * @property string $title
 * @property string $short_description
 * @property int|null $trial_days
 * @property int $archived
 * @property string|null $monthly_id
 * @property string|null $monthly_incentive
 * @property string|null $yearly_id
 * @property string|null $yearly_incentive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Feature[] $features
 * @property-read int|null $features_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Plan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereMonthlyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereMonthlyIncentive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereTier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereTrialDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereYearlyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plan whereYearlyIncentive($value)
 *
 * @mixin \Eloquent
 */
class Plan extends Model
{
    use HasFactory;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'plan_features')
            ->withPivot('value');
    }
}
