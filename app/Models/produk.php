<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class produk extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'produks';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'qty',
        'price',
        'kode', 
        'photo'
    ];
}
