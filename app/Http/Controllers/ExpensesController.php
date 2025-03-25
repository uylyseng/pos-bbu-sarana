<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\ExpenseType;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the expenses.
     */
    public function index()
    {
        $expenses = Expense::paginate(10);
        $expenseTypes = ExpenseType::all();
        $paymentMethods = PaymentMethod::all();
    
        return view('expenses.index', compact('expenses', 'expenseTypes', 'paymentMethods'));
    }
    public function recovery()
{
    $expenses = Expense::onlyTrashed()->paginate(10);

    if ($expenses->isEmpty()) {
        dd("No deleted expenses found!");
    }

    return view('expenses.recovery', compact('expenses'));
}

    /**
     * Show the form for creating a new expense.
     */
    public function create()
    {
        // If you need dropdowns for expense types or payment methods, fetch them here.
        $expenseTypes = ExpenseType::all();
        $paymentMethods = PaymentMethod::all();
        return view('expenses.create', compact('expenseTypes', 'paymentMethods'));
    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'expense_type_id'   => 'required|exists:expense_types,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'reference'         => 'nullable|string|max:50',
            'expense_date'      => 'required|date',
            'amount'            => 'required|numeric',
            'description'       => 'nullable|string',
            'attachment'        => 'nullable|file|max:2048',
        ]);

        // Process the attachment if provided.
        $attachmentPath = $request->hasFile('attachment')
            ? $request->file('attachment')->store('expenses/attachments', 'public')
            : null;

        // Merge audit fields: set created_by to the current user ID.
        $data = array_merge(
            $request->all(),
            ['attachment' => $attachmentPath, 'created_by' => Auth::id()]
        );

        Expense::create($data);

        return redirect()->route('expenses.index')
                         ->with('success', 'Expense created successfully.');
    }

    /**
     * Display the specified expense.
     */
    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit(Expense $expense)
    {
        $expenseTypes = ExpenseType::all();
        $paymentMethods = PaymentMethod::all();
        return view('expenses.edit', compact('expense', 'expenseTypes', 'paymentMethods'));
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_type_id'   => 'required|exists:expense_types,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'reference'         => 'nullable|string|max:50',
            'expense_date'      => 'required|date',
            'amount'            => 'required|numeric',
            'description'       => 'nullable|string',
            'attachment'        => 'nullable|file|max:2048',
        ]);

        // Process the attachment if provided; otherwise, keep existing value.
        $attachmentPath = $request->hasFile('attachment')
            ? $request->file('attachment')->store('expenses/attachments', 'public')
            : $expense->attachment;

        $data = array_merge(
            $request->all(),
            ['attachment' => $attachmentPath, 'updated_by' => Auth::id()]
        );

        $expense->update($data);

        return redirect()->route('expenses.index')
                         ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(Expense $expense)
    {
        // Set the deleted_by field before deletion.
        $expense->update(['deleted_by' => Auth::id()]);
        $expense->delete();

        return redirect()->route('expenses.index')
                         ->with('success', 'Expense deleted successfully.');
    }
// In your ExpensesController.php


    

    
    public function restore($id)
    {
        $expense = Expense::withTrashed()->findOrFail($id);
        $expense->restore();
        return redirect()->route('expenses.index')->with('success', 'Expense restored successfully.');
    }
    
    public function forceDelete($id)
    {
        $expense = Expense::withTrashed()->findOrFail($id);
        $expense->forceDelete();
        return redirect()->route('expenses.index')->with('success', 'Expense permanently deleted.');
    }
    


}
