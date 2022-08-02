<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Response;

use App\User;
use App\Level;

class UserController extends Controller
{
    public function __construct() {
      $this->middleware('token');
    }

    public function index() {
      $data = User::with('level')->get();
      $data = $data->isEmpty() ? "not found":$data;
      return Response::json(['data'=>$data],$data = $data ? 200:404);
    }

    public function show($id) {
      $data = User::with('level')->where('id',$id)->get()->first();
      $data = $data ? $data: "not found";
      return Response::json(['data'=>$data],$data = $data ? 200:404);
    }
}
