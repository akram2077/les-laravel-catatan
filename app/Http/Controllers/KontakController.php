<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kontak; 

class KontakController extends Controller
{
    // Menampilkan daftar kontak
    public function index()
    {
        $kontak = Kontak::all(); 
        return view('kontak.index', compact('kontak')); 
    }

    // Menampilkan form tambah kontak
    public function create()
    {
        return view('kontak.create');
    }

    // Menyimpan data inputan form ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            // Menambahkan aturan unique dan mengabaikan baris ini jika diperlukan (untuk store baru cukup 'unique:kontak')
            'email' => 'required|email|max:255|unique:kontak,email',
            'pesan' => 'required|string',
        ], [
            // Pesan warning/error dalam Bahasa Indonesia
            'nama.required' => 'Kolom nama wajib diisi.',
            'nama.max' => 'Nama maksimal tidak boleh lebih dari 100 karakter.',
            
            'email.required' => 'Kolom email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.max' => 'Email maksimal tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            
            'pesan.required' => 'Kolom pesan wajib diisi.',
        ]);

        Kontak::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'pesan' => $request->pesan,
        ]);

        return redirect('/kontak')->with('sukses', 'Pesan Anda berhasil dikirim dan disimpan!');
    }

    // Menampilkan detail kontak berdasarkan ID
    public function show($id)
    {
        $kontak = Kontak::findOrFail($id);
        return view('kontak.show', compact('kontak'));
    }

    public function edit($id)
    {
        $kontak = Kontak::findOrFail($id);
        return view('kontak.edit', compact('kontak'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255', 
            'email' => 'required|email|max:255|unique:kontak,email,' . $id,
            'pesan' => 'required|string',
        ], [
            // Pesan warning/error bahasa Indonesia untuk update
            'nama.required' => 'Kolom nama wajib diisi.',
            'email.required' => 'Kolom email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh kontak lain.',
            'pesan.required' => 'Kolom pesan wajib diisi.',
        ]);

        $kontak = Kontak::findOrFail($id);
        
        $kontak->update($request->only(['nama', 'email', 'pesan']));

        return redirect("/kontak/{$id}/edit")->with('sukses', 'Kontak berhasil diperbarui!');
    }
    public function destroy($id)
{
    $kontak= kontak::findOrFail($id);
    $kontak->delete();
    return redirect('/kontak')->with('sukses', 'Catatan berhasil dihapus.');
}
}