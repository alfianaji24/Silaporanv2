<?php

namespace App\Http\Controllers;

use App\Models\Pengaturanumum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class GeneralsettingController extends Controller
{
    public function index()
    {
        $data['setting'] = Pengaturanumum::where('id', 1)->first();
        return view('generalsettings.index', $data);
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $setting = Pengaturanumum::find($id);
        if ($setting) {
            $setting->update([
                'nama_perusahaan' => $request->input('nama_perusahaan'),
                'alamat' => $request->input('alamat'),
                'telepon' => $request->input('telepon'),
                'total_jam_bulan' => $request->input('total_jam_bulan'),
                'denda' => $request->input('denda') == 'on',
                'face_recognition' => $request->input('face_recognition') == 'on',
                'periode_laporan_dari' => $request->input('periode_laporan_dari'),
                'periode_laporan_sampai' => $request->input('periode_laporan_sampai'),
                'periode_laporan_next_bulan' => $request->input('periode_laporan_next_bulan') == 'on',
                'cloud_id' => $request->input('cloud_id'),
                'api_key' => $request->input('api_key')
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil di Update'));
        } else {
            return Redirect::back()->with(messageError('Data Gagal di Update'));
        }
    }
}
