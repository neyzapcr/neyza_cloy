<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dataUser'] = User::all();
        return view('admin.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required',
            'email'             => 'required|email',
            'password'          => 'required|min:6',
            'profile_picture'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data['name'] = $request->name;
        $data['email']  = $request->email;
        $data['password']   = Hash::make($request->password);

        // Upload foto jika ada
        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')
                ->store('profile_pictures', 'public');
        }

        User::create($data);

        return redirect()->route('user.index')->with('success', 'Penambahan user berhasil!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'              => 'required',
            'email'             => 'required|email',
            'profile_picture'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Update password hanya jika diisi
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // Update profile picture
        if ($request->hasFile('profile_picture')) {

            // Hapus foto lama jika ada
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Upload foto baru
            $user->profile_picture = $request->file('profile_picture')
                ->store('profile_pictures', 'public');
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Update user berhasil!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Hapus gambar jika ada
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }

    public function deletePhoto($id)
{
    $user = User::findOrFail($id);

    // Hapus file dari storage
    if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
        Storage::disk('public')->delete($user->profile_picture);
    }

    // Update field jadi null
    $user->profile_picture = null;
    $user->save();

    return back()->with('success', 'Foto berhasil dihapus!');
}

}


