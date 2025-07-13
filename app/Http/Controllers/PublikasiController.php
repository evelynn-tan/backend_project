<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publikasi;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
class PublikasiController extends Controller
{
    public function index(){
        // Mengambil semua data publikasi, diurutkan berdasarkan tanggal diupload
        return Publikasi::orderBy('created_at', 'desc')->get();
    }
    public function store(Request $request){
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'releaseDate' => 'required|date',
            'description' => 'nullable|string',
            'coverUrl' => 'nullable|url',
        ]);

        $publikasi = Publikasi::create($validated);
        return response()->json($publikasi, 201);
    }
     //menampilkan detail publikasi
    public function show($id){
        $publikasi = Publikasi::find($id);
        if(!$publikasi){
            return response()->json(['message' => 'Publikasi not found'], 404);
        }
        return response()->json($publikasi);
    }
    public function update(Request $request, $id)
    {
        // 1. Validasi data yang masuk dari request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'releaseDate' => 'required|date',
            'coverUrl' => 'required|url',
        ]);

        try {
            // 2. Cari publikasi berdasarkan ID, jika tidak ketemu akan error 404
            $publikasi = Publikasi::findOrFail($id);

            // 3. Update data publikasi dengan data yang sudah divalidasi
            $publikasi->update($validatedData);

            // 4. Kembalikan data yang sudah diupdate sebagai respon JSON
            return response()->json($publikasi, 200);

        } catch (\Exception $e) {
            // 5. Jika terjadi error lain, kembalikan respon error
            return response()->json([
                'message' => 'Gagal mengupdate publikasi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // mengedit data publikasi
    public function edit (Request $request, $id){
        //cari publikasi berdasarkan id
        $publikasi = Publikasi::find($id);
        if(!$publikasi){
            return response()->json(['message' => 'Publikasi not found'], 404);
        }
        //validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'releaseDate' => 'required|date',
            'description' => 'nullable|string',
            'coverUrl' => 'nullable|url',
        ]);
        $publikasi->update($validated);
        return response()->json($publikasi);
    }
    // menghapus data publikasi
    public function destroy($id){
        // 1. Cari publikasi berdasarkan ID, jika tidak ketemu akan error 404
        $publikasi = Publikasi::find($id);
        if(!$publikasi){
            return response()->json(['message' => 'Publikasi not found'], 404);
        }
        // $publikasi->delete();
        // 2. Ambil URL gambar dari data
        $imageUrl = $publikasi->coverUrl;
        // 3. Ekstrak 'Public ID' dari URL Cloudinary
        // Public ID-nya adalah 'folder/sample'
        $path = parse_url($imageUrl, PHP_URL_PATH);
        $publicId = pathinfo($path, PATHINFO_FILENAME);
        // Jika gambar ada di dalam folder, public ID akan menyertakan folder
        // jadi kita perlu mengambil path setelah /upload/ dan menghapus versi
        $parts = explode('/', $path);
        // Cari posisi 'upload' lalu ambil semua setelahnya
        $uploadIndex = array_search('upload', $parts);
        if ($uploadIndex !== false && isset($parts[$uploadIndex + 2])) {
            $publicIdWithFolder = implode('/', array_slice($parts, $uploadIndex + 2));
            $publicId = pathinfo($publicIdWithFolder, PATHINFO_FILENAME);
        }
        

        try {
            // 4. Hapus gambar dari Cloudinary menggunakan Public ID
            if ($publicId) {
                Cloudinary::destroy($publicId);
            }
            
            // 5. Hapus data dari database
            $publikasi->delete();

            return response()->json(['message' => 'Publikasi dan gambar berhasil dihapus']);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}