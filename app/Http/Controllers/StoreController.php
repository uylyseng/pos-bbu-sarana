<?php
namespace App\Http\Controllers;

use App\Models\Store; // Replace Category model with Store model
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the stores.
     */
    public function index()
    {
        // Paginate stores and pass to the view
        $stores = Store::paginate(10); // Change to Store model
        return view('stores.index', compact('stores'));
    }

    /**
     * Show the form for creating a new store.
     */
    public function create()
    {
        return view('stores.create'); // View for creating a store
    }

    /**
     * Store a newly created store in storage.
     */
    public function store(Request $request)
    {
        // Validate input fields for store creation
        $request->validate([
            'name'        => 'required|string|max:100',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:50000000',
            'contact'     => 'nullable|string|max:100',
            'address'     => 'nullable|string',
            'receipt_header' => 'nullable|string',
            'receipt_footer' => 'nullable|string',
       
        ]);

        // Prepare data for storage
        $data = $request->only(['name', 'contact', 'address', 'receipt_header', 'receipt_footer', 'status']);

        // Store the image if provided
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('stores', 'public');
        }
        
        // Create store record
        Store::create($data);

        // Redirect back to the stores index with a success message
        return redirect()->route('stores.index')->with('success', 'Store created successfully.');
    }

    /**
     * Show the form for editing the specified store.
     */
    public function show($id)
    {
        $store = Store::findOrFail($id); // Fetch the store by its ID

        // Pass the store to the view
        return view('stores.show', compact('store'));
    }
    public function edit(Store $store)
    {
        // Show the edit form with the store data
        return view('stores.edit', compact('store'));
    }

    /**
     * Update the specified store in storage.
     */
    public function update(Request $request, Store $store)
    {
        // Validate input fields for store update
        $request->validate([
            'name'        => 'required|string|max:100',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:500000',
            'contact'     => 'nullable|string|max:100',
            'address'     => 'nullable|string',
            'receipt_header' => 'nullable|string',
            'receipt_footer' => 'nullable|string',
           
        ]);

        // Prepare data for updating the store
        $data = $request->only(['name', 'contact', 'address', 'receipt_header', 'receipt_footer', 'status']);

        // If a new image is uploaded, store it
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('stores', 'public');
        }

        // Update the store record
        $store->update($data);

        // Redirect back to the stores index with a success message
        return redirect()->route('stores.index')->with('success', 'Store updated successfully.');
    }

    /**
     * Remove the specified store from storage.
     */
    public function destroy(Store $store)
    {
        // Delete the store record
        $store->delete();

        // Redirect back to the stores index with a success message
        return redirect()->route('stores.index')->with('success', 'Store deleted successfully.');
    }
}
