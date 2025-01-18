<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class peserta extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perusahaan',
        'pic',
        'no_hp',
        'email',
    ];
   // Relationship with Drtu
   public function drtu()
   {
       return $this->belongsTo(Drtu::class);
   }
    
}
