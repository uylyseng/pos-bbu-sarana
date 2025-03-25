<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoleModel; // Assuming this is the correct model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $data['getRecord'] = User::getRecord(); // Fetch users with role names
        return view('users.list', $data);
    }

    public function create()
    {
        $data['getRole'] = RoleModel::all();
        return view('users.add', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:3|confirmed',
            'roles_id' => 'nullable|integer|exists:roles,id',
            'gender' => 'nullable|in:male,female',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = new User();
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->roles_id = $request->roles_id;
        $user->status = $request->has('status') ? 'active' : 'inactive';
        $user->gender = trim($request->gender);

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('profile_images', $fileName, 'public');
            $user->profile = $filePath;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User added successfully.');
    }

    public function edit($id)
    {
        $data['getRecord'] = User::find($id);
        $data['getRole'] = RoleModel::all();

        if (!$data['getRecord']) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        return view('users.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:3|confirmed',
            'roles_id' => 'nullable|integer|exists:roles,id',
            'gender' => 'nullable|in:male,female',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        $user->name = trim($request->name);
        $user->email = trim($request->email);
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->roles_id = $request->roles_id;
        $user->status = $request->has('status') ? 'active' : 'inactive';
        $user->gender = trim($request->gender);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile && Storage::exists('public/' . $user->profile)) {
                Storage::delete('public/' . $user->profile);
            }
            $file = $request->file('profile_picture');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('profile_images', $fileName, 'public');
            $user->profile = $filePath;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function profile()
    {
        // Get the currently authenticated user
        $user = Auth::user(); // This will fetch the currently logged-in user

        // Return the profile view with the user data
        return view('users.profile', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        if ($user->profile && Storage::exists('public/' . $user->profile)) {
            Storage::delete('public/' . $user->profile);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
