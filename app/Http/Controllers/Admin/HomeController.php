<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Post;
use App\Page;
use App\Staff;
use App\Organization;

class HomeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index() {
    $title = "Home";
    $description = "Dashboard Admin";

    $cPost = 0;
    $cPage = 0;
    $cStaff = 0;
    $cOrganization = 0;

    return view('admin.home', [
      'title' => $title,
      'description' => $description,
      'cPost' => $cPost,
      'cPage' => $cPage,
      'cStaff' => $cStaff,
      'cOrganization' => $cOrganization,
    ]);
  }
}
