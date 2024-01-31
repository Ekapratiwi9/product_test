<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('product')->paginate(10);
        return view('transaction.index', compact('transactions'));
    }

    public function store(Request $request)
    {
       $product = Product::find($request->product_id);

        if ($product) {
            $price = $product->price;
        } else {
            $price = 0; 
        }

        $payment = $price * $request->quantity;

        $transactions = Transaction::create([
            'product_id' => $request->product_id,
            'price' => $price,
            'quantity' => $request->quantity,
            'payment_amount' => $payment
        ]);

        $stock = $product->stock - $request->quantity;

        $product->update([
            'stock' => $stock
        ]);

        if ($transactions) {
            return response()->json(['message' => 'Data Berhasil Disimpan']);
        } else {
            return response()->json(['message' => 'Data Gagal Disimpan']);
        }
        
    }
}
