<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{

    protected $fillable = [
        'product_variant_one', 'product_variant_two', 'product_variant_three','price','stock','product_id'
    ];
    protected $appends = ['variant_one','variant_two','variant_three'];

    /**
     * Get the product that owns the ProductVariant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productVariantOne()
    {
        return $this->belongsTo('App\Models\ProductVariant','product_variant_one','id');
    }
    /**
     * Get the product that owns the ProductVariant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productVariantTwo()
    {
        return $this->belongsTo('App\Models\ProductVariant','product_variant_two','id');
    }
    /**
     * Get the product that owns the ProductVariant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productVariantThree()
    {
        return $this->belongsTo('App\Models\ProductVariant','product_variant_three','id');
    }

    public function getVariantOneAttribute()
    {
        return !empty($this->productVariantOne()->first()) ? ucwords($this->productVariantOne()->first()->variant) : null;
    }
    public function getVariantTwoAttribute()
    {
        return !empty($this->productVariantTwo()->first()) ? strtoupper($this->productVariantTwo()->first()->variant) : null;
    }
    public function getVariantThreeAttribute()
    {
        return !empty($this->productVariantThree()->first()) ? ucwords($this->productVariantThree()->first()->variant) : null;
    }
}
