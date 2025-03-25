<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\GroupTable;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index(Request $request)
    {
        // Get 'perPage' from the request, default to 10 if not provided
        $perPage = $request->get('perPage', 10);
    
        // Validate the 'perPage' parameter to allow specific values
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
        // Paginate the tables and eager load the 'group' relationship
        $tables = Table::with('group')->paginate($perPage);
    
        // Fetch all groups
        $groups = GroupTable::all();
    
        // Return the view with data
        return view('tables.index', compact('tables', 'groups'));
    }
    

    public function create()
    {
        $groups = GroupTable::all();
        return view('tables.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'group_id' => 'nullable|exists:group_tables,id',
        ]);

        Table::create($request->all());

        return redirect()->route('tables.index')->with('success', 'Table created successfully!');
    }

    public function edit(Table $table)
    {
        $groups = GroupTable::all();
        return view('tables.edit', compact('table', 'groups'));
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'group_id' => 'nullable|exists:group_tables,id',
        ]);

        $table->update($request->all());

        return redirect()->route('tables.index')->with('success', 'Table updated successfully!');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->route('tables.index')->with('success', 'Table deleted successfully!');
    }
}
