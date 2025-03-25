<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Shift;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::paginate(10);
        return view('payment_methods.index', compact(var_name: 'paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payment_methods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:100',
            'descript' => 'nullable|string',
            'amount' => 0, 
            // Ensure amount is numeric (optional)
        ]);
    
        // Create a new PaymentMethod
        $paymentMethod = PaymentMethod::create([
            'name' => $request->name,
            'descript' => $request->descript,
        ]);
    
        // Check if an amount was provided and update the PaymentMethod if necessary
        if ($request->status === 'completed') {
            // 1) Retrieve the PaymentMethod (e.g., "Cash", "ABA", etc.)
            $paymentMethod = PaymentMethod::find($request->payment_method_id);
        
            if ($paymentMethod) {
                // 2) Convert $request->amount to float, then add to PaymentMethod->amount
                $amount = (float) $request->amount;
                $paymentMethod->amount += $amount;
                $paymentMethod->save();
            }
        
        
          
        }
        
        
    
        // Redirect back with success message
        return redirect()->route('payment_methods.index')
                         ->with('success', 'Payment method created successfully and amount updated.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return view('payment_methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('payment_methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'descript' => 'nullable|string',
        ]);

        $paymentMethod->update($request->all());

        return redirect()->route('payment_methods.index')
                         ->with('success', 'Payment method updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()->route('payment_methods.index')
                         ->with('success', 'Payment method deleted successfully.');
    }
}
