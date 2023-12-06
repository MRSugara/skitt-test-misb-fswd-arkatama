<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
     public function index()
     {
     return view('welcome');
     }

     public function store(Request $request)
     {
    // Ambil data dari input pengguna
    $inputData = strtoupper(trim($request->input('data')));

    // Ekstrak usia terlebih dahulu menggunakan regex
    $usia = $this->extractUsia($inputData);

    // Hapus usia dari input data
    $inputDataWithoutUsia = trim(preg_replace('/\b(\d+)\s*(?:TAHUN|THN|TH)\b/i', '', $inputData));

    // Pecah data menjadi array
    $data = explode(' ', $inputDataWithoutUsia);

    // Ambil Nama
    $nama = implode(' ', array_slice($data, 0, -2));

    // Ambil Kota
    $kota = implode(' ', array_slice($data, 2, 3));

    // Simpan data ke dalam database
    $user = new User();
    $user->name = strtoupper($nama);
    $user->age = $usia;
    $user->city = strtoupper($kota);
    $user->save();

    return "Data berhasil disimpan ke dalam database.";
     }

     // Fungsi untuk mengambil usia menggunakan regex
     private function extractUsia($data)
     {
     $pattern = '/\b(\d+)\s*(?:TAHUN|THN|TH)\b/i';
     preg_match($pattern, $data, $matches);

     return isset($matches[1]) ? (int)$matches[1] : null;
     }
}
