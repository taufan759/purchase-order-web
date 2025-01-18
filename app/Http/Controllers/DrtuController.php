<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Drtu;
use Illuminate\Http\Request;
use PDF; // Menggunakan package DomPDF untuk PDF

class DrtuController extends Controller
{
    // Menampilkan daftar DRTU dengan fitur pencarian
    public function index(Request $request)
{
    $query = Drtu::query();
    $filterYear = $request->input('filter_year'); // Ambil filter tahun dari request
    $searchBy = $request->input('search_by', 'all'); // Ambil opsi search_by (default: 'all')


    // Jika ada filter tahun, tambahkan ke query
    if (!empty($filterYear)) {
        $query->whereYear('doc_date', $filterYear); // Filter berdasarkan tahun dari kolom doc_date
    }

    // Jika ada input pencarian
    if ($request->has('search')) {
        $search = $request->get('search');

        // Jika pencarian dimulai dengan "DRTU"
        if (str_starts_with($search, 'DRTU')) {
            // Hilangkan prefix "DRTU"
            $search = substr($search, 4);
        }

        // Cek apakah pencarian hanya berdasarkan ID atau semua kolom
        if ($searchBy === 'id') {
            // Jika pencarian berdasarkan ID saja
            $query->where(function($q) use ($search) {
                $q->where('id', $search) // Pencarian jika pengguna memasukkan ID seperti "1"
                  ->orWhereRaw("LPAD(id, 4, '0') LIKE ?", ["%$search%"]) // Pencarian dengan leading zeros seperti "0001"
                  ->orWhereRaw("CONCAT('DRTU', LPAD(id, 4, '0')) LIKE ?", ["%$search%"]); // Pencarian dengan format "DRTU0001"
            });
        } else {
            // Pencarian berdasarkan semua kolom
            $query->where(function($q) use ($search) {
                $q->where('id', $search) // Pencarian jika pengguna memasukkan ID seperti "1"
                  ->orWhereRaw("LPAD(id, 4, '0') LIKE ?", ["%$search%"]) // Pencarian dengan leading zeros seperti "0001"
                  ->orWhereRaw("CONCAT('DRTU', LPAD(id, 4, '0')) LIKE ?", ["%$search%"]) // Pencarian dengan format "DRTU0001"
                  ->orWhere('number_of_purchase_requisition', 'LIKE', "%{$search}%") // Pencarian berdasarkan no PR
                  ->orWhere('number_of_memo_dinas', 'LIKE', "%{$search}%") // Pencarian berdasarkan no Memo Dinas
                  ->orWhere('id_tahun', 'LIKE', "%{$search}%"); // Pencarian berdasarkan no Memo Dinas

            });
        }
    }

    // Tambahkan urutan ID descending
    $query->orderBy('id', 'desc');

    // Paginate the result
    $drtus = $query->paginate(10); // Menggunakan paginasi

    // Mengirimkan filter tahun dan opsi search_by yang digunakan ke view
    return view('drtus.index', compact('drtus', 'filterYear', 'searchBy'));
}





    // Menampilkan form create DRTU baru
    public function create()
    {
        return view('drtus.create');
    }

    // Menyimpan data DRTU dan peserta
    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'number_of_purchase_requisition' => 'required',
            'number_of_memo_dinas' => 'required',
            'doc_date' => 'required|date',
            'description' => 'required',
            'penanggung_jawab' => 'required|string',
            'metode_pengadaan' => 'required|string',
            'peserta.*.nama_perusahaan' => 'required|string',
            'peserta.*.pic' => 'required|string',
            'peserta.*.no_hp' => 'required|string|regex:/^[\d\s\-\/\+]+$/|max:255',
            'peserta.*.email' => 'required|email',
        ]);

        // Ambil ID terakhir dari database
        $lastDrtu = Drtu::orderBy('id', 'desc')->first();
        $newId = isset($lastDrtu) ? sprintf('%04d', $lastDrtu->id + 1) : '0001';

        // Simpan DRTU
        $drtu = Drtu::create([
            'id' => $newId,
            'number_of_purchase_requisition' => $request->number_of_purchase_requisition,
            'number_of_memo_dinas' => $request->number_of_memo_dinas,
            'doc_date' => $request->doc_date,
            'description' => $request->description,
            'penanggung_jawab' => $request->penanggung_jawab,
            'metode_pengadaan' => $request->metode_pengadaan,
        ]);

        // Simpan peserta
        if ($request->has('peserta') && is_array($request->peserta)) {
            foreach ($request->peserta as $peserta) {
                $drtu->peserta()->create([
                    'nama_perusahaan' => $peserta['nama_perusahaan'],
                    'pic' => $peserta['pic'],
                    'no_hp' => $peserta['no_hp'],
                    'email' => $peserta['email'],
                ]);
            }
        }

        return redirect()->route('drtus.index')->with('success', 'DRTU and Peserta created successfully.');
    }

    // Menampilkan DRTU berdasarkan ID
    public function show(Drtu $drtu)
    {
        return view('drtus.show', compact('drtu'));
    }

    // Menampilkan form edit DRTU
    public function edit($id)
    {
        $drtu = Drtu::with('peserta')->findOrFail($id);
        return view('drtus.edit', compact('drtu'));
    }

    // Memperbarui data DRTU dan peserta
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'doc_date' => 'required|date',
            'number_of_purchase_requisition' => 'required|string|max:255',
            'number_of_memo_dinas' => 'required|string|max:255',
            'metode_pengadaan' => 'required|string|max:255',
            'description' => 'required|string',
            'penanggung_jawab' => 'required|string|in:Hasmawati Z,Hendriyanto',
            'peserta.*.nama_perusahaan' => 'required|string|max:255',
            'peserta.*.pic' => 'required|string|max:255',
            'peserta.*.no_hp' => 'required|string|regex:/^[\d\s\-\/\+]+$/|max:255',
            'peserta.*.email' => 'required|email|max:255',
        ]);

        // Update DRTU
        $drtu = Drtu::findOrFail($id);
        $drtu->update($request->only('doc_date', 'number_of_purchase_requisition', 'number_of_memo_dinas', 'metode_pengadaan', 'description', 'penanggung_jawab'));

        // Hapus peserta lama
        $drtu->peserta()->delete();

        // Tambahkan peserta baru
        foreach ($request->peserta as $peserta) {
            $drtu->peserta()->create([
                'nama_perusahaan' => $peserta['nama_perusahaan'],
                'pic' => $peserta['pic'],
                'no_hp' => $peserta['no_hp'],
                'email' => $peserta['email'],
            ]);
        }

        return redirect()->route('drtus.index')->with('success', 'DRTU updated successfully.');
    }

    // Menghapus DRTU
    public function destroy(Drtu $drtu)
    {
        $drtu->delete();
        return redirect()->route('drtus.index')->with('success', 'DRTU deleted successfully.');
    }

    // Generate PDF dari DRTU berdasarkan ID
    public function generatePDF($id)
    {
        $drtu = Drtu::findOrFail($id);

        // Render PDF menggunakan view
        $pdf = PDF::loadView('drtus.pdf', compact('drtu'))
        ->setPaper('A4','potrait');

        // Menampilkan file PDF atau mengunduhnya
        return $pdf->stream('DRTU_' . sprintf('%04d', $drtu->id) . '.pdf'); // Bisa menggunakan ->download() untuk mengunduh langsung
    }
}
