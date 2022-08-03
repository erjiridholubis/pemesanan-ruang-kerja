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
