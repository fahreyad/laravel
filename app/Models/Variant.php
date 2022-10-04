<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class Variant extends Model
{
    protected $fillable = [
        'title', 'description'
    ];

    /**
     * Get all of the productVariant for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productVariant()
    {
        return $this->hasMany(ProductVariant::class);
    }


}
