<?php

namespace App\Models;

use App\Models\Mobil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Kategori extends Model
{
    use HasFactory;
    protected $table ="kategori";
    protected $fillable = [
        'nama',
        'deskripsi'
    ];


     /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function kategori_Mobil()
    {
        return $this->belongsToMany(Mobil::class,'kategori_Mobil','kategori_id','Mobil_id');
    }
}
