<?php

namespace App\Http\Controllers;

use App\Models\GroupTable;
use Illuminate\Http\Request;

class GroupTableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the 'perPage' parameter from the query string, defaulting to 10 if not provided
        $perPage = $request->get('perPage', 10);
    
        // Validate the 'perPage' parameter to allow only specific values (2, 4, 5, 10, 20)
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
        // Retrieve group tables with pagination
        $groupTables = GroupTable::paginate($perPage);
    
        // Return the view with group tables and the selected 'perPage' value
        return view('group_tables.index', compact('groupTables', 'perPage'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('group_tables.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'descript' => 'nullable|string',
        ]);

        GroupTable::create($request->all());

        return redirect()->route('group_tables.index')->with('success', 'Group Table created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupTable $groupTable)
    {
        return view('group_tables.edit', compact('groupTable'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GroupTable $groupTable)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'descript' => 'nullable|string',
        ]);

        $groupTable->update($request->all());

        return redirect()->route('group_tables.index')->with('success', 'Group Table updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupTable $groupTable)
    {
        $groupTable->delete();
        return redirect()->route('group_tables.index')->with('success', 'Group Table deleted successfully.');
    }
}
