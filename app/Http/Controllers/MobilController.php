<?php

namespace App\Http\Controllers;

use File;
use App\Models\Mobil;
use App\Models\Profile;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class MobilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('search')){
            $Mobil = Mobil::where('judul','like','%'.$request->search.'%')->paginate(6);
        }
        else{
            $Mobil = Mobil::paginate(6);
        }
        $iduser = Auth::id();
        $profile = Profile::where('users_id', $iduser)->first();
        return view('Mobil.tampil', ['Mobil' => $buku, 'profile' => $profile]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategori = Kategori::all();
        $Mobil = Mobil::all();
        $iduser = Auth::id();
        $profile = Profile::where('users_id', $iduser)->first();
        return view('Mobil.tambah', ['Mobil' => $Mobil, 'profile' => $profile, 'kategori'=>$kategori]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'judul' => 'required',
                'kode_Mobil'=>'required|unique:Mobil',
                'kategori_Mobil'=>'required',
                'pengarang' => 'required',
                'penerbit' => 'required',
                'tahun_terbit' => 'required',
                'deskripsi' => 'required',
                'gambar' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'judul.required' => 'judul tidak boleh kosong',
                'kode_Mobil.required'=> 'Kode Mobil Tidak Boleh Kosong',
                'kode_Mobil.unique'=> 'Kode Mobil Telah Tersedia',
                'kategori_Mobil.required' =>'Harap masukan kategori',
                'pengarang.required' => 'pengarang tidak boleh kosong',
                'penerbit.requiered' => 'penerbit tidak boleh kosong',
                'tahun_terbit.required' => 'harap isi tahun terbit',
                'deskripsi.required' => 'deskripsi tidak boleh kosong',
                'gambar.mimes' => 'Gambar Harus Berupa jpg,jpeg,atau png',
                'gambar.max' => 'ukuran gambar tidak boleh lebih dari 2048 MB',
            ],
        );

        if ($request->hasFile('gambar')) {
            $nama_gambar = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('images'), $nama_gambar);

            $Mobil = Mobil::create([
                'judul'=>$request['judul'],
                'kode_Mobil'=>$request['kode_Mobil'],
                'pengarang'=>$request['pengarang'],
                'penerbit'=>$request['penerbit'],
                'tahun_terbit'=>$request['tahun_terbit'],
                'deskripsi'=>$request['deskripsi'],
                'gambar'=>$nama_gambar
            ]);
            $Mobil->kategori_Mobil()->sync($request->kategori_Mobil);
        } else {
            $Mobil = Mobil::create($request->all());
            $Mobil->kategori_Mobil()->sync($request->kategori_Mobil);
        }

        Alert::success('Berhasil', 'Berhasil Menambakan Data Mobil');
        return redirect('/Mobil');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Mobil = Mobil::find($id);
        $iduser = Auth::id();
        $profile = Profile::where('users_id', $iduser)->first();
        return view('Mobil.detail', ['Mobil' => $Mobil, 'profile' => $profile]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $iduser = Auth::id();
        $kategori = Kategori::all();
        $profile = Profile::where('users_id', $iduser)->first();
        $Mobil = Mobil::find($id);
        return view('Mobil.edit', ['Mobil' => $Mobil, 'profile' => $profile,'kategori'=>$kategori]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Mobil = Mobil::find($id);
        $kategori= Kategori::find($id);
        $request->validate(
            [
                'judul' => 'required',
                'pengarang' => 'required',
                'penerbit' => 'required',
                'tahun_terbit' => 'required',
                'deskripsi' => 'required',
                'gambar' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'judul.required' => 'judul tidak boleh kosong',
                'pengarang.required' => 'pengarang tidak boleh kosong',
                'penerbit.requiered' => 'penerbit tidak boleh kosong',
                'tahun_terbit.required' => 'harap isi tahun terbit',
                'deskripsi.required' => 'deskripsi tidak boleh kosong',
                'gambar.mimes' => 'Gambar Harus Berupa jpg,jpeg,atau png',
                'gambar.max' => 'ukuran gambar tidak boleh lebih dari 2048 MB',
            ],
        );

        if ($request->has('gambar')) {
            $path = 'images/';
            File::delete($path . $Mobil->gambar);

            $nama_gambar = time() . '.' . $request->gambar->extension();

            $request->gambar->move(public_path('images'), $nama_gambar);

            $Mobil->gambar = $nama_gambar;

            $Mobil->kategori_Mobil()->sync($request->kategori_Mobil);
            $Mobil->save();
        }
        $Mobil->judul = $request->judul;
        $Mobil->pengarang = $request->pengarang;
        $Mobil->penerbit = $request->penerbit;
        $Mobil->tahun_terbit = $request->tahun_terbit;
        $Mobil->deskripsi = $request->deskripsi;
        $Mobil->kategori_Mobil()->sync($request->kategori_Mobil);
        $Mobil->save();

        Alert::success('Berhasil', 'Update Berhasil');
        return redirect('/Mobil');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Mobil = Mobil::find($id);

        $Mobil->delete();

        Alert::success('Berhasil', 'Mobil Berhasil Terhapus');
        return redirect('Mobil');
    }
}
