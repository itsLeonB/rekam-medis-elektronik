<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\PractitionerResource;
use App\Models\Fhir\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->query('name');

        if ($name) {
            $users = User::where('name', 'like', '%' . addcslashes($name, '%_') . '%')->paginate(15)->withQueryString();
            return response()->json(['users' => $users], 200);
        }

        return response()->json(['users' => User::paginate(15)->withQueryString()], 200);
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->practitionerUser()->count() == 0) {
                return response()->json([
                    'user' => $user,
                    'practitioner' => null
                ], 200);
            }

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

    public function store(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => strip_tags($request->input('name')),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            $practitioner = Resource::where([
                ['res_type', 'Practitioner'],
                ['satusehat_id', $request->input('practitioner_id')]
            ])->first()->practitioner;

            $user->practitionerUser()->save($practitioner);

            DB::commit();

            $pracResId = $user->practitionerUser()->first()->resource_id;

            return response()->json([
                'user' => $user,
                'practitioner' => new PractitionerResource(Resource::findOrFail($pracResId))
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return response()->json(['message' => 'User gagal dibuat'], 500);
        }
    }

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
