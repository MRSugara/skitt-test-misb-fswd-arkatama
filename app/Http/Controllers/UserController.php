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

        // Hapus kata "TAHUN," "THN," dan "TH" dari input data
        $inputDataWithoutTahun = trim(preg_replace('/\b(?:TAHUN|THN|TH)\b/i', '', $inputData));

        // Ekstrak usia terlebih dahulu menggunakan regex
        $usia = $this->extractUsia($inputDataWithoutTahun);

        // Pisahkan input menjadi nama dan kota menggunakan regex
        $pattern = '/^(.*?)\s*(\d+)\s*(.*?)$/i';
        if (preg_match($pattern, $inputDataWithoutTahun, $matches)) {
        $nama = $matches[1];
        $usia = $matches[2];
        $kota = $matches[3];
        } else {
        $nama = $inputDataWithoutTahun;
        $usia = null;
        $kota = null;
        }

        // Simpan data ke dalam database
        $user = new User();
        $user->name = strtoupper(trim($nama));
        $user->age = $usia;
        $user->city = strtoupper(trim($kota));
        $user->save();

        return "Data berhasil disimpan ke dalam database.";
        }

        private function extractUsia($data)
        {
        $pattern = '/\b(\d+)\b/';
        preg_match($pattern, $data, $matches);

        return isset($matches[1]) ? (int)$matches[1] : null;
        }
}
