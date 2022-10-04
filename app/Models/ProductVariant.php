<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Variant;
class ProductVariant extends Model
{
    protected $fillable = [
        'variant_id', 'product_id', 'variant'
    ];
    /**
     * Get the product that owns the ProductVariant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * Get the varient that owns the ProductVariant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function varient()
    {
        return $this->belongsTo(Variant::class);
    }
}
