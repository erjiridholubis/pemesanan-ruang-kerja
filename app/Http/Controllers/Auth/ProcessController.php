<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Hash;
use Session;

use App\User;

class ProcessController extends Controller
{
    public function index() {
      $update = User::find(Auth::id());
      $update->api_token = Hash::make(md5(Auth::user()->email));
      $update->save();

      Session::put('api_token',$update->api_token);
      alert()->success('Login Sukses');
      return redirect()->route('admin.home');
    }
}
