<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pengajuan;
use App\Models\Pengajuan_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function index()
    {
        $belum_approve = Pengajuan::with('user_pengajuan')->where('level_1', '!=', null)->where('level_2', null)->paginate(10);
        $approve = Pengajuan::with('user_pengajuan')->where('level_1', '!=', null)->where('level_2', '!=', null)->paginate(10);
        return view('super_admin.index', compact('approve', 'belum_approve'));
    }

    public function barang()
    {
        $barang = Barang::all();
        return view('super_admin.barang', compact('barang'));
    }

    public function detail($id)
    {
        $pengajuan_detail = Pengajuan_detail::with('barang')->where('pengajuan_id', $id)->get();
        $pengajuan = Pengajuan::where('id', $id)->first();
        $barang = Barang::all();
        return view('super_admin.detail', compact('pengajuan_detail', 'pengajuan', 'barang'));
    }

    public function store(Request $request)
    {
        $pengajuan = Pengajuan::where('id', $request->id)->first();
        $pengajuan->level_2 = Auth::user()->id;
        $pengajuan->update();
        return back()->with('success', 'Berhasil Approve');
    }
}
