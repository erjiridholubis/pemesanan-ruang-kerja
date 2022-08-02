<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;
use Str;
use Alert;

use App\Type;

class TypeController extends Controller
{
    public function __construct() {
    $this->middleware('auth');
  }

  public function index() {
    $data = Type::all();
    $title = "Semua Tipe Ruangan";
    $description = "Data semua tipe ruangan";

    return view('admin.type.type',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
    ]);
  }

  public function trash() {
    $data = Type::onlyTrashed()->get();
    $title = "Tipe Ruangan Terhapus";
    $description = "Data semua tipe ruangan yang terhapus";

    return view('admin.type.type',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
    ]);
  }

  public function store(Request $req) {
    $this->validate($req,[
      'name' => 'required|min:4|max:255',
      'price' => 'required|min:20|numeric',
    ]);
    
    $data = new Type();
    $data->name = $req->name;
    $data->price = $req->price;
    $data->save();
    
    alert()->success('Data berhasil di tambah');
    return redirect()->back();
    
}

public function update(Request $req, $id) {
    $this->validate($req,[
        'name' => 'required|min:4|max:255',
        'price' => 'required|min:20|numeric',
    ]);

    $data = Type::find($id);
    $data->name = $req->name;
    $data->price = $req->price;
    $data->save();

    alert()->success('Data berhasil di update');
    return redirect()->back();

  }

  public function delete($id) {
    $data = Type::find($id);
    $data->delete();

    alert()->success('Data berhasil di hapus');
    return redirect()->back();
  }

  public function deletePermanent($id) {
    $data = Type::onlyTrashed()->where('id',$id);
    $data->forceDelete();

    alert()->success('Data berhasil di hapus permanen');
    return redirect()->back();
  }

  public function restore() {
    $data = Type::onlyTrashed();
    $data->restore();

    alert()->success('Semua data berhasil di restore');
    return redirect()->back();

  }

  public function restoreType($id) {
    $data = Type::where("id",$id)->onlyTrashed();
    $data->restore();

    alert()->success('Data berhasil di restore');
    return redirect()->back();

  }

}
