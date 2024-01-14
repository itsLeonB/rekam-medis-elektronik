<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\PractitionerResource;
use App\Models\Fhir\Resource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->query('name');

        $users = User::when($name, function ($query) use ($name) {
            return $query->where('name', 'like', '%' . addcslashes($name, '%_') . '%');
        })->paginate(15)->withQueryString();

        return response()->json(['users' => $users], 200);
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->practitionerUser->count() > 0) {
                $practitioner = new PractitionerResource(data_get($user, 'practitionerUser.0.resource'));
            }

            return response()->json([
                'user' => $user,
                'practitioner' => $practitioner ?? null
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'User tidak ditemukan',
                'message' => $th->getMessage()
            ], 404);
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

            if ($request->input('practitioner_id')) {
                $practitioner = Resource::where('res_type', 'Practitioner')
                    ->where('satusehat_id', $request->input('practitioner_id'))
                    ->firstOrFail()
                    ->practitioner;

                $user->practitionerUser()->save($practitioner);

                $pracRes = new PractitionerResource($practitioner->resource);
            }


            DB::commit();

            return response()->json([
                'user' => $user,
                'practitioner' => $pracRes ?? null
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'Gagal membuat user baru',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(UserRequest $request, $user_id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($user_id);

            $updateData = [
                'name' => strip_tags($request->input('name')),
                'password' => $request->input('password') ? Hash::make($request->input('password')) : null,
                'password_changed_at' => $request->input('password') ? now() : null,
                'email' => $request->input('email'),
                'email_verified_at' => $request->input('email') ? null : $user->email_verified_at,
            ];

            $user->update($updateData);

            DB::commit();

            return response()->json(['user' => $user], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return response()->json('User tidak ditemukan', 404);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'Gagal memperbarui user',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $user_id)
    {
        DB::beginTransaction();
        try {
            $currentUser = $request->user();

            if ($currentUser->id != $user_id) {
                User::destroy($user_id);

                DB::commit();

                return response()->json('User berhasil dihapus', 204);
            } else {
                DB::rollback();
                return response()->json('Tidak dapat menghapus akun sendiri', 403);
            }
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return response()->json('User tidak ditemukan', 404);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'User gagal dihapus',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
