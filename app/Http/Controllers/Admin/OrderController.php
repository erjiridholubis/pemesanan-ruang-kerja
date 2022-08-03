<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;
use Str;
use Hash;
use Alert;

use App\Customer;
use App\Room;
use App\Order;

class OrderController extends Controller
{
  public function __construct() {
    $this->middleware('auth');
  }

  public function index() {
    $data = Order::with('customer')->with('room')->get();
    $customer = Customer::all();
    $room = Room::all();
    $title = "Semua Orderan";
    $description = "Data semua orderan";
    
    return view('admin.order.order',[
        'data' => $data,
        'title' => $title,
        'description' => $description,
        'customer' => $customer,
        'room' => $room,
    ]);
}

public function trash() {
    $data = Order::with('customer')->with('room')->onlyTrashed()->get();
    $customer = Customer::all();
    $room = Room::all();
    $title = "Orderan Terhapus";
    $description = "Data semua orderan yang terhapus";
    
    return view('admin.order.order',[
        'data' => $data,
        'title' => $title,
        'description' => $description,
        'customer' => $customer,
        'room' => $room,
    ]);
  }

  public function store(Request $req) {
      $this->validate($req,[
        'start_date' => 'required',
        'end_date' => 'required',
        'customer' => 'required',
        'room' => 'required',
    ]);
    
    $data = new Order();
    $data->customer_id = $req->customer;
    $data->room_id = $req->room;
    $data->start_date = $req->start_date;
    $data->end_date = $req->end_date;
    $data->booking_date = date('Y-m-d H:i:s');
    $data->save();
    
    alert()->success('Data berhasil di tambah');
    return redirect()->back();
    
}

public function update(Request $req, $id) {
    $this->validate($req,[
        'start_date' => 'required',
        'end_date' => 'required',
        'customer' => 'required',
        'room' => 'required',
    ]);
    
    $data = Order::find($id);
    $data->customer_id = $req->customer;
    $data->room_id = $req->room;
    $data->start_date = $req->start_date;
    $data->end_date = $req->end_date;
    $data->booking_date = date('Y-m-d H:i:s');
    $data->save();

    alert()->success('Data berhasil di update');
    return redirect()->back();

  }

  public function delete($id) {
    $data = Order::find($id);
    $data->delete();

    alert()->success('Data berhasil di hapus');
    return redirect()->back();
  }

  public function deletePermanent($id) {
    $data = Order::onlyTrashed()->where('id',$id);
    $data->forceDelete();

    alert()->success('Data berhasil di hapus permanen');
    return redirect()->back();
  }

  public function restore() {
    $data = Order::onlyTrashed();
    $data->restore();

    alert()->success('Semua data berhasil di restore');
    return redirect()->back();

  }

  public function restoreOrder($id) {
    $data = Order::where("id",$id)->onlyTrashed();
    $data->restore();

    alert()->success('Data berhasil di restore');
    return redirect()->back();

  }

}
