<?php

namespace App\Http\Controllers;

use App\Models\transaksi;
use App\Models\produk;
use App\Models\customer;
use App\Models\pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = transaksi::all();
        $produk = produk::all();
        $cust = customer::all();
        return view('user.transaksi', compact('data', 'produk', 'cust'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pegawai = pegawai::where('user_id', Auth::user()->id)->first();
        $request->pegawai_id = $pegawai->id;
        $trans = transaksi::create([
            'pegawai_id' => $pegawai->id,
            'cust_id' => $request->cust_id,
            'produk_id' => $request->produk_id,
            'jenis' => $request->jenis, 
            'jumlah' => $request->jumlah,
            'biaya_tambahan' => $request->biaya_tambahan,
            'lama_kredit' => $request->lama_kredit,
            'dp' => $request->dp, 
            'angsuran' => $request->angsuran,
            'berkas_pembelian' => $this->uploadFile($request, 'berkas_pembelian'),
        ]);
        return redirect()->route('transaksi.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = transaksi::find($id);
        $data->update($request->all());
        if ($request->file('berkas_pembelian')) {
            $data->berkas_pembelian = $this->uploadFile($request, 'berkas_pembelian');
            $data->save();
        }
        return redirect()->route('transaksi.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = transaksi::find($id);
        $data->delete();
        return redirect()->route('transaksi.index');
    }

    public function uploadFile(Request $request, $oke)
    {
        $result = '';
        $file = $request->file($oke);
        $name = $file->getClientOriginalName();
        $extension = explode('.', $name);
        $extension = strtolower(end($extension));
        $key = rand() . '-' . $oke;
        $tmp_file_name = "{$key}.{$extension}";
        $tmp_file_path = "admin/" . $oke . "/";
        $file->move($tmp_file_path, $tmp_file_name);
        $result = url('/') . '/' . 'admin/' . $oke . '' . '/' . $tmp_file_name;
        return $result;
    }
}
