<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductsApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Products::all();
        // return new ProuductResource(true,"List Product",$product);
        return new ProductResource(true, "List Product", $product);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name'     => 'required',
            'price'   => 'required',
            'description'   => 'required',
            'stock'   => 'required',
        ]);

         if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        $product = Products::create([
            'image'     => $image->hashName(),
            'name'     => $request->name,
            'price'   => $request->price,
            'description'   => $request->description,
            'stock'   => $request->stock
        ]);

        return new ProductResource(true, 'Product Created', $product);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $product = Products::find($id);
        if($product){
            return new ProductResource(true, 'Detail Product', $product);
        }
        else{
            return new ProductResource(false, 'Product Not Found', null);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {

        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name'     => 'required',
            'price'   => 'required',
            'description'   => 'required',
            'stock'   => 'required',
        ]);

         if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Products::find($id);
        
        if($request->hasFile('image')){
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            Storage::delete('public/products/'.$product->image);

            $product->update([
                'image'     => $image->hashName(),
                'name'     => $request->name,
                'price'   => $request->price,
                'description'   => $request->description,
                'stock'   => $request->stock
            ]);
        } else {
            $product->update([
                'name'     => $request->name,
                'price'   => $request->price,
                'description'   => $request->description,
                'stock'   => $request->stock
            ]);
        }
        return new ProductResource(true, 'Product Updated', $product);
    

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        $product = Products::find($id);
        if($product){
            $product->delete();
            return new ProductResource(true, 'Product Deleted', null);
        } else {
            return new ProductResource(false, 'Product Not Found', null);
        }
        
    }
}
