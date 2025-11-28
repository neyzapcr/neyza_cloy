<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filterableColumns = ['gender'];
        $searchableColumns = ['first_name', 'last_name', 'email', 'phone'];

        $data['dataPelanggan'] = Pelanggan::filter($request, $filterableColumns)
            ->search($request, $searchableColumns)
            ->paginate(10)
            ->onEachSide(2);

        return view('admin.pelanggan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pelanggan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pelanggan = Pelanggan::create($request->only([
            'first_name', 'last_name', 'birthday', 'gender', 'email', 'phone'
        ]));

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $file->store("pelanggan/{$pelanggan->pelanggan_id}", "public");
            }
        }

        return redirect()->route('pelanggan.index')
            ->with('success', 'Penambahan Data Berhasil!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $dataPelanggan = Pelanggan::findOrFail($id);

        // scan folder foto berdasarkan pelanggan_id
        $folder = "pelanggan/" . $dataPelanggan->pelanggan_id;
        $fotos = Storage::disk('public')->files($folder);

        return view('admin.pelanggan.detail', compact('dataPelanggan', 'fotos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dataPelanggan = Pelanggan::findOrFail($id);

        $folder = "pelanggan/" . $dataPelanggan->pelanggan_id;
        $fotos = Storage::disk('public')->files($folder);

        return view('admin.pelanggan.edit', compact('dataPelanggan', 'fotos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $pelanggan->first_name = $request->first_name;
        $pelanggan->last_name  = $request->last_name;
        $pelanggan->birthday   = $request->birthday;
        $pelanggan->gender     = $request->gender;
        $pelanggan->email      = $request->email;
        $pelanggan->phone      = $request->phone;

        // simpan foto ke folder pelanggan/{id}
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $file->store("pelanggan/{$pelanggan->pelanggan_id}", "public");
            }
        }

        $pelanggan->save();

        return redirect()->route('pelanggan.show', $id)
            ->with('success', 'Perubahan Data Berhasil!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data berhasil dihapus');
    }

    /**
     * Hapus satu foto pelanggan (Cara B).
     */
    public function destroyFoto(Request $request, $id)
    {
        Pelanggan::findOrFail($id);

        $foto = $request->foto; // contoh: pelanggan/3/abc.png

        if ($foto && Storage::disk('public')->exists($foto)) {
            Storage::disk('public')->delete($foto);
        }

        return back()->with('success', 'Foto berhasil dihapus');
    }
}
