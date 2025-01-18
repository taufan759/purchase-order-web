<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class DRTU extends Model
{
    use HasFactory;

    // Opsional: Tentukan nama tabel jika perlu, Laravel secara otomatis menganggap 'drtus'
     protected $table = 'drtus';

    // Tentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'doc_date',
        'number_of_purchase_requisition',
        'number_of_memo_dinas',
        'metode_pengadaan',
        'description',
        'penanggung_jawab',
    ];

    // Opsional: Mengatur casting tipe data untuk kolom tertentu
    protected $casts = [
        'doc_date' => 'date',
        'no_hp' => 'integer',
        
    ];

    // Relationship with Peserta
    public function peserta()
    {
        return $this->hasMany(Peserta::class, 'drtu_id');
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
