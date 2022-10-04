@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control" value="">
                </div>
                <div class="col-md-2">

                    <select name="variant" id="" class="form-control">

                        <option value="">-- Select A Variant-</option>
                        @foreach ($variants as $variant)
                            <optgroup label={{$variant->title}}>
                                @foreach ($variant->productVariant as $pv )
                                    <option value="{{$pv->variant}}">{{$pv->variant}}</option>
                                @endforeach
                            </optgroup>

                        @endforeach

                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach ($products['data'] as $product)


                        <tr>
                            <td>{{$product['id']}}</td>
                            <td>{{$product['title']}} <br> Created at : {{$product['created_at']}}</td>
                            <td>{{substr($product['description'],0,20).'...'}}</td>
                            <td>

                                <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">
                                    @foreach ( $product['product_variant_price'] as $p)
                                    <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">



                                        <dt class="col-sm-3 pb-0">
                                            {{!empty($p['variant_two']) ? $p['variant_two'] : ''}}
                                            {{!empty($p['variant_one']) ? '/ '.$p['variant_one'] : ''}}
                                            {{!empty($p['variant_three']) ? '/ '.$p['variant_three'] : ''}}
                                        </dt>
                                        <dd class="col-sm-9">
                                            <dl class="row mb-0">
                                                <dt class="col-sm-4 pb-0">Price : {{ number_format($p['price'],2) }}</dt>
                                                <dd class="col-sm-8 pb-0">InStock : {{ number_format($p['stock'],2) }}</dd>
                                            </dl>
                                        </dd>

                                    </dl>
                                    @endforeach
                                </dl>

                                <button  class="btn btn-sm btn-link show-more">Show more</button>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('product.edit', $product['id']) }}" class="btn btn-success">Edit</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach


                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{$products['from']}} to {{$products['to']}} out of {{$products['total']}}</p>
                </div>
                <div class="col-md-3">
                    {!! $links !!}
                </div>
            </div>
        </div>
    </div>

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function(){
        $('.show-more').on('click',function(){
            $(this).prev().toggleClass('h-auto');
        })
    })
</script>
