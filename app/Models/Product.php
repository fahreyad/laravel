<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;

class Product extends Model
{
    protected $casts =[
        'created_at' => 'datetime:d-M-Y'
    ];
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    /**
     * Get all of the productVariant for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productVariant()
    {
        return $this->hasMany(ProductVariant::class)
        ->selectRaw("variant_id, Distinct('variant')");
        //->get();
    }



    /**
     * Get all of the productVariant for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productVariantPrice()
    {
        return $this->hasMany(ProductVariantPrice::class);
    }

    public function filters($params){

        $q = $this;
        if(!empty($params['title'])){
            $q = $q->where('title','like','%'.$params['title'].'%');
        }
        if(!empty($params['date'])){
            $q = $q->whereDate('created_at','like','%'.$params['date'].'%');
        }
        if(!empty($params['price_from']) && !empty($params['price_to'])){
            $q = $q->with([
                'productVariantPrice' => function ($q) use ($params){
                    return $q->with('productVariantOne','productVariantTwo','productVariantThree')
                    ->whereBetween('price', [$params['price_from'], $params['price_to']]);

                }
            ]);
        }else{

            $q = $q->with([
                'productVariantPrice' => function ($q){
                    return $q->with('productVariantOne','productVariantTwo','productVariantThree');
                }
            ]);
        }

        if(!empty($params['id'])){
            $q = $q->with('productVariant')->find($params['id']);
        }


        return $q;

    }

}
