<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use App\Models\Table;
use App\Models\PaymentMethod;
use App\Models\Shift;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        $payments = Payment::with(['order', 'paymentMethod'])->get();
        return response()->json($payments);
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        return view('payments.create');
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        // Validate the input fields
        $request->validate([
            'order_id'          => 'required|exists:orders,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount'            => 'required|numeric',
            'changeUSD'         => 'nullable|numeric',
            'changeRiel'        => 'nullable|integer',
            'note'              => 'nullable|string',
            'status'            => 'required|in:pending,completed',  // Validate status
        ]);
    
        // Store the payment data
        $payment = Payment::create([
            'order_id'          => $request->order_id,
            'payment_method_id' => $request->payment_method_id,
            'amount'            => $request->amount,
            'changeUSD'         => $request->changeUSD,
            'changeRiel'        => $request->changeRiel,
            'note'              => $request->note,
            'status'            => $request->status,  // Include status
        ]);
    
        // After payment is created, check if the status is 'completed' and update the PaymentMethod
        if ($request->status === 'completed') {
            // Find the PaymentMethod using the payment_method_id
            $paymentMethod = PaymentMethod::find($request->payment_method_id);
    
            if ($paymentMethod) {
                // Update the amount for the PaymentMethod (e.g., Cash, ABA)
                $paymentMethod->amount += $request->amount;  // Add the payment amount to the existing amount
                $paymentMethod->save();  // Save the updated amount
            }
            $activeShiftId = session('active_shift');
            if ($activeShiftId) {
                $shift = Shift::find($activeShiftId);
                // Make sure the shift is still open
                if ($shift && is_null($shift->time_close)) {
                    $shift->total_cash += $request->amount;
                    $shift->save();
                }
            }
        }
    
        // Return a success response with payment details
        return response()->json([
            'payment_id' => $payment->id,
            'message'    => 'Payment created successfully.',
            'order_id'   => $payment->order_id,
        ]);
    }
    
    
    

    

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0',
            'balance' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update($request->all());

        return response()->json([
            'message' => 'Payment updated successfully',
            'payment' => $payment
        ]);
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'order_id'          => 'required|exists:orders,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount'            => 'required|numeric',
            'changeUSD'         => 'nullable|numeric',
            'changeRiel'        => 'nullable|integer',
            'note'              => 'nullable|string',
            'status'            => 'required|in:pending,completed',
            'table_id'          => 'nullable|exists:tables,id', // for table update
        ]);
    
        DB::beginTransaction();
        try {
            // Create or update the payment record.
            // (This code is similar to your store() method, so it creates a new record.
            //  If you need to update an existing record instead, adjust accordingly.)
            $payment = Payment::create([
                'order_id'          => $request->order_id,
                'payment_method_id' => $request->payment_method_id,
                'amount'            => $request->amount,
                'changeUSD'         => $request->changeUSD,
                'changeRiel'        => $request->changeRiel,
                'note'              => $request->note,
                'status'            => $request->status,
            ]);
    
            // After payment is created, if status is 'completed',
            // update PaymentMethod amount and active shift's total cash.
            if ($request->status === 'completed') {
                $paymentMethod = PaymentMethod::find($request->payment_method_id);
                if ($paymentMethod) {
                    // Add the payment amount to the existing amount.
                    $paymentMethod->amount += $request->amount;
                    $paymentMethod->save();
                }
                $activeShiftId = session('active_shift');
                if ($activeShiftId) {
                    $shift = Shift::find($activeShiftId);
                    if ($shift && is_null($shift->time_close)) {
                        $shift->total_cash += $request->amount;
                        $shift->save();
                    }
                }
            }
    
            // **Update table status to active (free) if table_id is provided.**
            if ($request->filled('table_id')) {
                $table = Table::find($request->table_id);
                if ($table) {
                    $table->update(['status' => 'active']);
                }
            }
    
            DB::commit();
    
            return response()->json([
                'payment_id' => $payment->id,
                'message'    => 'Payment updated successfully.',
                'order_id'   => $payment->order_id,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error updating payment',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    
    
    
}
