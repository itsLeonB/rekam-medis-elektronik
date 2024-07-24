<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\FhirResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
// use Spatie\Permission\Models\Role;
use App\Models\Role;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->query('name');

        $users = User::when($name, function ($query) use ($name) {
            return $query->where('name', 'like', '%' . addcslashes($name, '%_') . '%');
        })
            // ->withoutRole('admin') // broken
            ->paginate(15)
            ->withQueryString();

        return response()->json(['users' => $users], 200);
    }

    public function show($id)
    {
        try {
            $user = User::with('practitionerUser')->findOrFail($id);

            return response()->json($user, 200);
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
            // Log::info('Request received:', $request->all());
            $user = User::create([
                'name' => strip_tags($request->input('name')),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            if ($request->input('practitioner_id')) {
                $practitioner = FhirResource::where([
                    ['resourceType', 'Practitioner'],
                    ['id', $request->input('practitioner_id')]
                ])
                    ->firstOrFail();

                $user->practitionerUser()->save($practitioner);
            }

            $user->assignRole($request->input('role'));

            DB::commit();

            return response()->json($user, 201);
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

            $user->syncRoles($request->input('role'));

            DB::commit();

            return response()->json($user, 200);
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

            if ($currentUser->_id != $user_id) {
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

    public function getRoles()
    {
       try {
            $roles = Role::all()->pluck('name');
            return response()->json($roles, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengambil roles',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
