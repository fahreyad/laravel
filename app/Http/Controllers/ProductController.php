<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request,Product $product,Variant $variant)
    {
        $params = $request->all();
        $query = $product->filters($params);
        $query = $query->paginate(2);
        $links =  $query->appends($params)->links();
        $products = $query->toArray();
        $variants = $variant->with(['productVariant' => function($q){
            return $q->selectRaw(" distinct(variant), variant_id");
        }])->get();
        return view('products.index',compact('products','variants','links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            DB::beginTransaction();
            $product = Product::create($data);
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
            DB::commit();
        } catch (\Throwable $th) {

            DB::rollBack();
        }

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $params['id'] =  $product->id;

        $result = $product->filters($params);
        //$result = $product;
        return response()->json(
            [
                'result' => $result
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        $params['id'] = $product->id;
        $result = $product->filters($params);
        //dd($result);
        return view('products.edit', compact('variants','result'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
