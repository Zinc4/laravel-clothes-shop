<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{

    public function index()
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
        return view('home', ['products' => $products]);
    }

  
}
