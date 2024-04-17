<?php

namespace App\Models;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mobil extends Model
{
    use HasFactory;

    protected $table = "Mobil";
    protected $fillable = [
        'judul',
        'kode_Mobil',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'deskripsi',
        'gambar'
    ];

    /**
     * The roles that belong to the Mobil
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function kategori_Mobil():BelongsToMany
    {
        return $this->belongsToMany(Kategori::class, 'kategori_Mobil', 'Mobil_id', 'kategori_id');
    }
}
