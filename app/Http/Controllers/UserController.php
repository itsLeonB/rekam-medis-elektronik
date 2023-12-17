<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // index all users
    public function index()
    {
        return response()->json(['users' => User::paginate(15)], 200);
    }


    // show the selected user
    public function show($id)
    {
        return response()->json(['user' => User::find($id)], 200);
    }


    // create a new user
    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => strip_tags($request->input('name')),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json($user, 201);
    }


    // update the selected user
    public function update(UserRequest $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $updateData = ['name' => strip_tags($request->input('name'))];

        if ($request->input('password')) {
            $updateData['password'] = Hash::make($request->input('password'));
            $updateData['password_changed_at'] = now();
        }

        if ($request->input('email')) {
            $updateData['email'] = $request->input('email');
            $updateData['email_verified_at'] = null;
        }

        $user->update($updateData);

        return response()->json($user, 200);
    }


    // delete the selected user
    public function destroy($user_id)
    {
        $currentUser = Auth::user();

        if ($currentUser->id != $user_id) {
            $user = User::findOrFail($user_id);
            $user->delete();

            return response()->json('User berhasil dihapus', 204);
        } else {
            return response()->json('Tidak dapat menghapus akun sendiri', 403);
        }
    }
}
