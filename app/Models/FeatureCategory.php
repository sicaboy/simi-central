<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FeatureCategory
 *
 * @property int $id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Feature[] $features
 * @property-read int|null $features_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FeatureCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeatureCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeatureCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeatureCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeatureCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeatureCategory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeatureCategory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class FeatureCategory extends Model
{
    use HasFactory;

    public function features()
    {
        return $this->hasMany(\App\Models\Feature::class);
    }
}
