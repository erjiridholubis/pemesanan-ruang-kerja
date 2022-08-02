<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Response;

use App\Customer;

class CustomerController extends Controller
{
    public function __construct() {
      $this->middleware('token');
    }

    public function index() {
      $data = Customer::all();
      $data = $data->isEmpty() ? "not found":$data;
      return Response::json(['data'=>$data],$data = $data ? 200:404);
    }

    public function show($id) {
      $data = Customer::where('id',$id)->get()->first();
      $data = $data ? $data: "not found";
      return Response::json(['data'=>$data],$data = $data ? 200:404);
    }
}
