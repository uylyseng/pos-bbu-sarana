<?php

namespace App\Http\Controllers;

use App\Models\Topping;
use Illuminate\Http\Request;

class ToppingController extends Controller
{
    /**
     * Display a listing of the toppings.
     */
    public function index(Request $request)
    {
        // Get the 'perPage' parameter from the request, default to 10
        $perPage = $request->get('perPage', 10);
        
        // Validate the 'perPage' parameter to allow only specific values (2, 4, 5, 10, 20)
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
        
        // Get the toppings with pagination
        $toppings = Topping::paginate($perPage);
    
        // Return the view with toppings
        return view('toppings.index', compact('toppings'));
    }
    
    /**
     * Show the form for creating a new topping.
     */
    public function create()
    {
        return view('toppings.create');
    }

    /**
     * Store a newly created topping in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'descript' => 'nullable|string',
        ]);

        Topping::create($request->only(['name', 'descript']));

        return redirect()->route('toppings.index')->with('success', 'Topping created successfully!');
    }

    /**
     * Show the form for editing the specified topping.
     */
    public function edit(Topping $topping)
    {
        return view('toppings.edit', compact('topping'));
    }

    /**
     * Update the specified topping in storage.
     */
    public function update(Request $request, Topping $topping)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'descript' => 'nullable|string',
        ]);

        $topping->update($request->only(['name', 'descript']));

        return redirect()->route('toppings.index')->with('success', 'Topping updated successfully!');
    }

    /**
     * Remove the specified topping from storage.
     */
    public function destroy(Topping $topping)
    {
        $topping->delete();
        return redirect()->route('toppings.index')->with('success', 'Topping deleted successfully!');
    }
}
