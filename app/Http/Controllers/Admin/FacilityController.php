<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;
use Str;
use Alert;

use App\Facility;

class FacilityController extends Controller
{
    public function __construct() {
    $this->middleware('auth');
  }

  public function index() {
    $data = Facility::all();
    $title = "Semua Fasilitas";
    $description = "Data semua fasilitas";

    return view('admin.facility.facility',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
    ]);
  }

  public function trash() {
    $data = Facility::onlyTrashed()->get();
    $title = "Fasilitas Terhapus";
    $description = "Data semua fasilitas yang terhapus";

    return view('admin.facility.facility',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
    ]);
  }

  public function store(Request $req) {
    $this->validate($req,[
      'name' => 'required|min:2|max:255',
    ]);

    $data = new Facility();
    $data->name = $req->name;
    $data->save();
    
    alert()->success('Data berhasil di tambah');
    return redirect()->back();

}

public function update(Request $req, $id) {
    $this->validate($req,[
        'name' => 'required|min:2|max:255',
    ]);

    $data = Facility::find($id);
    $data->name = $req->name;
    $data->save();

    alert()->success('Data berhasil di update');
    return redirect()->back();

  }

  public function delete($id) {
    $data = Facility::find($id);
    $data->delete();

    alert()->success('Data berhasil di hapus');
    return redirect()->back();
  }

  public function deletePermanent($id) {
    $data = Facility::onlyTrashed()->where('id',$id);
    $data->forceDelete();

    alert()->success('Data berhasil di hapus permanen');
    return redirect()->back();
  }

  public function restore() {
    $data = Facility::onlyTrashed();
    $data->restore();

    alert()->success('Semua data berhasil di restore');
    return redirect()->back();

  }

  public function restoreFacility($id) {
    $data = Facility::where("id",$id)->onlyTrashed();
    $data->restore();

    alert()->success('Data berhasil di restore');
    return redirect()->back();

  }

}
