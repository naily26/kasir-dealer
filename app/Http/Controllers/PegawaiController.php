<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Hash;

class PegawaiController extends Controller
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
        $data = pegawai::all();
        //dd($data);
        return view('admin.pegawai', compact('data'));
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
        'no_hp' => 'required',
        'email' => 'required|email|unique:users',
        'nik' => 'required|min:16|unique:pegawais',
     ]);   

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->nik),
            'role' => 'user'
         ]);
    
         $pegawai = pegawai::create([
            'name' => $request->name,
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'user_id' => $user->id,
         ]);
    
     return redirect()->route('pegawai.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(pegawai $pegawai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(pegawai $pegawai)
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
            'no_hp' => 'required',
            'email' => 'required|email',
            'nik' => 'required|min:16',
        ]);
        
        $pegawai = pegawai::find($id);
        $pegawai->update($request->all());
        $user = User::find($pegawai->user_id);
        $user->update([
            'email' => $request->email,
            'password' => Hash::make($pegawai->nik),
        ]);
        return redirect()->route('pegawai.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pegawai = pegawai::find($id);
        $user = User::find($pegawai->user_id);
        $user->delete();
        return redirect()->route('pegawai.index');
    }
}
