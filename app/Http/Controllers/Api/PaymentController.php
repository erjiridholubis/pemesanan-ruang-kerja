<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Response;

use App\Payment;
use App\Order;
use App\User;

class PaymentController extends Controller
{
    public function __construct() {
      $this->middleware('token');
    }

    public function index() {
      $data = Payment::with('user')->with('order')->get();
      $data = $data->isEmpty() ? "not found":$data;
      return Response::json(['data'=>$data],$data = $data ? 200:404);
    }

    public function show($id) {
      $data = Payment::with('user')->with('order')->where('id',$id)->get()->first();
      $data = $data ? $data: "not found";
      return Response::json(['data'=>$data],$data = $data ? 200:404);
    }
}
