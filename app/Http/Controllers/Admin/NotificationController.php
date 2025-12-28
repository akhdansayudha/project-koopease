<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    // Halaman Utama (History Blast)
    public function index()
    {
        // UPDATE: Menambahkan 'type' ke select dan groupBy
        $blasts = Notification::select(
            'batch_id',
            'raw_message',
            'created_at',
            'type', // <--- PENTING: Mengambil kolom type
            DB::raw('count(*) as recipient_count'),
            DB::raw('MAX(id) as id')
        )
            ->whereNotNull('batch_id')
            ->groupBy('batch_id', 'raw_message', 'created_at', 'type') // <--- PENTING: Group by type
            ->latest()
            ->paginate(10);

        return view('admin.notifications.index', compact('blasts'));
    }

    // Form Buat Notifikasi
    public function create()
    {
        return view('admin.notifications.create');
    }

    // Proses Kirim Blast
    public function store(Request $request)
    {
        // UPDATE: Validasi Type
        $request->validate([
            'target' => 'required|in:all,promo',
            'type' => 'required|in:info,promo,order', // <--- Validasi Type
            'message' => 'required|string|max:255',
        ]);

        $query = User::where('role', 'mahasiswa');

        if ($request->target === 'promo') {
            $query->where('notify_promo', true);
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            return back()->with('error', 'Tidak ada user yang sesuai kriteria target.');
        }

        $dataToInsert = [];
        $now = now();
        $batchId = Str::uuid();

        foreach ($users as $user) {
            $personalizedMessage = str_replace(
                ['{nama}', '{email}'],
                [$user->name, $user->email],
                $request->message
            );

            $dataToInsert[] = [
                'user_id' => $user->id,
                'order_id' => null,
                'type' => $request->type, // <--- PENTING: Simpan Type ke Database
                'message' => $personalizedMessage,
                'raw_message' => $request->message,
                'batch_id' => $batchId,
                'is_read' => false,
                'created_at' => $now,
            ];
        }

        Notification::insert($dataToInsert);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi tipe ' . ucfirst($request->type) . ' berhasil dikirim ke ' . count($dataToInsert) . ' pengguna.');
    }

    // Detail Batch
    public function show($id)
    {
        $ref = Notification::findOrFail($id);

        $notifications = Notification::with('user')
            ->where('batch_id', $ref->batch_id)
            ->get();

        return view('admin.notifications.show', compact('notifications', 'ref'));
    }

    // Hapus Batch
    public function destroy($id)
    {
        $ref = Notification::findOrFail($id);

        Notification::where('batch_id', $ref->batch_id)->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Berhasil menghapus batch notifikasi.');
    }
}
