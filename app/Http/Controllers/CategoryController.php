<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator; // Import Validator

class CategoryController extends Controller
{
    //CRUD category hahahahahaah

    //C = Create Category Data
    //R = Read Data
    //U = Update Data
    //D = Delete Data

    public function getCategories(){
        //kita ambil data dari model Category
        //lalu kita setor di dalam variabel $categories
        $categories = Category::get();

        //kemudian kita tunjukkan data tersebut dalam bentuk JSON
        return response()->json($categories);
    }

    public function createCategory(Request $request){
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:categories', // nama kategori wajib diisi, berupa string, maksimal 255 karakter, dan unik
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400); // kembalikan error validasi dengan status 400
        }

        // Buat kategori baru
        $category = Category::create([
            'name' => $request->name, // ambil nama dari request
        ]);

        // Kembalikan respon sukses dengan data kategori yang baru dibuat
        return response()->json([
            'message' => 'Kategori berhasil dibuat', // pesan sukses
            'data' => $category // data kategori
        ], 201); // status 201 artinya "Created"
    }

    public function updateCategory(Request $request, $id){
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191|unique:categories,name,' . $id, // nama kategori wajib diisi, unik kecuali untuk dirinya sendiri
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400); // kembalikan error validasi
        }

        // Cari kategori berdasarkan ID
        $category = Category::find($id);

        // Jika kategori tidak ditemukan
        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404); // kembalikan pesan error dengan status 404
        }

        // Update nama kategori
        $category->name = $request->name;
        $category->save(); // simpan perubahan

        // Kembalikan respon sukses dengan data kategori yang telah diupdate
        return response()->json([
            'message' => 'Kategori berhasil diperbarui', // pesan sukses
            'data' => $category // data kategori yang diperbarui
        ]);
    }

    public function deleteCategory($id){
        // Cari kategori berdasarkan ID
        $category = Category::find($id);

        // Jika kategori tidak ditemukan
        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404); // kembalikan pesan error
        }

        // Hapus kategori
        $category->delete();

        // Kembalikan respon sukses
        return response()->json(['message' => 'Kategori berhasil dihapus']); // pesan sukses
    }
}
