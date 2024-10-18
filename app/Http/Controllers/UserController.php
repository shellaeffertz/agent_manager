<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request) {

        return Inertia::render('Users', [
            'users' => User::where('id', '<>', $request->user()->id)
                            ->orderBy('created_at', 'DESC')
                            ->paginate(10)
        ]);
    }

    public function store(Request $request) {

        $attributes = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'unique:users'],
            'role' => ['required'],
            'password' => ['required', 'confirmed'],
        ]);

        User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'role' => $attributes['role'],
            'password' => Hash::make($attributes['password'])
        ]);

        return redirect()->route('users')->banner('User Added Successfully');
    }

    public function update(Request $request) {

        $attributes = $request->validate([
            'name' => ['required'],
            'email' => ['required'],
            'role' => ['required']
        ]);

        $user = User::findOrFail($request->id);

        if(strtolower($user->email) !== strtolower($attributes['email'])) {
            $request->validate([
                'email' => ['unique:users']
            ]);
        }

        $user->update($attributes);

        return redirect()->route('users')->banner('User Updated Successfully');
    }

    public function destroy(Request $request) {

        $user = User::findOrFail($request->id);

        $user->delete();

        return redirect()->route('users')->banner('User Deleted Successfully');
    }
}
