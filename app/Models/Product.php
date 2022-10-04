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
        return $this->hasMany(ProductVariant::class);
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
    /**
     * Filter the data
     *
     * @return Query
     */
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
            $q = $q->with(['productVariant' => function($q){
                return $q
                ->selectRaw(" group_concat(distinct(variant)) as v, product_id,variant_id")
                ->groupBy('variant_id');
            }])->find($params['id']);
        }
        return $q;

    }
    /**
     * Populate the product variant prices & product variant database
     *
     * @return Query
     */
    public function saveData($data,$product){
        if(isset($data['product_variant_prices']) && is_array($data['product_variant_prices'])){
            foreach($data['product_variant_prices'] as $pvp){
                $title = explode("/",$pvp['title'],-1);
                $pvArr = [];
                foreach($title as $k=>$t){
                    $productVariant =new ProductVariant();
                    $productVariant->variant_id = $data['product_variant'][$k]['option'];
                    $productVariant->product_id = $product->id;
                    $productVariant->variant = $t;
                    $productVariant->save();
                    array_push($pvArr,$productVariant->id);
                }
                $variantPrice = new ProductVariantPrice();
                $variantPrice->price = $pvp['price'];
                $variantPrice->stock = $pvp['stock'];
                $variantPrice->product_variant_one = !empty($pvArr[0]) ? $pvArr[0]: null;
                $variantPrice->product_variant_two = !empty($pvArr[1]) ? $pvArr[1]: null;
                $variantPrice->product_variant_three = !empty($pvArr[2]) ? $pvArr[2]: null;
                $variantPrice->product_id = $product->id;
                $variantPrice->save();
            }
        }
        return;
    }

}
