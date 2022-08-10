<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;
use Auth;
use Str;
use Hash;
use Alert;

use App\User;
use App\Order;
use App\Payment;

class PaymentController extends Controller
{
  public function __construct() {
    $this->middleware('auth');
  }

  public function index() {
    $data = Payment::with('order')->with('user')->get();
    $order = Order::all();
    $user = User::all();
    $title = "Semua Pembayaran";
    $description = "Data semua pembayaran";

    return view('admin.payment.payment',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
      'user' => $user,
      'order' => $order,
      'data' => $data,
    ]);
  }

  public function paid() {
    $data = Payment::with('order')->with('user')->where("status","paid")->get();
    $order = Order::all();
    $user = User::all();
    $title = "Semua Pembayaran Lunas";
    $description = "Data semua pembayaran yang telah di approve oleh admin";

    return view('admin.payment.payment',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
      'user' => $user,
      'order' => $order,
      'data' => $data,
    ]);
  }

  public function pending() {
    $data = Payment::with('order')->with('user')->where("status","pending")->get();
    $order = Order::all();
    $user = User::all();
    $title = "Semua Pembayaran Tertunda";
    $description = "Data semua pembayaran yang belum di approve oleh admin";

    return view('admin.payment.payment',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
      'user' => $user,
      'order' => $order,
      'data' => $data,
    ]);
  }

  public function cancel() {
    $data = Payment::with('order')->with('user')->where("status","cancelled")->get();
    $order = Order::all();
    $user = User::all();
    $title = "Semua Pembayaran Ditolak";
    $description = "Data semua pembayaran yang di ditolak oleh admin";

    return view('admin.payment.payment',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
      'user' => $user,
      'order' => $order,
      'data' => $data,
    ]);
  }

  public function trash() {
    $data = Payment::with('order')->with('user')->onlyTrashed()->get();
    $order = Order::all();
    $user = User::all();
    $title = "Pembayaran Terhapus";
    $description = "Data semua pembayaran yang terhapus";

    return view('admin.payment.payment',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
      'user' => $user,
      'order' => $order,
    ]);
  }

  public function store(Request $req) {
    $this->validate($req,[
      'order' => 'required',
      'payment_date' => 'required',
      'proof' => 'required|file|image|mimes:jpeg,png,jpg',
      'status' => 'required',
    ]);

    $image = $req->file('proof');
    $img_name = "";
    $fileInfo = $image->getClientOriginalExtension();
    $newname = 'proof-'.uniqid().'.'.$fileInfo;
    $dir = 'uploads/payments/';

    $data = new Payment();
    $data->order_id = $req->order;
    $data->user_id = Auth::id();;
    $data->payment_date = $req->payment_date;
    $data->proof = $newname;
    $data->status = $req->status;
    $data->payment_deadline = date('Y-m-d H:i:s');
    $data->save();

    $image->move($dir,$newname);

    alert()->success('Data berhasil di tambah');
    return redirect()->back();

  }

  public function update(Request $req, $id) {
    $this->validate($req,[
      'order' => 'required',
      'payment_date' => 'required',
      'proof' => 'file|image|mimes:jpeg,png,jpg',
      'status' => 'required',
    ]);

    $data = Payment::find($id);

    $img_name = "proof";
    $image = $req->file('proof');
    if ($image != null) {
      $fileInfo = $image->getClientOriginalExtension();
      $newname = $img_name.'-'.uniqid().'.'.$fileInfo;
    } else {
      $newname = $data->proof;
    }
    $dir = 'uploads/payments/';

    $data->user_id = Auth::id();;
    $data->payment_date = $req->payment_date;
    if ($image != null) {
      $image->move($dir,$newname);
      File::delete($dir.$data->image);
    }
    $data->proof = $newname;
    $data->status = $req->status;
    $data->save();

    alert()->success('Data berhasil di update');
    return redirect()->back();

  }

  public function delete($id) {
    $data = Payment::find($id);
    $data->delete();

    alert()->success('Data berhasil di hapus');
    return redirect()->back();
  }

  public function deletePermanent($id) {
    $data = Payment::onlyTrashed()->where('id',$id);
    $data->forceDelete();

    alert()->success('Data berhasil di hapus permanen');
    return redirect()->back();
  }

  public function restore() {
    $data = Payment::onlyTrashed();
    $data->restore();

    alert()->success('Semua data berhasil di restore');
    return redirect()->back();

  }

  public function restoreUser($id) {
    $data = Payment::where("id",$id)->onlyTrashed();
    $data->restore();

    alert()->success('Data berhasil di restore');
    return redirect()->back();

  }

  public function approve($id) {
    $param = request()->segment('4');
    if ($param == 'approve' || $param == 'deceline') {
       $status = 'pending';
       
       if($param == 'approve') {
           $status = 'paid';
        } elseif ($param == 'deceline') {
            $status = 'cancelled';
        } else {
            $status = 'pending';
        }

      $data = Payment::find($id);
      $data->user_id = Auth::id();
      $data->status = $status;
      $data->save();

      alert()->success($param == 'approve' ? "Pembayaran berhasil di approve":"pembayran berhasil di batalkan");
    } else {
      alert()->error('Maaf, validasi tidak valid.');
    }

    return redirect()->back();
  }
}
