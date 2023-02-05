<?php

namespace App\Models\Delivery;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Delivery\DeliveryWeightCost
 *
 * @property int $delivery_weight_cost_id
 * @property int $location_id
 * @property float $from_weight
 * @property float $to_weight
 * @property int $cost_cents
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|DeliveryWeightCost newModelQuery()
 * @method static Builder|DeliveryWeightCost newQuery()
 * @method static Builder|DeliveryWeightCost onlyTrashed()
 * @method static Builder|DeliveryWeightCost query()
 * @method static Builder|DeliveryWeightCost whereCostCents($value)
 * @method static Builder|DeliveryWeightCost whereCreatedAt($value)
 * @method static Builder|DeliveryWeightCost whereDeletedAt($value)
 * @method static Builder|DeliveryWeightCost whereDeliveryWeightCostId($value)
 * @method static Builder|DeliveryWeightCost whereFromWeight($value)
 * @method static Builder|DeliveryWeightCost whereLocationId($value)
 * @method static Builder|DeliveryWeightCost whereToWeight($value)
 * @method static Builder|DeliveryWeightCost whereUpdatedAt($value)
 * @method static Builder|DeliveryWeightCost withTrashed()
 * @method static Builder|DeliveryWeightCost withoutTrashed()
 * @mixin Eloquent
 */
class DeliveryWeightCost extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'delivery_weight_cost';
    protected $primaryKey = 'delivery_weight_cost_id';
}
