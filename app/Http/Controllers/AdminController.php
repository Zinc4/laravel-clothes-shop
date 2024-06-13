<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
  public function index() :View
    {
        try {
            $response = Http::get('http://localhost:8000/api/products');

            if ($response->successful()) {
                $products = $response->json();
                $products=$products['data'];
            } else {
                $products = [];
            }

        } catch (\Exception $e) {
            $products = [];
        }
        return view('admin.product.index', ['products' => $products]);
    }

    public function create(): View
    {
        return view('admin.product.create');
    }

    public function store(Request $request): RedirectResponse
    {
    

    if ($request->hasFile('image')) {
        $image = $request->file('image');

        $response = Http::attach(
            'image', 
            file_get_contents($image->getRealPath()), 
            $image->getClientOriginalName()
        )->post('http://localhost:8000/api/products', [
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'stock'       => $request->stock,
        ]);

        if ($response->successful()) {
            return redirect()->route('admin.products.index')->with('success', 'Product Created');
        } else {
            return redirect()->route('admin.products.index')->with('error', 'Product Creation Failed');
        }
    }

    return redirect()->route('admin.products.index')->with('error', 'Image file is required');
    }

    public function edit($id): View
    {
        $response = Http::get('http://localhost:8000/api/products/' . $id);

        if ($response->successful()) {
            $product = $response->json();
             $product=$product['data'];
        } else {
            $product = [];
        }
        return view('admin.product.edit', ['product' => $product, 'id' => $id]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
    $data = [
        'name'        => $request->name,
        'price'       => $request->price,
        'description' => $request->description,
        'stock'       => $request->stock,
        '_method'     => 'PUT',
    ];

    if ($request->hasFile('image')) {
        $image = $request->file('image');

        $response = Http::attach(
            'image',
            file_get_contents($image->getRealPath()),
            $image->getClientOriginalName()
        )->post('http://localhost:8000/api/products/' . $id, $data);
    } else {
        $response = Http::post('http://localhost:8000/api/products/' . $id, $data);
    }

    if ($response->successful()) {
        return redirect()->route('admin.products.index')->with('success', 'Product Updated');
    } else {
        return redirect()->route('admin.products.index')->with('error', 'Product Update Failed');
    }
    }

    public function destroy($id): RedirectResponse
    {
        $response = Http::delete('http://localhost:8000/api/products/' . $id);
        return redirect()->route('admin.products.index')->with('success', 'Product Deleted');
    }

    public function show($id): View
    {
        $response = Http::get('http://localhost:8000/api/products/' . $id);

        if ($response->successful()) {
            $product = $response->json();
            $product=$product['data'];
        } else {
            $product = [];
        }


        return view('admin.product.show', ['product' => $product, 'id' => $id]);
    }

}
