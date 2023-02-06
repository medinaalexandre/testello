<?php

namespace App\Models\Delivery;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Delivery\DeliveryLocation
 *
 * @property int $location_id
 * @property int $customer_id
 * @property string $from_postcode
 * @property string $to_postcode
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read array|DeliveryWeightCost[] $weightCosts
 * @method static Builder|DeliveryLocation newModelQuery()
 * @method static Builder|DeliveryLocation newQuery()
 * @method static Builder|DeliveryLocation onlyTrashed()
 * @method static Builder|DeliveryLocation query()
 * @method static Builder|DeliveryLocation whereCreatedAt($value)
 * @method static Builder|DeliveryLocation whereCustomerId($value)
 * @method static Builder|DeliveryLocation whereDeletedAt($value)
 * @method static Builder|DeliveryLocation whereFromPostcode($value)
 * @method static Builder|DeliveryLocation whereLocationId($value)
 * @method static Builder|DeliveryLocation whereToPostcode($value)
 * @method static Builder|DeliveryLocation whereUpdatedAt($value)
 * @method static Builder|DeliveryLocation withTrashed()
 * @method static Builder|DeliveryLocation withoutTrashed()
 * @mixin Eloquent
 */
class DeliveryLocation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'delivery_location';
    protected $primaryKey = 'location_id';

    public function weightCosts(): HasMany
    {
        return $this->hasMany(
            DeliveryWeightCost::class,
            $this->primaryKey,
            $this->primaryKey,
        )->orderBy('delivery_weight_cost.delivery_weight_cost_id');
    }
}
