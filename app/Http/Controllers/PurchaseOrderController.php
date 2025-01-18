<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    $filterYear = $request->input('filter_year'); // Ambil filter tahun dari request

    // Query utama
    $orders = PurchaseOrder::with('items')
        ->when($search, function ($query, $search) {
            $cleanedSearch = ltrim($search, 'OE');

            $query->where(function ($q) use ($cleanedSearch, $search) {
                if (is_numeric($cleanedSearch)) {
                    $formattedId = sprintf('%04d', $cleanedSearch);
                    $q->where('id', $formattedId);
                }
                $q->orWhere('number_of_purchase_requisition', 'LIKE', "%{$search}%")
                  ->orWhere('number_of_memo_dinas', 'LIKE', "%{$search}%")
                  ->orWhere('id_tahun', 'LIKE', "%{$search}%");
            });
        })
        ->when($filterYear, function ($query, $filterYear) {
            $query->whereYear('doc_date', $filterYear);
        })
        ->orderBy('id', 'desc')
        ->get();

    $userRole = Auth::user()->role;

    return view('purchase_orders.index', compact('orders', 'search', 'filterYear', 'userRole'));
}





    

    public function create()
    {
        return view('purchase_orders.create');
    }


    public function store(Request $request)
{
    // Validasi input form
    $request->validate([
        'number_of_purchase_requisition' => 'required',
        'number_of_memo_dinas' => 'required',
        'doc_date' => 'required|date',
        'description' => 'required',
        'penanggung_jawab' => 'required|string',
        'items.*.deskripsi' => 'required|string',
        'items.*.qty' => 'required|integer',
        'items.*.satuan' => 'required|string',
        'items.*.hps_satuan' => 'required|numeric',
        'items.*.dasar_acuan_harga' => 'required|string',
    ]);

    // Ambil ID terakhir dari database
    $lastOrder = PurchaseOrder::orderBy('id', 'desc')->first();
    $newId = isset($lastOrder) ? sprintf('%04d', $lastOrder->id + 1) : '0001';

    // Simpan Purchase Order
    $purchaseOrder = PurchaseOrder::create([
        'id' => $newId,
        'number_of_purchase_requisition' => $request->number_of_purchase_requisition,
        'number_of_memo_dinas' => $request->number_of_memo_dinas,
        'doc_date' => $request->doc_date,
        'description' => $request->description,
        'penanggung_jawab' => $request->penanggung_jawab,
    ]);

    // Simpan Item Purchase Order dengan menghitung HPS Total
    foreach ($request->items as $item) {
        $purchaseOrder->items()->create([
            'deskripsi' => $item['deskripsi'],
            'qty' => $item['qty'],
            'satuan' => $item['satuan'],
            'hps_satuan' => $item['hps_satuan'],
            'hps_total' => $item['qty'] * $item['hps_satuan'], // Hitung HPS Total di backend
            'dasar_acuan_harga' => $item['dasar_acuan_harga'],
        ]);
    }

    return redirect()->route('purchase_orders.index')->with('success', 'Purchase order created successfully');
}

    

    public function edit($id)
{
    // Ambil data purchase order berdasarkan ID
    $purchaseOrder = PurchaseOrder::findOrFail($id);

    // Ambil item-item yang terkait dengan purchase order
    $items = $purchaseOrder->items;

    // Tampilkan form edit dengan data purchase order dan item-item
    return view('purchase_orders.edit', compact('purchaseOrder', 'items'));
}

public function update(Request $request, PurchaseOrder $purchaseOrder)
{
    $request->validate([
        'number_of_purchase_requisition' => 'required',
        'number_of_memo_dinas' => 'required',
        'doc_date' => 'required|date',
        'description' => 'required',
        'penanggung_jawab' => 'required|string|in:Hasmawati Z,Hendriyanto,Ridi Djajakusuma',
        'items.*.deskripsi' => 'required|string',
        'items.*.qty' => 'required|integer',
        'items.*.satuan' => 'required|string',
        'items.*.hps_satuan' => 'required|numeric',
        'items.*.dasar_acuan_harga' => 'required|string',
    ]);

    // Update data Purchase Order
    $purchaseOrder->update([
        'number_of_purchase_requisition' => $request->number_of_purchase_requisition,
        'number_of_memo_dinas' => $request->number_of_memo_dinas,
        'doc_date' => $request->doc_date,
        'description' => $request->description,
        'penanggung_jawab' => $request->penanggung_jawab,
    ]);

    // Hapus item lama
    $purchaseOrder->items()->delete();

    // Simpan item baru dengan perhitungan HPS Total
    foreach ($request->items as $item) {
        $purchaseOrder->items()->create([
            'deskripsi' => $item['deskripsi'],
            'qty' => $item['qty'],
            'satuan' => $item['satuan'],
            'hps_satuan' => $item['hps_satuan'],
            'hps_total' => $item['qty'] * $item['hps_satuan'], // Hitung HPS Total di backend
            'dasar_acuan_harga' => $item['dasar_acuan_harga'],
        ]);
    }

    return redirect()->route('purchase_orders.index')
                            ->with('success', 'Purchase order updated successfully');
}

public function generatePDF($id)
{
    // Pastikan pengguna login
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $purchaseOrder = PurchaseOrder::findOrFail($id);
    $items = $purchaseOrder->items;

    $pdf = FacadePdf::loadView('purchase_orders.pdf', compact('purchaseOrder', 'items'))
        ->setPaper('A4', 'portrait');

    return $pdf->stream('OE' . ($purchaseOrder->id_tahun) . '.pdf');
}


    public function preview($id)
{
    // Ambil data purchase order berdasarkan ID
    $purchaseOrder = PurchaseOrder::findOrFail($id);

    // Ambil item-item yang terkait dengan purchase order
    $items = $purchaseOrder->items; // Sesuaikan dengan relasi yang Anda gunakan

    // Tampilkan view preview dengan data purchase order dan items
    return view('purchase_orders.preview', compact('purchaseOrder', 'items'));
}
public function destroy($id)
{
    $order = PurchaseOrder::findOrFail($id);
    $order->delete();

    return redirect()->route('purchase_orders.index')->with('success', 'Purchase Order berhasil dihapus.');
}



}
