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
        $query = $query->paginate(config('constants.max_pagination'));
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
    public function store(Request $request, Product $product)
    {
        try {
            $data = $request->all();
            DB::beginTransaction();
            $saveProduct = $product->create($data);
            $product->saveData($data,$saveProduct);
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

        try {
            $data = $request->all();
            DB::beginTransaction();
            $product->update($data);
            $productVariant =new ProductVariant();
            $variantPrice = new ProductVariantPrice();
            $productVariant->where('product_id',$product->id)->delete();
            $variantPrice->where('product_id',$product->id)->delete();
            $product->saveData($data,$product);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
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
