<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

//--yang perlu ditambahkan
use Illuminate\Support\Facades\Validator;
use App\Models\Transaksi;
//--

class TransaksiController extends Controller
{
    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'id_member'         => 'required|numeric',
            'tgl'               => 'required|date',
            'lama_pengerjaan'   => 'required|numeric',
            'id_user'           => 'required|numeric',

		]);

		if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ]);
		}

        //menghitung data batas waktu
        $tgl_transaksi = date_create($request->tgl);
        date_add($tgl_transaksi, date_interval_create_from_date_string($request->lama_pengerjaan." days"));
        $batas_waktu = date_format($tgl_transaksi, 'Y-m-d');


		$transaksi = new Transaksi();
		$transaksi->id_member = $request->id_member;
        $transaksi->tgl = $request->tgl;
        $transaksi->batas_waktu = $batas_waktu;
        $transaksi->id_user = $request->id_user;
		$transaksi->save();

        $data = Transaksi::where('id_transaksi','=', $transaksi->id_transaksi)->first();
        return response()->json([
            'success' => true,
            'message' => 'Data transaksi berhasil ditambahkan!.',
            'data' => $data
        ]);
    }

    public function update_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'id_transaksi'      => 'required|numeric',
            'status'            => 'required|string',
		]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ]);
		}

        $transaksi = Transaksi::where('id_transaksi', $request->id_transaksi)->first();
		$transaksi->status = $request->status;
		$transaksi->save();

        return response()->json([
            'success' => true,
            'message' => 'Data transaksi berhasil diubah menjadi '.$request->status,
        ]);

    }

    public function update_bayar(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'id_transaksi'      => 'required|numeric',
            'status'           => 'required|string',
		]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ]);
		}

        $transaksi = Transaksi::where('id_transaksi', $request->id_transaksi)->first();
		$transaksi->dibayar = $request->status;
		$transaksi->save();

        return response()->json([
            'success' => true,
            'message' => 'Data pembayaran berhasil diubah menjadi '.$request->status,
        ]);

    }
}
