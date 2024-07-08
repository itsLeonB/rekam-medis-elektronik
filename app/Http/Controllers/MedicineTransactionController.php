<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineTransaction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MedicineTransactionController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->query('name');

        $medicinetransactions = MedicineTransaction::query()
            ->select('*')
            ->with('medicine')
            ->when($name, function ($query) use ($name) {
                return $query->whereHas('medicine', function ($query) use ($name) {
                    $query->where('name', 'like', '%' . addcslashes($name, '%_') . '%');
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return response()->json(['medicinetransactions' => $medicinetransactions], 200);
    }

    public function show($id)
    {
        try {
            $medicinetransaction = MedicineTransaction::with('medicine')->findOrFail($id);
            return response()->json($medicinetransaction, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'MedicineTransaction tidak ditemukan',
                'message' => $e->getMessage()
            ], 404);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data MedicineTransaction',
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {   
        try {
            $medicine = Medicine::findOrFail($request->input('id_medicine'));
    
            $medicinetransaction = MedicineTransaction::create([
                '_id' => (string) Str::uuid(),
                'id_transaction' => $request->input('id_transaction'),
                'id_medicine' => $request->input('id_medicine'),
                'quantity' => intval($request->input('quantity')),
                'note' => $request->input('note'),
            ]);
    
            // Kurangi quantity pada Medicine
            $medicine->quantity -= intval($request->input('quantity'));
            $medicine->save();
    
            return Inertia::location(route('medicinetransaction'));
            // return response()->json($medicinetransaction, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Medicine tidak ditemukan',
                'message' => $e->getMessage()
            ], 404);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'Gagal membuat MedicineTransaction baru',
                'message' => $th->getMessage()
            ], 500);
        }
    }    
    
    

    public function update(Request $request, $id)
    {
        try {
            
            $medicineTransaction = MedicineTransaction::findOrFail($id);
            
            // Ambil medicine berdasarkan id_medicine yang baru
            $medicine = Medicine::findOrFail($medicineTransaction->id_medicine);
            $medicine->quantity += $medicineTransaction->quantity;
            $medicine->save();

            // Update atribut-atribut yang dibutuhkan
            $medicineTransaction->id_transaction = $request->input('id_transaction');
            $medicineTransaction->id_medicine = $request->input('id_medicine');
            $medicineTransaction->quantity = intval($request->input('quantity'));
            $medicineTransaction->note = $request->input('note');

            // Simpan perubahan
            $medicineTransaction->save();

            // Kurangi quantity pada Medicine
            $medicine = Medicine::findOrFail($request->input('id_medicine'));
            $medicine->quantity -= intval($request->input('quantity'));
            $medicine->save();
            // Jika ada pengubahan quantity, proses juga pengurangan atau penambahan pada Medicine sesuai kebutuhan bisnis

            return Inertia::location(route('medicinetransaction'));
            // return response()->json($medicineTransaction, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Medicine Transaction tidak ditemukan',
                'message' => $e->getMessage()
            ], 404);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'Gagal memperbarui Medicine Transaction',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            // Temukan MedicineTransaction yang akan dihapus
            $medicineTransaction = MedicineTransaction::findOrFail($id);

            // Pastikan user yang sedang login memiliki izin untuk menghapus transaksi
            if ($request->user()->id != $medicineTransaction->user_id) {
                // Hapus MedicineTransaction
                MedicineTransaction::destroy($id);

                // Update quantity pada Medicine terkait
                $medicine = Medicine::findOrFail($medicineTransaction->id_medicine);
                $medicine->quantity += $medicineTransaction->quantity; // Tambahkan quantity yang dihapus
                $medicine->save();

                return Inertia::location(route('medicinetransaction'));
            } else {
                // Jika user mencoba menghapus transaksi sendiri, kembalikan pesan error
                return response()->json('Tidak dapat menghapus transaksi Anda sendiri', 403);
            }
        } catch (ModelNotFoundException $e) {
            // Tangkap error jika MedicineTransaction tidak ditemukan
            return response()->json([
                'error' => 'MedicineTransaction tidak ditemukan',
                'message' => $e->getMessage()
            ], 404);
        } catch (\Throwable $th) {
            // Tangkap error umum
            Log::error($th->getMessage());
            return response()->json([
                'error' => 'Gagal menghapus MedicineTransaction',
                'message' => $th->getMessage()
            ], 500);
        }
    }


    // public function getRoles()
    // {
    //     return Role::all()->pluck('name');
    // }

    public function getmedicine(Request $request)
    {
        $search = $request->query('search');
        $medicines = Medicine::where('name', 'like', '%' . $search . '%')->get();
        return response()->json($medicines);
    }
}
