<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PlanFeature
 *
 * @property int $id
 * @property int $plan_id
 * @property int $feature_id
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFeature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFeature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFeature query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFeature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFeature whereFeatureId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFeature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFeature wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFeature whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFeature whereValue($value)
 *
 * @mixin \Eloquent
 */
class PlanFeature extends Model
{
    use HasFactory;
}
