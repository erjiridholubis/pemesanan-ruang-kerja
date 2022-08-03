<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;
use Str;
use Hash;
use Alert;

use App\User;
use App\Level;

class UserController extends Controller
{
  public function __construct() {
    $this->middleware('auth');
  }

  public function index() {
    $data = User::with('level')->get();
    $level = Level::all();
    $title = "Semua Pegawai";
    $description = "Data semua pegawai";

    return view('admin.user.user',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
      'level' => $level,
    ]);
  }

  public function trash() {
    $data = User::onlyTrashed()->get();
    $level = Level::all();
    $title = "Pegawai Terhapus";
    $description = "Data semua pegawai yang terhapus";

    return view('admin.user.user',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
      'level' => $level,
    ]);
  }

  public function store(Request $req) {
    $this->validate($req,[
      'name' => 'required|min:4|max:255',
      'email' => 'required|email|max:255|unique:users',
      'password' => 'required|string|min:8|confirmed',
      'level' => 'required',
    ]);

    $data = new User();
    $data->level_id = $req->level;
    $data->name = $req->name;
    $data->email = $req->email;
    $data->email_verified_at = date('Y-m-d H:i:s');
    $data->password = Hash::make($req->password);
    $data->status = 'active';
    $data->save();

    alert()->success('Data berhasil di tambah');
    return redirect()->back();

  }

  public function update(Request $req, $id) {
    $this->validate($req,[
      'name' => 'required|min:4|max:255',
      'email' => 'required|email|max:255',
      'level' => 'required',
    ]);

    if ($req->password != null) {
      $this->validate($req,[
        'password' => 'required|string|min:8|confirmed',
      ]);
    }

    $data = User::find($id);
    $data->level_id = $req->level;
    $data->name = $req->name;
    $data->email = $req->email;
    if ($req->password !== null) {
      $data->password = Hash::make($req->password);
    }
    $data->save();

    alert()->success('Data berhasil di update');
    return redirect()->back();

  }

  public function delete($id) {
    $data = User::find($id);
    $data->delete();

    alert()->success('Data berhasil di hapus');
    return redirect()->back();
  }

  public function deletePermanent($id) {
    $data = User::onlyTrashed()->where('id',$id);
    $data->forceDelete();

    alert()->success('Data berhasil di hapus permanen');
    return redirect()->back();
  }

  public function restore() {
    $data = User::onlyTrashed();
    $data->restore();

    alert()->success('Semua data berhasil di restore');
    return redirect()->back();

  }

  public function restoreUser($id) {
    $data = User::where("id",$id)->onlyTrashed();
    $data->restore();

    alert()->success('Data berhasil di restore');
    return redirect()->back();

  }

  public function active($id) {
    $param = request()->segment('4');
    if ($param == 'active' || $param == 'nonactive') {
      $data = User::find($id);
      $data->status = $param;
      $data->save();

      alert()->success($param == 'active' ? "User berhasil diaktifkan":"User berhasil dinonaktifkan");
    } else {
      alert()->error('Maaf, validasi tidak valid.');
    }

    return redirect()->back();
  }
}
