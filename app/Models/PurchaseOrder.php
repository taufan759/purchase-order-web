<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_of_purchase_requisition',
        'number_of_memo_dinas',
        'doc_date',
        'description',
        'penanggung_jawab',  // Kolom baru
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    

public static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        // Ambil tahun dari doc_date
        $docYear = Carbon::parse($model->doc_date)->year;

        // Cek jumlah record di tahun yang sesuai dengan doc_date
        $latest = static::whereYear('doc_date', $docYear)->orderBy('id', 'desc')->first();

        // Cek jumlah record di tahun yang sesuai dengan doc_date
$latest = static::whereYear('doc_date', $docYear)->orderBy('id', 'desc')->first();

// Jika ada record di tahun doc_date
if ($latest && isset($latest->id_tahun)) {
    $idParts = explode('-', $latest->id_tahun);
    $nextId = isset($idParts[1]) ? intval($idParts[1]) + 1 : 1;
} else {
    $nextId = 1;
}

// Set nilai id_tahun menjadi 'tahun-sequence', misalnya '2025-0001'
$model->id_tahun = $docYear . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

    });
}

}
