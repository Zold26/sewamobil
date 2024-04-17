<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    protected $table = "profile";
    protected $fillable =[
    'NomorSIM',
    'alamat',
    'noTelp',
    'photoProfile',
    'users_id'
    ];

    public function user(){
        return $this->belongsTo(User::class,'users_id');
    }
}
