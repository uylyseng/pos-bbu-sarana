<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers.
     */
    public function index(Request $request)
    {
        // Get the 'perPage' parameter from the query string, defaulting to 10 if not provided
        $perPage = $request->get('perPage', 10);
    
        // Validate the 'perPage' parameter to allow only specific values (2, 4, 5, 10, 20)
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
        // Retrieve suppliers with pagination
        $suppliers = Supplier::paginate($perPage);
    
        // Return the view with suppliers and the selected 'perPage' value
        return view('suppliers.index', compact('suppliers', 'perPage'));
    }
    

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'contact_info' => 'nullable|string',
            'address'      => 'nullable|string',
        ]);

        Supplier::create($request->only(['name', 'contact_info', 'address']));

        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'contact_info' => 'nullable|string',
            'address'      => 'nullable|string',
        ]);

        $supplier->update($request->only(['name', 'contact_info', 'address']));

        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier deleted successfully.');
    }
}
