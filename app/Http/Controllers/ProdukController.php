<?php

namespace App\Http\Controllers;

use App\Models\produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = produk::all();
        return view('admin.produk', compact('data'));
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
        $request->validate([
            'name' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'kode' => 'required', 
            'photo' => 'required'
        ]);

        $photo =  $this->uploadFile($request, 'photo');

        $produk = produk::create([
            'name' => $request->name,
            'qty' => $request->qty,
            'price' => $request->price,
            'kode' => $request->kode, 
            'photo' => $photo
        ]);

        return redirect()->route('produk.index');
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

    /**
     * Display the specified resource.
     */
    public function show(produk $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(produk $produk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'kode' => 'required', 
            'photo' => 'required'
        ]);
        $data = produk::find($id);
        $data->update($request->all());

        if ($request->file('photo')) {
            $data->photo = $this->uploadFile($request, 'photo');
            $data->save();
        }
        return redirect()->route('produk.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = produk::find($id);
        $data->delete();
        return redirect()->route('produk.index');

    }
}
