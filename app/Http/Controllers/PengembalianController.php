<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Mobil;
use App\Models\User;
use App\Models\Profile;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PeminjamanController extends Controller
{
    public function index(){
        $iduser = Auth::id();
        $profile = Profile::where('users_id',$iduser)->first();
        $Mobil = Mobil::where('status','diPeminjaman')->get();
        $user = User::all();
        $peminjam = Profile::where('users_id','>','1')->get();

        return view('Peminjaman.Peminjaman',['profile'=>$profile,'users'=>$user,'Mobil'=>$Mobil, 'Peminjaman'=>$Peminjaman]);
    }

    public function pengembalian(Request $request ){

        $Peminjaman = Peminjaman::where('users_id',$request->users_id)->where('Mobil_id',$request->Mobil_id)
        ->where('tanggal_pengembalian',null);
        $dataPeminjaman = $Peminjaman->first();
        $count = $Peminjaman->count();

        if($count == 1){
            try {
                DB::beginTransaction();
                //update data tanggal pengembalian
                $dataPinjaman->tanggal_pengembalian = Carbon::now()->toDateString();
                $dataPinjaman->save();
                //update status Mobil
                $Mobil = Mobil::findOrFail($request->Mobil_id);
                $Mobil->status = 'In Stock';
                $Mobil->save();
                DB::commit();
                Alert::success('Berhasil', 'Berhasil Mengembalikan Mobil');
                return redirect('/peminjaman');
            } catch (\Throwable $th) {
                DB::rollback();
            }
        }
        else {
            Alert::warning('Gagal', 'Mobil yang pinjam salah atau tidak ada');
            return redirect('/pengembalian');
        }

    }

}
