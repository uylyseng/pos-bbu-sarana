<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index(Request $request)
    {
        // Get the 'perPage' parameter from the request, default to 10
        $perPage = $request->get('perPage', 10);
    
        // Validate the 'perPage' parameter to allow only specific values (2, 4, 5, 10, 20)
        $perPage = in_array($perPage, [2, 4, 5, 10, 20]) ? $perPage : 10;
    
        // Get the sizes with pagination
        $sizes = Size::paginate($perPage);
    
        // Return the view with sizes
        return view('sizes.index', compact('sizes'));
    }
    
    public function create()
    {
        return view('sizes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'descript' => 'nullable|string',
        ]);

        Size::create($request->all());

        return redirect()->route('sizes.index')->with('success', 'Size created successfully!');
    }

    public function edit(Size $size)
    {
        return view('sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'descript' => 'nullable|string',
        ]);

        $size->update($request->all());

        return redirect()->route('sizes.index')->with('success', 'Size updated successfully!');
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return redirect()->route('sizes.index')->with('success', 'Size deleted successfully!');
    }
}
