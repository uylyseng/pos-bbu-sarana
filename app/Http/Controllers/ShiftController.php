<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\User;
use App\Models\Payment;
use App\Models\ShiftPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    /**
     * Display a listing of shifts.
     */
    public function index()
    {
        $shifts = Shift::with('user')->orderBy('time_open', 'desc')->paginate(10);
        return view('shifts.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new shift.
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a shift.');
        }

        // Check if user has an active shift
        $activeShift = Shift::where('user_id', Auth::id())->whereNull('time_close')->first();
        if ($activeShift) {
            return redirect()->route('pos.index')->with('info', 'You already have an active shift.');
        }

        return view('shifts.create');
    }

    /**
     * Store a newly created shift and start POS.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a shift.');
        }

        // Validate input
        $request->validate([
            'cash_in_hand' => 'required|numeric|min:0',
        ]);

        $cashInHand = $request->input('cash_in_hand', 0);

        // Check if cash_in_hand is 0
        if ($cashInHand == 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Cash in hand must be greater than 0 to create a shift.');
        }

        // Ensure there's no active shift
        $activeShift = Shift::where('user_id', Auth::id())->whereNull('time_close')->first();
        if ($activeShift) {
            session(['active_shift' => $activeShift->id]);
            return redirect()->route('pos.index')->with('info', 'You already have an active shift.');
        }
    

        // Create shift
        $shift = Shift::create([
            'user_id' => Auth::id(),
            'time_open' => now(),
            'cash_in_hand' => $request->input('cash_in_hand', 0),
            'cash_submitted' => 0,
            'total_cash'    => 0, 
           
        ]);

        // Store shift in session
        session(['active_shift' => $shift->id]);
        session()->flash('success', 'Shift created successfully!');

        return redirect()->route('pos.index')->with('success', 'Shift created successfully!');
    }

    /**
     * Close an active shift.
     */
    public function closeShift(Request $request)
    {
        // Attempt to find the active shift
        $shift = Shift::where('user_id', Auth::id())
                      ->whereNull('time_close')
                      ->first();
    
        if (!$shift) {
            // If no shift, redirect with an error message
            return redirect()
                ->route('shifts.create')
                ->with('error', 'No active shift found or shift already closed.');
        }
    
        // Otherwise, close the shift
        $shift->update([
            'time_close'     => now(),
            'cash_submitted' => $request->input('cash_submitted', 0),
        ]);
    
        // Clear any session if needed
        session()->forget('active_shift');
    
        // Redirect to shifts.create with success
        return redirect()
            ->route('shifts.create')
            ->with('success', 'Shift closed successfully.');
    }
    

   
    
}
