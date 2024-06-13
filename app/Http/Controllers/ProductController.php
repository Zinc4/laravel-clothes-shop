<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller {

      public function show($id)
    {
        try {
            $response = Http::get('http://localhost:8000/api/products/' . $id);

            if ($response->successful()) {
                $product = $response->json();
                $product=$product['data'];
            } else {
                $product = [];
            }
        } catch (\Exception $e) {
            $product = [];
        }
        return view('product', ['product' => $product, 'id' => $id]);
        
    }
}