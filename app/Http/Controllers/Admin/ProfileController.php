<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Session;
use Str;
use File;
use Alert;

use App\WebProfile;

class ProfileController extends Controller
{
  public function __construct() {
    $this->middleware('auth');
  }

  public function index() {
    $data = WebProfile::get()->first();
    $title = "Profile Web";
    $description = "Data Profile Web";

    return view('admin.profile.profile',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
    ]);
  }

  public function update(Request $req) {
    $this->validate($req, [
        'icon'        => 'file|image|mimes:jpeg,png,jpg',
        'thumbnail'   => 'file|image|mimes:jpeg,png,jpg',
        'title'       => 'required|max:255',
        'slogan'      => 'required|max:255',
        'description' => 'required|max:500',
        'version'     => 'required|max:4',
        'phone'     => 'required|max:15',
        'email'     => 'required|email|max:255',
        'instagram'     => 'required|max:100',
        'line'     => 'required|max:100',
      ]);

      $data = WebProfile::get()->first();

      $icon = $req->file('icon');
      $thumbnail = $req->file('thumbnail');
      $title = ucwords($req->title);
      if ($icon == null) {
        $newname_icon =  $data->logo;
      } else {
        $newname_icon =  'logo-'.date('dhmis').'.'.$icon->getClientOriginalExtension();
      }

      if ($thumbnail == null) {
        $newname_thumbnail =  $data->thumbnail;
      } else {
        $newname_thumbnail =  'dashboard-'.date('dhmis').'.'.$thumbnail->getClientOriginalExtension();
      }

      $data->title = $req->title;
      $data->slogan = $req->slogan;
      $data->description = $req->description;
      $data->version = $req->version;
      $data->phone = $req->phone;
      $data->email = $req->email;
      $data->ig = $req->instagram;
      $data->line = $req->line;

      if ($icon != null) {
        $icon->move(public_path(),$newname_icon);
        File::delete('./'.str_replace(url(''),'',$data->logo));
      }
      if ($thumbnail != null) {
        $thumbnail->move(public_path(),$newname_thumbnail);
        File::delete('./'.str_replace(url(''),'',$data->thumbnail));
      }

      $data->logo = $newname_icon;
      $data->thumbnail = $newname_thumbnail;
      $data->save();

      alert()->success('Profile web berhasil diupdate');
      return redirect()->route('admin.profile');
  }

}
