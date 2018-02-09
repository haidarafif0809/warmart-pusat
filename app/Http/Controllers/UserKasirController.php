<?php

namespace App\Http\Controllers;

use App\User;
use App\UserWarung;
use Auth;
use Illuminate\Http\Request;

class UserKasirController extends Controller
{
    public function __construct()
    {
        $this->middleware('user-must-topos');
    }

    public function dataPagination($data_kasir, $data_kasir_array)
    {

        $respons['current_page']   = $data_kasir->currentPage();
        $respons['data']           = $data_kasir_array;
        $respons['first_page_url'] = url('/user-kasir/view?page=' . $data_kasir->firstItem());
        $respons['from']           = 1;
        $respons['last_page']      = $data_kasir->lastPage();
        $respons['last_page_url']  = url('/user-kasir/view?page=' . $data_kasir->lastPage());
        $respons['next_page_url']  = $data_kasir->nextPageUrl();
        $respons['path']           = url('/user-kasir/view');
        $respons['per_page']       = $data_kasir->perPage();
        $respons['prev_page_url']  = $data_kasir->previousPageUrl();
        $respons['to']             = $data_kasir->perPage();
        $respons['total']          = $data_kasir->total();

        return $respons;
    }

    //VIEW USER KASIR
    public function view() //tipe user == 4 (User Kasir)

    {
        $data_kasir = UserWarung::where('tipe_user', 4)->orderBy('id', 'desc')->paginate(10);

        $data_kasir_array = array();
        foreach ($data_kasir as $data_kasirs) {

            array_push($data_kasir_array, ['data_kasir' => $data_kasirs]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_kasir, $data_kasir_array);

        return response()->json($respons);
    }

    //CARI USER KASIR
    public function pencarian(Request $request)
    {
        $search     = $request->search;
        $data_kasir = UserWarung::where('tipe_user', 4)->orderBy('id', 'desc')
            ->where(function ($query) use ($search) {
                $query->orwhere('name', 'LIKE', $search . '%')
                    ->orWhere('no_telp', 'LIKE', $search . '%');
            })->paginate(10);

        $data_kasir_array = array();
        foreach ($data_kasir as $data_kasirs) {

            array_push($data_kasir_array, ['data_kasir' => $data_kasirs]);
        }

        //DATA PAGINATION
        $respons = $this->dataPagination($data_kasir, $data_kasir_array);

        return response()->json($respons);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'    => 'required',
            'alamat'  => 'required',
            'no_telp' => 'required|without_spaces|max:15|unique:users,no_telp,',
        ]);

        $data_warung = User::where('id', Auth::user()->id)->first();

        //INSERT USER KASIR
        $user_kasir = UserWarung::create([
            'name'              => $request->name,
            'no_telp'           => $request->no_telp,
            'alamat'            => $request->alamat,
            'id_warung'         => $data_warung->id_warung,
            'tipe_user'         => 4, //USER KASIR
            'status_konfirmasi' => $data_warung->status_konfirmasi,
            'konfirmasi_admin'  => $data_warung->konfirmasi_admin,
            'foto_ktp'          => $data_warung->foto_ktp,
            'kasir_id'          => $data_warung->id,
            'password'          => bcrypt('123456'),
        ]);
    }
}
