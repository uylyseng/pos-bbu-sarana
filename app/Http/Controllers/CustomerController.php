<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get the 'perPage' parameter from the query string, defaulting to 10 if not provided
        $perPage = $request->get('perPage', 10);
    
        // Validate the 'perPage' parameter to allow only specific values (2, 4, 5, 10, 20)
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
        // Eager-load coupons for each customer and apply pagination
        $customers = Customer::with('coupons')->orderBy('id', 'asc')->paginate($perPage);
    
        // Return the view with customers and the selected 'perPage' value
        return view('customers.index', compact('customers', 'perPage'));
    }
    

    /**
     * Show the form for creating a new customer.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'name'         => 'required|string|max:100',
            'contact_info' => 'nullable|string',
        ]);

        // Create the customer record
        Customer::create($validatedData);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Show the form for editing the specified customer.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\View\View
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer      $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Customer $customer)
    {
        // Validate the request
        $validatedData = $request->validate([
            'name'         => 'required|string|max:100',
            'contact_info' => 'nullable|string',
        ]);

        // Update the customer record
        $customer->update($validatedData);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
