<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    public function index()
    {

        $transactions = Transaction::where('user_id', Auth::user()->id)->get();

        $response = Http::get('http://localhost:8000/api/products');

        if ($response->successful()) {
            $products = $response->json();
            $products=$products['data'];
        } else {
            $products = [];
        }

        $transactions->transform(function ($transaction, $key) use ($products) {
            $transaction->product = collect($products)->firstWhere('id', $transaction->product_id);
            return $transaction;
        });


        return view('transactions', compact('transactions'));
    }
}
