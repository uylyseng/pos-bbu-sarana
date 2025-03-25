<?php

namespace App\Http\Controllers;

use App\Models\ExpenseType;
use Illuminate\Http\Request;

class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of the expense types.
     */
    public function index(Request $request)
    {
        // Get 'perPage' from the request, default to 10
        $perPage = $request->get('perPage', 10);
    
        // Validate 'perPage' to allow only specific values
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
        // Fetch paginated data based on 'perPage'
        $expenseTypes = ExpenseType::paginate($perPage);
    
        return view('expense_types.index', compact('expenseTypes'));
    }
    
    /**
     * Show the form for creating a new expense type.
     */
    public function create()
    {
        return view('expense_types.create');
    }

    /**
     * Store a newly created expense type in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        ExpenseType::create($request->only(['name', 'description']));

        return redirect()->route('expense_types.index')
                         ->with('success', 'Expense type created successfully.');
    }

    /**
     * Display the specified expense type.
     */
    public function show(ExpenseType $expenseType)
    {
        return view('expense_types.show', compact('expenseType'));
    }

    /**
     * Show the form for editing the specified expense type.
     */
    public function edit(ExpenseType $expenseType)
    {
        return view('expense_types.edit', compact('expenseType'));
    }

    /**
     * Update the specified expense type in storage.
     */
    public function update(Request $request, ExpenseType $expenseType)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $expenseType->update($request->only(['name', 'description']));

        return redirect()->route('expense_types.index')
                         ->with('success', 'Expense type updated successfully.');
    }

    /**
     * Remove the specified expense type from storage.
     */
    public function destroy(ExpenseType $expenseType)
    {
        $expenseType->delete();

        return redirect()->route('expense_types.index')
                         ->with('success', 'Expense type deleted successfully.');
    }
}
