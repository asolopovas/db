<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $touches = ['order'];
    protected $appends = ['price', 'wastage', 'discountedPrice'];
    protected $fillable = [
        'order_id',
        'floor_id',
        'extra_id',
        'dimension_id',
        'grade_id',
        'wastage_rate',
        'discount',
        'meterage',
        'unit_price',
    ];

    protected $casts = [
        'price'        => 'float',
        'meterage'     => 'float',
        'unit_price'   => 'float',
        'wastage'      => 'float',
        'wastage_rate' => 'float',
        'discount'     => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function areas()
    {
        return $this->hasMany(ProductArea::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    /**
     * @param Floor $floor
     *
     * @return Model
     */
    public function addFloor(Floor $floor)
    {
        return $this->floor()->associate($floor);
    }

    /**
     * @return mixed
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * @param Grade $grade
     *
     * @return Model
     */
    public function addGrade(Grade $grade)
    {
        return $this->grade()->associate($grade);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function extra()
    {
        return $this->belongsTo(Extra::class);
    }

    /**
     * @param Extra $extra
     *
     * @return Model
     */
    public function addExtra(Extra $extra)
    {
        return $this->extra()->associate($extra);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }

    /**
     * @param Dimension $dimension
     *
     * @return Model
     */
    public function addDimension(Dimension $dimension)
    {
        return $this->dimension()->associate($dimension);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @param $query
     */
    public function scopeWithMeta($query)
    {
        $query->join('floors', 'products.floor_id', '=', 'floors.id')->join(
            'extras',
            'products.extra_id',
            '=',
            'extras.id'
        )->join('dimensions', 'products.dimension_id', '=', 'dimensions.id')->join(
            'grades',
            'products.grade_id',
            '=',
            'grades.id'
        )->addSelect(
            [
                'products.*',
                'floors.name as floor',
                'extras.name as extra',
                'dimensions.width as width',
                'dimensions.length as length',
                'dimensions.thickness as thickness',
                'dimensions.type as type',
                'grades.name as grade',
                'extras.name as extra',
            ]
        );
    }

    public function scopeLastMonth($query, $last_month = false)
    {
        if ($last_month) {
            $start = new Carbon('first day of last month');
            $end = new Carbon('last day of last month');
            $dates = [$start->format('Y-m-d 00:00:00'), $end->format('Y-m-d 00:00:00')];
            $query->whereBetween('orders.payment_date', $dates);
        }

        return $query;
    }

    public function scopeData($query)
    {
        return $query->join('orders', 'products.order_id', '=', 'orders.id')
            ->withMeta()
            ->join('statuses', 'orders.status_id', '=', 'statuses.id')
            ->leftjoin('projects', 'orders.project_id', '=', 'projects.id')
            ->with('customer')
            ->where('statuses.name', 'Invoice')
            ->whereRaw('orders.balance != orders.grand_total')
            ->addSelect(
                [
                    'projects.street as pr_street',
                    'projects.postcode as pr_postcode',
                    'statuses.name as status',
                    'orders.customer_id',
                    'orders.payment_date as order_date',
                ]
            )
            ->orderBy('order_date', 'asc');
    }

    /**
     *
     */
    public function meterageUpdate()
    {
        $this->meterage = 0;
        if ($this->areas->count() !== 0) {
            $this->meterage = $this->areas->sum('meterage');
        }
        $this->save();
    }

    /**
     * Wastage attribute accessor
     * @return float|int
     */
    public function getWastageAttribute()
    {
        $result = $this->meterage * $this->wastage_rate / 100;

        return round($result, 2);
    }

    /**
     * @return float|int|null
     */
    public function getTotalMeterageAttribute()
    {
        $result = $this->meterage + $this->wastage;

        return round($result, 2);
    }

    /**
     * @return float|int
     */
    public function getPriceAttribute()
    {
        $result = $this->totalMeterage * $this->unit_price;

        return round($result, 2);
    }

    /**
     * @return float|int
     */
    public function getDiscountedUnitPriceAttribute()
    {
        $result = $this->unit_price * (1 - $this->discount / 100);

        return round($result, 2);
    }

    /**
     * @return float|int
     */
    public function getDiscountedPriceAttribute()
    {
        $result = $this->totalMeterage * $this->discountedUnitPrice;

        return round($result, 2);
    }

    /**
     * @return Floor|string
     */
    public function getNameAttribute()
    {
        return $this->floor === 'Natural Oiled' ? $this->floor : "$this->floor - Bespoke Item";
    }

    /**
     * @return string
     */
    public function getDimensionsAttribute()
    {
        return $this->length ?
            "{$this->length}x{$this->width}x{$this->thickness}mm" :
            "{$this->width}x{$this->thickness}mm";
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'products_index';
    }
}
