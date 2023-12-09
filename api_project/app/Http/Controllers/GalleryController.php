<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $data = Gallery::paginate(10);
        return response()->json(['data' => $data, 'status' => 200], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'images' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        $gallery = new Gallery();

        if ($request->hasFile('images')) {

            $imageSize = $request->file('images')->getSize(); // Ukuran dalam byte

            if ($imageSize > 2048 * 1024) {
                // Return response JSON dengan pesan kesalahan jika gambar melebihi 2 MB
                return response()->json(['message' => 'Ukuran gambar melebihi batas maksimum 2 MB.', 'status' => 400], 400);
            }

            // Hapus gambar lama jika ada
            if ($gallery->images) {
                Storage::delete('photos/' . $gallery->images);
            }

            // Simpan gambar yang baru diunggah
            $filename = $this->generateRandomString();
            $extension = $request->file('images')->extension();
            Storage::putFileAs('photos', $request->file('images'), $filename . '.' . $extension);

            $gallery->images = $filename . '.' . $extension;
        }
        $gallery->save();
        return response()->json(['message' => 'Gambar berhasil ditambahkan', 'status' => 201], 201);
    }

    public function getImage($image_name){
        $path = storage_path('app/photos/' . $image_name);

        if (!file_exists($path)) {
            return response()->json(['message' => 'Image not found.'], 404);
        }

        $file = file_get_contents($path);
        $type = mime_content_type($path);

        return response($file, 200)->header('Content-Type', $type);
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

