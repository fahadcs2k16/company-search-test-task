<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'report_id' => 'required|integer',
            'report_name' => 'required|string',
            'company_name' => 'required|string',
            'country_code' => 'required|string',
            'price' => 'required|numeric',
            'period' => 'nullable|string'
        ]);

        $cart = Session::get('cart', []);
        
        $cartItem = [
            'report_id' => $request->report_id,
            'report_name' => $request->report_name,
            'company_name' => $request->company_name,
            'country_code' => $request->country_code,
            'price' => $request->price,
            'period' => $request->period,
            'cart_key' => $request->country_code . '_' . $request->report_id . '_' . ($request->period ?? 'default')
        ];

        $cart[$cartItem['cart_key']] = $cartItem;
        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Report added to cart',
            'cart_count' => count($cart)
        ]);
    }

    public function remove(Request $request)
    {
        $cartKey = $request->input('cart_key');
        $cart = Session::get('cart', []);
        
        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            Session::put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart_count' => count($cart)
        ]);
    }

    public function view()
    {
        $cart = Session::get('cart', []);
        $total = array_sum(array_column($cart, 'price'));

        return view('cart.view', compact('cart', 'total'));
    }

    public function clear()
    {
        Session::forget('cart');
        return redirect()->back()->with('success', 'Cart cleared successfully');
    }
}
