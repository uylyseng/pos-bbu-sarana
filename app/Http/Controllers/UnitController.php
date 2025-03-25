<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the units.
     */
    public function index(Request $request)
    {
        // Get the 'perPage' parameter from the query string, defaulting to 10 if not provided
        $perPage = $request->get('perPage', 10);
    
        // Validate the 'perPage' parameter to allow only specific values (2, 4, 5, 10, 20)
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
        // Retrieve units with pagination
        $units = Unit::paginate($perPage);
    
        // Return the view with units and the selected 'perPage' value
        return view('units.index', compact('units', 'perPage'));
    }
    

    /**
     * Show the form for creating a new unit.
     */
    public function create()
    {
        return view('units.create');
    }

    /**
     * Store a newly created unit in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'symbol' => 'nullable|string|max:10',
            'descript' => 'nullable|string',
        ]);

        Unit::create($request->all());

        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
    }

    /**
     * Show the form for editing the specified unit.
     */
    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    /**
     * Update the specified unit in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'symbol' => 'nullable|string|max:10',
            'descript' => 'nullable|string',
        ]);

        $unit->update($request->all());

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified unit from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
