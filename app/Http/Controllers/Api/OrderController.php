<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Response;

use App\Order;
use App\Customer;
use App\Room;

class OrderController extends Controller
{
    public function __construct() {
      $this->middleware('token');
    }

    public function index() {
      $data = Order::with('customer')->with('room')->get();
      $data = $data->isEmpty() ? "not found":$data;
      return Response::json(['data'=>$data],$data = $data ? 200:404);
    }

    public function show($id) {
      $data = Order::with('customer')->with('room')->where('id',$id)->get()->first();
      $data = $data ? $data: "not found";
      return Response::json(['data'=>$data],$data = $data ? 200:404);
    }
}
