<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Multipleuploads;
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
     *
     */
    public function store(Request $request)
    {
        // validasi basic (opsional, kamu bisa tambah rule sendiri)
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email',
            'foto.*'     => 'nullable|image|max:5120',
        ]);

        $pelanggan = Pelanggan::create($request->only([
            'first_name', 'last_name', 'birthday', 'gender', 'email', 'phone'
        ]));

        // upload foto pelanggan ke folder pelanggan/{id}
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $file->store("pelanggan/{$pelanggan->pelanggan_id}", "public");
            }
        }

        return redirect()->route('pelanggan.index')
            ->with('success', 'Penambahan Data Berhasil!');
    }

    /**
     * Display the specified resource (DETAIL).
     *
     */
    public function show($id)
    {
        $dataPelanggan = Pelanggan::findOrFail($id);

        // FOTO pelanggan (Cara B)
        $folderFoto = "pelanggan/" . $dataPelanggan->pelanggan_id;
        $fotos = Storage::disk('public')->exists($folderFoto)
            ? Storage::disk('public')->files($folderFoto)
            : [];

        // FILE pendukung dari multiuploads
        $files = Multipleuploads::where('ref_table', 'pelanggan')
            ->where('ref_id', $dataPelanggan->pelanggan_id)
            ->latest()
            ->get();

        return view('admin.pelanggan.detail', compact('dataPelanggan', 'fotos', 'files'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(string $id)
{
    $dataPelanggan = Pelanggan::findOrFail($id);

    // FOTO pelanggan (Cara B)
    $folderFoto = "pelanggan/" . $dataPelanggan->pelanggan_id;
    $fotos = Storage::disk('public')->exists($folderFoto)
        ? Storage::disk('public')->files($folderFoto)
        : [];

    // FILE pendukung
    $files = Multipleuploads::where('ref_table', 'pelanggan')
        ->where('ref_id', $dataPelanggan->pelanggan_id)
        ->latest()
        ->get();

    return view('admin.pelanggan.edit', compact('dataPelanggan', 'fotos', 'files'));
}

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email',
            'foto.*'     => 'nullable|image|max:5120',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);

        $pelanggan->first_name = $request->first_name;
        $pelanggan->last_name  = $request->last_name;
        $pelanggan->birthday   = $request->birthday;
        $pelanggan->gender     = $request->gender;
        $pelanggan->email      = $request->email;
        $pelanggan->phone      = $request->phone;

        // upload foto baru ke folder pelanggan/{id}
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
     *
     */
    public function destroy(string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // hapus foto folder
        $folderFoto = "pelanggan/" . $pelanggan->pelanggan_id;
        if (Storage::disk('public')->exists($folderFoto)) {
            Storage::disk('public')->deleteDirectory($folderFoto);
        }

        // hapus file pendukung (fisik + db)
        $pendukung = Multipleuploads::where('ref_table', 'pelanggan')
            ->where('ref_id', $pelanggan->pelanggan_id)
            ->get();

        foreach ($pendukung as $file) {
            if (Storage::disk('public')->exists($file->filename)) {
                Storage::disk('public')->delete($file->filename);
            }
            $file->delete();
        }

        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
            ->with('success','Data berhasil dihapus');
    }

    /**
     * Hapus satu FOTO pelanggan (Cara B) - dipakai di EDIT.
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

    /**
     * Upload FILE PENDUKUNG pelanggan (multiple) - dipakai di DETAIL.
     * Isi ref_table="pelanggan", ref_id=pelanggan_id via hidden input.
     */
    public function uploadFilePendukung(Request $request, $id)
    {
        $dataPelanggan = Pelanggan::findOrFail($id);

        $request->validate([
            'files.*' => 'file|max:5120'
        ]);

        $refTable = $request->ref_table; // "pelanggan"
        $refId    = $request->ref_id;    // pelanggan_id

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {

                $path = $file->store("pendukung/{$refTable}/{$refId}", 'public');

                Multipleuploads::create([
                    'filename'  => $path,
                    'ref_table' => $refTable,
                    'ref_id'    => $refId,
                ]);
            }
        }

        return back()->with('success', 'File pendukung berhasil diupload.');
    }

    /**
     * Hapus 1 FILE PENDUKUNG pelanggan - dipakai di DETAIL.
     */
    public function destroyFilePendukung($id, $fileId)
    {
        Pelanggan::findOrFail($id);

        $file = Multipleuploads::where('ref_table', 'pelanggan')
            ->where('ref_id', $id)
            ->where('id', $fileId)
            ->firstOrFail();

        if (Storage::disk('public')->exists($file->filename)) {
            Storage::disk('public')->delete($file->filename);
        }

        $file->delete();

        return back()->with('success', 'File berhasil dihapus.');
    }
}
