<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductTopping;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Size;
use App\Models\Topping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
  public function index(Request $request)
{
    $query = Product::query();
    
    // Apply filters
    if ($request->filled('is_stock')) {
        $query->where('is_stock', $request->input('is_stock'));
    }
    
    if ($request->filled('has_size')) {
        $query->where('has_size', $request->input('has_size'));
    }
    
    if ($request->filled('has_topping')) {
        $query->where('has_topping', $request->input('has_topping'));
    }
    
    // Name filter (supports both English & Khmer names)
    if ($request->filled('search_name')) {
        $query->where(function ($q) use ($request) {
            $q->where('name_en', 'LIKE', '%' . $request->search_name . '%')
              ->orWhere('name_kh', 'LIKE', '%' . $request->search_name . '%');
        });
    }
    
    // Get the 'perPage' parameter from the query string or set a default value of 10
    $perPage = $request->get('perPage', 10);

    // Validate the 'perPage' parameter to allow only specific values (2, 5, 10, 20)
    $perPage = in_array($perPage, [2, 5, 10, 20]) ? $perPage : 10;

    // Retrieve products with pagination and relationships
    $products = $query->with(['unit', 'category', 'sizes', 'toppings'])->paginate($perPage);

    return view('products.index', compact('products', 'perPage'));
}

    
    public function getStock($productId)
    {
        $product = Product::select('is_stock', 'qty')->where('id', $productId)->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'is_stock'  => $product->is_stock, // 'have_stock' or 'none_stock'
            'qty'       => $product->qty ?? 0, // Ensure it returns the correct stock
        ]);
    }
    
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    
    public function search(Request $request)
{
    $query = $request->input('query');

    // Check if query is provided
    if (!$query) {
        return response()->json(['error' => 'No query provided.'], 400);
    }

    // Search by name_en, name_kh, or barcode
    $products = Product::with('category')
        ->where(function ($qb) use ($query) {
            $qb->where('name_en', 'like', '%' . $query . '%')
               ->orWhere('name_kh', 'like', '%' . $query . '%')
               ->orWhere('barcode', 'like', '%' . $query . '%');
        })
        ->get();

    // Return the results as a JSON response
    return response()->json($products);
}

    
    
    
    public function create()
    {
        $units = Unit::all();
        $categories = Category::all();
        $sizes = Size::all();
        $toppings = Topping::all();
      
        return view('products.create', compact('units', 'categories', 'sizes', 'toppings'));
    }
    
    public function store(Request $request)
    {
        $rules = [
            'barcode'          => 'nullable|string|unique:products,barcode',
            'name_en'          => 'required|string|max:100',
            'name_kh'          => 'nullable|string|max:100',
            'base_price'       => 'required|numeric',
            'sale_unit_id'     => 'nullable|exists:units,id',
            'purchase_unit_id'=>'nullable|exists:units,id',
            'category_id'      => 'nullable|exists:categories,id',
            'is_stock'         => 'in:have_stock,none_stock',
            'low_stock'        => 'nullable|numeric',
            'qty'              => 'nullable|numeric',
            'image'            => 'nullable|image|max:2048',
            'active'           => 'in:active,inactive',
            'has_size'         => 'in:has,none',
            'has_topping'      => 'in:has,none',
            'created_by'       => 'nullable|exists:users,id'
        ];
    
        $validatedData = $request->validate($rules);
        $validatedData['created_by'] = auth()->id();
     
     
        $is_stock_value = $request->input('is_stock', 'none_stock');
        $validatedData['is_stock'] = $is_stock_value;
    
        // Normalize options
        $validatedData['has_size'] = $request->input('has_size') === 'has' ? 'has' : 'none';
        $validatedData['has_topping'] = $request->input('has_topping') === 'has' ? 'has' : 'none';
        $validatedData['active'] = $request->input('active', 'inactive');
    
        // When product has no stock, clear purchase_unit_id and low_stock.
        if ($request->input('is_stock') === 'none_stock') {
            $validatedData['low_stock'] = null;
            $validatedData['purchase_unit_id'] = null; // Clear purchase_unit_id if no stock
        } else {
            // When product has stock ("have_stock"), ensure low_stock is provided
            if (empty($validatedData['low_stock'])) {
                return redirect()->back()
                    ->withErrors(['low_stock' => 'Low stock level is required for products that have stock.'])
                    ->withInput();
            }
    
            // Ensure purchase_unit_id is provided when the product has stock
            if (empty($validatedData['purchase_unit_id'])) {
                return redirect()->back()
                    ->withErrors(['purchase_unit_id' => 'Purchase unit is required for products that have stock.'])
                    ->withInput();
            }
        }
    
        // Process image upload if provided.
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('products', $filename, 'public');
            $validatedData['image'] = $path;
        }
    
        // Create the product.
        $product = Product::create($validatedData);
    
        // Process Product Sizes.
        ProductSize::where('product_id', $product->id)->delete();
        if ($validatedData['has_size'] === 'has') {
            $sizeIds = $request->input('size_ids', []);
            $sizePrices = $request->input('size_prices', []);
            foreach ($sizeIds as $index => $sizeId) {
                if (!empty($sizeId)) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size_id'    => $sizeId,
                        'price'      => isset($sizePrices[$index]) ? $sizePrices[$index] : 0,
                    ]);
                }
            }
        }
    
        // Process Product Toppings.
        ProductTopping::where('product_id', $product->id)->delete();
        if ($validatedData['has_topping'] === 'has') {
            $toppingIds = $request->input('topping_ids', []);
            $toppingPrices = $request->input('topping_prices', []);
            foreach ($toppingIds as $index => $toppingId) {
                if (!empty($toppingId)) {
                    ProductTopping::create([
                        'product_id' => $product->id,
                        'topping_id' => $toppingId,
                        'price'      => isset($toppingPrices[$index]) ? $toppingPrices[$index] : 0,
                    ]);
                }
            }
        }
    
        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }
    
    public function edit(Product $product)
    {
        $units = Unit::all();
        $categories = Category::all();
        $sizes = Size::all();
        $toppings = Topping::all();
    
        return view('products.edit', compact('product', 'units', 'categories', 'sizes', 'toppings'));
    }
    
    public function update(Request $request, Product $product)
    {
        $rules = [
            'barcode'          => 'nullable|string|unique:products,barcode,' . $product->id,
            'name_en'          => 'required|string|max:100',
            'name_kh'          => 'nullable|string|max:100',
            'base_price'       => 'required|numeric',
            'purchase_unit_id'=>'nullable|exists:units,id',
            'sale_unit_id'     => 'nullable|exists:units,id',
            'category_id'      => 'nullable|exists:categories,id',
            'is_stock'         => 'in:have_stock,none_stock',
            'low_stock'        => 'nullable|numeric',
            'image'            => 'nullable|image|max:2048',
            'active'           => 'in:active,inactive',
            'has_size'         => 'in:has,none',
            'has_topping'      => 'in:has,none',
            'updated_by'       => 'nullable|exists:users,id'
        ];
    
        $validatedData = $request->validate($rules);
        $validatedData['updated_by'] = auth()->id();
        $is_stock_value = $request->input('is_stock', 'none_stock');
        $validatedData['is_stock'] = $is_stock_value;
        $validatedData['has_size'] = $request->input('has_size') === 'has' ? 'has' : 'none';
        $validatedData['has_topping'] = $request->input('has_topping') === 'has' ? 'has' : 'none';
        $validatedData['active'] = $request->input('active', 'inactive');
    
        // When product has no stock, clear purchase_unit_id and low_stock.
       
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('products', $filename, 'public');
            $validatedData['image'] = $path;
        }
        if ($request->input('is_stock') === 'none_stock') {
            $validatedData['low_stock'] = null;
            $validatedData['purchase_unit_id'] = null; // Clear purchase_unit_id if no stock
        } else {
            // When product has stock ("have_stock"), ensure low_stock is provided
            if (empty($validatedData['low_stock'])) {
                return redirect()->back()
                    ->withErrors(['low_stock' => 'Low stock level is required for products that have stock.'])
                    ->withInput();
            }
    
            // Ensure purchase_unit_id is provided when the product has stock
            if (empty($validatedData['purchase_unit_id'])) {
                return redirect()->back()
                    ->withErrors(['purchase_unit_id' => 'Purchase unit is required for products that have stock.'])
                    ->withInput();
            }
        }
    
        $product->update($validatedData);
    
        // Update Product Sizes.
        ProductSize::where('product_id', $product->id)->delete();
        if ($validatedData['has_size'] === 'has') {
            $sizeIds = $request->input('size_ids', []);
            $sizePrices = $request->input('size_prices', []);
            foreach ($sizeIds as $index => $sizeId) {
                if (!empty($sizeId)) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size_id'    => $sizeId,
                        'price'      => isset($sizePrices[$index]) ? $sizePrices[$index] : 0,
                    ]);
                }
            }
        }
    
        // Update Product Toppings.
        ProductTopping::where('product_id', $product->id)->delete();
        if ($validatedData['has_topping'] === 'has') {
            $toppingIds = $request->input('topping_ids', []);
            $toppingPrices = $request->input('topping_prices', []);
            foreach ($toppingIds as $index => $toppingId) {
                if (!empty($toppingId)) {
                    ProductTopping::create([
                        'product_id' => $product->id,
                        'topping_id' => $toppingId,
                        'price'      => isset($toppingPrices[$index]) ? $toppingPrices[$index] : 0,
                    ]);
                }
            }
        }
    
        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }
    
    public function destroy(Product $product)
    {
        $product->update(['deleted_by' => Auth::id()]);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
