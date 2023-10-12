<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class transaksi extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'transaksis';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'cust_id',
        'produk_id',
        'pegawai_id',
        'jenis', 
        'jumlah',
        'biaya_tambahan',
        'lama_kredit',
        'dp', 
        'angsuran',
        'berkas_pembelian', 
    ];
}
