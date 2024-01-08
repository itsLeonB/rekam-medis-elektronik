<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\PractitionerResource;
use App\Models\Fhir\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    // index all users
    public function index(Request $request)
    {
        $name = $request->query('name');

        if ($name) {
            $users = User::where('name', 'like', '%' . addcslashes($name, '%_') . '%')->paginate(15);
            return response()->json(['users' => $users], 200);
        }

        return response()->json(['users' => User::paginate(15)], 200);
    }


    // show the selected user
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            $pracResId = $user->practitionerUser()->first()->resource_id;

            return response()->json([
                'user' => $user,
                'practitioner' => new PractitionerResource(Resource::findOrFail($pracResId))
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }
    }

    // create a new user
    public function store(UserRequest $request)
    {
        try {
            $user = User::create([
                'name' => strip_tags($request->input('name')),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            $practitioner = Resource::where([
                ['res_type', 'Practitioner'],
                ['satusehat_id', $request->input('practitioner_id')]
            ])->first()->practitioner()->first();

            $user->practitionerUser()->save($practitioner);

            return response()->json(['user' => $user], 201);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'User gagal dibuat'], 500);
        }
    }


    // update the selected user
    public function update(UserRequest $request, $user_id)
    {
        try {
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

            return response()->json(['user' => $user], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'User gagal diperbarui'], 500);
        }
    }


    // delete the selected user
    public function destroy($user_id)
    {
        try {
            $currentUser = Auth::user();

            if ($currentUser->id != $user_id) {
                $user = User::findOrFail($user_id);
                $user->delete();

                return response()->json('User berhasil dihapus', 204);
            } else {
                return response()->json('Tidak dapat menghapus akun sendiri', 403);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'User gagal dihapus'], 500);
        }
    }
}
