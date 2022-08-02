<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;
use Str;
use Hash;
use Alert;

use App\Customer;

class CustomerController extends Controller
{
    public function __construct() {
    $this->middleware('auth');
  }

  public function index() {
    $data = Customer::all();
    $title = "Semua Customer";
    $description = "Data semua customer";

    return view('admin.customer.customer',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
    ]);
  }

  public function trash() {
    $data = Customer::onlyTrashed()->get();
    $title = "Customer Terhapus";
    $description = "Data semua customer yang terhapus";

    return view('admin.customer.customer',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
    ]);
  }

  public function store(Request $req) {
    $this->validate($req,[
      'name' => 'required|min:4|max:255',
      'phone' => 'required|min:11|numeric',
      'email' => 'required|email|max:255|unique:customers',
      'password' => 'required|string|min:8|confirmed',
    ]);

    $data = new Customer();
    $data->name = $req->name;
    $data->phone = $req->phone;
    $data->email = $req->email;
    $data->email_verified_at = date('Y-m-d H:i:s');
    $data->password = Hash::make($req->password);
    $data->save();
    
    alert()->success('Data berhasil di tambah');
    return redirect()->back();

}

public function update(Request $req, $id) {
    $this->validate($req,[
        'name' => 'required|min:4|max:255',
        'phone' => 'required|min:11|max:13|numeric',
        'email' => 'required|email|max:255',
    ]);

    if ($req->password != null) {
      $this->validate($req,[
        'password' => 'required|string|min:8|confirmed',
      ]);
    }

    $data = Customer::find($id);
    $data->name = $req->name;
    $data->phone = $req->phone;
    $data->email = $req->email;
    if ($req->password !== null) {
      $data->password = Hash::make($req->password);
    }
    $data->save();

    alert()->success('Data berhasil di update');
    return redirect()->back();

  }

  public function delete($id) {
    $data = Customer::find($id);
    $data->delete();

    alert()->success('Data berhasil di hapus');
    return redirect()->back();
  }

  public function deletePermanent($id) {
    $data = Customer::onlyTrashed()->where('id',$id);
    $data->forceDelete();

    alert()->success('Data berhasil di hapus permanen');
    return redirect()->back();
  }

  public function restore() {
    $data = Customer::onlyTrashed();
    $data->restore();

    alert()->success('Semua data berhasil di restore');
    return redirect()->back();

  }

  public function restoreCustomer($id) {
    $data = Customer::where("id",$id)->onlyTrashed();
    $data->restore();

    alert()->success('Data berhasil di restore');
    return redirect()->back();

  }

}
