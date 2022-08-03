<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;


use App\Comments;
use App\Subcomments;
use App\Views;
use App\Viewpages;
use App\Categories;
use App\Categoriesposts;
use App\Folders;
use App\Images;
use App\Levels;
use App\Pages;
use App\Posts;
use App\Statuses;
use App\Titlewebs;
use App\Users;
use App\Keywords;

use Jenssegers\Agent\Agent;
use PulkitJalan\GeoIP\GeoIP;
use Session;
use Str;
use File;

class AdminController extends Controller
{
  private $webProf;
  public $titleWeb;
  public $sloganWeb;
  public $descWeb;
  public $iconWeb;
  public $url;
  public $img;
  public $versionWeb;
  public $created_at;
  public $updated_at;
  public $userProfile;
  public $color;

  public function __construct(Request $req) {
    $this->webProf = Titlewebs::where("id","=",1)->get();
    foreach ($this->webProf as $key => $p) {
      $this->titleWeb = $p->title_web;
      $this->sloganWeb = $p->slogan_web;
      $this->descWeb = $p->description_web;
      $this->iconWeb = $p->icon_web;
      $this->url = config('app.url');
      $this->img = config('app.url').'/dashboard.jpg';
      $this->versionWeb = $p->version_web;
      $this->created_at = $p->created_at;
      $this->updated_at = $p->updated_at;
    }

    $this->color = ['Indonesia'=>"#2e59d9",'United States'=>"#17a673",'Canada'=>"#d45079",'India Indonesia'=>"#216353",'Russia'=>"#f1935c",'Malaysia'=>"#2c9faf",'Other'=>"#eaebd8"];
    $this->userProfile = Users::where('id',Session::get('id_user'))->get();
  }

  public function index(Request $req){
    if (empty(Session::get('login'))) {
      return redirect('/prev/login');
    }else {
    return redirect('/prev/dashboard');
    }
  }

  public function login(Request $req){
    if (empty(Session::get('login'))) {
      return view('prev/login',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
      ]);
    }else {
      Session::flash('infoLogin','<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Selamat datang kembali <span class="text-capitalize">'.Session::get("name_user").'.</span></div>');
      return redirect('/prev/dashboard');
    }
  }

  public function proseslogin(Request $req){
    $this->validate($req,[
      'email'=>'required',
      'password'=>'required'
    ]);
    $str = ['$','!','%','^','0','1','&','=','.','A','y','x','X','c','s',','];
    $n1 = $str[8].$str[2].$str[4].$str[6].$str[0].$str[10].$str[12].$str[14];
    $n2 = $str[1].$str[3].$str[5].$str[7].$str[9].$str[2].$str[0].$str[1].$str[11].$str[15].$str[13].$str[7].$str[9];
    $pass = $n1.md5(sha1($req->password)).$n2;
    $login = DB::table('users')
            ->join('levels','levels.id','=','users.levels_id')
            ->where('email_user',$req->email)
            ->where('password',"c1".md5($pass))
            ->select('users.*','levels.*','users.id as user_id')
            ->get();
    $cc = count($login);
    $br = "<br>";
    $avatar = "";
    $name_user = "";
    $email_user = "";
    $username = "";
    $level = "";
    $Slogin = "";
    $infoLogin = "";

    if ($cc == 1) {
    foreach ($login as $key => $v) {
        Session::put('id_user',$v->user_id);
        Session::put('avatar',$v->avatar);
        Session::put('name_user',$v->name_user);
        Session::put('email_user',$v->email_user);
        Session::put('username',$v->username);
        Session::put('level',$v->level);
        Session::put('login',TRUE);

        $avatar = Session::get('avatar');
        $name_user = Session::get('name_user');
        $email_user = Session::get('email_user');
        $username = Session::get('username');
        $level = Session::get('level');
        $Slogin = Session::get('login');

      }
      $infoLogin = Session::flash('infoLogin','<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Login Sukses! Selamat datang kembali <span class="text-capitalize">'.$name_user.'</span></div>');
      $sweet = Session::flash('popLogin',"<script>Swal.fire({title: 'Login Berhasil!',text: '',icon: 'success',showConfirmButton: false,timer: 1500})</script>");

      return redirect('/prev/dashboard');
      } else {
        $infoLogin = Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Login Gagal!</div>');
        return redirect('/prev/login');
    }
  }

  public function logout(Request $req) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      Session::forget('avatar');
      Session::forget('email_user');
      Session::forget('name_user');
      Session::forget('username');
      Session::forget('level');
      Session::forget('login');
      Session::flash('infoLogin','<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Logout berhasil.</div>');
      return redirect('/prev/login');
    }
  }

  public function dashboard(Request $req){
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');

    }else {
    $bulan = date('Y-m-d');
    // $bulan = '2020-02';
    $this->userProfile = Users::where('id',Session::get('id_user'))->get();
    $Tadmins = count(Users::where('levels_id','2')->get());
    $Tusers = count(Users::where('levels_id','3')->get());
    $Tarticles = count(Posts::all());
    $Tpages = count(Pages::all());
    $ViewDay = Views::select(DB::raw('*,count(*) as countVisitor'))->where('created_at','LIKE',"%$bulan%")->groupBy('posts_id')->orderBy('created_at','asc')->get();
    $c = Views::select(DB::raw('count(*) as countVisitor'))->where('created_at','LIKE',"%$bulan%")->get();
    $country = Views::select(DB::raw('id,location,count(*) as countries'))->where('created_at','LIKE',"%$bulan%")->groupBy('location')->orderBy('location','asc')->get();
    return view('prev/index',[
                "titleWeb"        => $this->titleWeb,
                "sloganWeb"       => $this->sloganWeb,
                "descWeb"         => $this->descWeb,
                "iconWeb"         => $this->iconWeb,
                "url"             => $this->url,
                "img"             => $this->img,
                "versionWeb"      => $this->versionWeb,
                "created_at"      => $this->created_at,
                "updated_at"      => $this->updated_at,
                'myProfile'       => $this->userProfile,
                'Tadmins'         => $Tadmins,
                'Tusers'          => $Tusers,
                'Tarticles'       => $Tarticles,
                'Tpages'          => $Tpages,
                'viewDay'         => $ViewDay,
                'totalVisitor'    => $c,
                'country'         => $country,
                'color'           => $this->color,
              ]);
    }
  }

  // CRUD

  // ------------ Articles -------------
  public function articles(){
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');

    } else {
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      $articles = Posts::orderBy('id','desc')->get();
      return view('prev/articles/articles',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'articles'        => $articles,
      ]);
    }
  }

  public function articlesPublish(){
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');

    } else {
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      $articles = Posts::where('statuses_id',1)->orderBy('id','desc')->get();
      return view('prev/articles/articles',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'articles'        => $articles,
      ]);
    }
  }

  public function articlesPending(){
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');

    } else {
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      $articles = Posts::where('statuses_id',2)->orderBy('id','desc')->get();
      return view('prev/articles/articles',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'articles'        => $articles,
      ]);
    }
  }

  public function articlesDelete() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');

    } else {
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      $articles = Posts::onlyTrashed()->orderBy('id','desc')->get();
      return view('prev/articles/articles',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'articles'        => $articles,
      ]);
    }
  }

  public function deletePost($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {

      $article = Posts::find($id);
      $article->delete();
      $views = Views::where('posts_id',$id);
      $views->delete();
      Session::flash('infoCRUD','Data berhasil di hapus.');
      // return redirect('/prev/articles');
      return redirect()->back();
    }
  }

  public function deletePostPermanent($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $post_tags = DB::table('categories_posts')->where('posts_id',$id);
      $post_tags->delete();

      $article = Posts::onlyTrashed()->where('id',$id);
      $d = $article->get();
      File::delete('upload/thumbnail/'.$d[0]->image_post);
      $article->forceDelete();

      $views = Views::onlyTrashed()->where('posts_id',$id);
      $views->forceDelete();
      Session::flash('infoCRUD','Data berhasil di hapus permanen .');
      return redirect()->back();
    }
  }

  public function restorePost($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $article = Posts::onlyTrashed()->where('id',$id);
      $article->restore();
      Session::flash('infoCRUD','Data berhasil di kembalikan.');
      // return redirect('/prev/articles');
      return redirect()->back();
    }
  }

  public function articlesRestoreAll() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $article = Posts::onlyTrashed();
      $article->restore();
      $views = Views::onlyTrashed();
      $views->restore();
      Session::flash('infoCRUD','Semua data berhasil di kembalikan.');
      // return redirect('/prev/articles');
      return redirect()->back();
    }
  }

  public function publishPost($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $article = Posts::find($id);
      $article->statuses_id = 1;
      $article->save();
      Session::flash('infoCRUD','Data berhasil di publish.');
      // return redirect('/prev/articles');
      return redirect()->back();
    }
  }

  public function pendingPost($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $article = Posts::find($id);
      $article->statuses_id = 2;
      $article->save();
      Session::flash('infoCRUD','Data berhasil di pending.');
      // return redirect('/prev/articles');
      return redirect()->back();
    }
  }

  public function createPost() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $categories = Categories::all();
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      return view('prev/articles/create',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'categories'      => $categories,
      ]);
    }
  }

  public function storePost(Request $req) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $this->validate($req,[
        'judulpost'=>'required|min:8|max:200',
        'gambar'=>'required|file|image|mimes:jpeg,png,jpg',
        'konten'=>'required|min:50',
        'tags'=>'required',
        'link_post'=>'required|min:8|max:200',
        'deskripsi'=>'required|min:20'
      ]);
      $user_id = Session::get('id_user');
      $tag_id = $req->tags;
      $slug = Str::slug(strtolower($req->link_post));
      $gambar = $req->file('gambar');
      if ($gambar != null) {
        $fileInfo = $gambar->getClientOriginalExtension();
        $newname = $slug.'.'.$fileInfo;

        $dir = 'upload/thumbnail';
        $gambar->move($dir,$newname);

      } else {
        $newname = 'default.jpg';
      }

      $new_post = new Posts();
      $new_post->users_id = $user_id;
      if ($req->has('publish')) {
        $new_post->statuses_id = 1;
      } elseif ($req->has('pending')) {
        $new_post->statuses_id = 2;
      }
      $new_post->link_post = $slug;
      $new_post->title_post = $req->judulpost;
      $new_post->description_post = $req->deskripsi;
      $new_post->content_post = $req->konten;

      $new_post->image_post = $newname;
      $new_post->save();

      $new_post->Categories()->sync($tag_id);
      Session::flash('infoCRUD','Data berhasil di upload.');
      return redirect('/prev/articles');
    }
  }

  public function editPost($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $categories = Categories::all();
      $data = Posts::find($id);
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      return view('prev/articles/update',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'categories'      => $categories,
        'data'      => $data,
      ]);
    }
  }

  public function updatePost(Request $req, $id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $this->validate($req,[
        'judulpost'=>'required|min:8|max:200',
        'konten'=>'required|min:50',
        'gambar'=>'file|image|mimes:jpeg,png,jpg',
        'tags'=>'required',
        'link_post'=>'required|min:8|max:200',
        'deskripsi'=>'required|min:20'
      ]);

      $user_id = Session::get('id_user');
      $tag_id = $req->tags;
      $slug = Str::slug(strtolower($req->link_post));

      $new_post = Posts::find($id);
      $new_post->users_id = $user_id;
      if ($req->has('publish')) {
        $new_post->statuses_id = 1;
      } elseif ($req->has('pending')) {
        $new_post->statuses_id = 2;
      }
      $new_post->link_post = $slug;
      $new_post->title_post = $req->judulpost;
      $new_post->description_post = $req->deskripsi;
      $new_post->content_post = $req->konten;

      if ($req->gambar != null) {
        $gambar = $req->file('gambar');
        $fileInfo = $gambar->getClientOriginalExtension();
        $newname = $slug.'.'.$fileInfo;

        File::delete('upload/thumbnail/'.$new_post->image_post);

        $dir = 'upload/thumbnail';
        $gambar->move($dir,$newname);

        $new_post->image_post = $newname;
      }

      $new_post->save();

      $new_post->Categories()->sync($tag_id);
      Session::flash('infoCRUD','Data berhasil di update.');
      return redirect('/prev/articles');
    }
  }

  public function jsonTags($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $data = DB::table('categories_posts')
      ->select('categories_posts.categories_id')
      ->whereIn('categories_posts.posts_id', function($query) use ($id){
        $query->select('id')
        ->from('posts')
        ->where("posts.id","=",$id);
      })
      ->groupBy('categories_posts.categories_id')
      ->get();

      // $rplc = ['"categories_id":','{','}'];
      // $data = strtr($rplc,$data);
      $data = str_replace('"categories_id":','',$data);
      $data = str_replace('{','',$data);
        $data = str_replace('}','',$data);
        return $data;
      }
    }
  // ------------ Articles -------------

  // ------------ Labels -------------
  public function labels() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');

    } else {
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      $labels = Categories::orderBy('id','desc')->get();
      return view('prev/labels/labels',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'labels'        => $labels,
      ]);
    }
  }

  public function createLabel() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      return view('prev/labels/create',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
      ]);
    }
  }

  public function storeLabel(Request $req) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $this->validate($req, [
        'nama_kategori' => 'required',
        'gambar'        => 'required|file|image|mimes:svg',
      ]);
      $slug = Str::slug(strtolower($req->nama_kategori));
      $gambar = $req->file('gambar');
      $fileInfo = $gambar->getClientOriginalExtension();
      $newname = $slug.'.'.$fileInfo;
      $dir = 'upload/label';

      $gambar->move($dir,$newname);

      $new_data = new Categories();
      $new_data->variable = $slug;
      $new_data->name_category = strtolower($req->nama_kategori);
      $new_data->link_category = $slug;
      $new_data->icon_category = $newname;
      $new_data->save();

      Session::flash('infoCRUD','Data berhasil di upload.');
      return redirect('/prev/labels');
    }
  }

  public function editLabel($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $categories = Categories::find($id);
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      return view('prev/labels/update',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'data'      => $categories,
      ]);
    }
  }

  public function updateLabel(Request $req, $id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $this->validate($req, [
        'nama_kategori' => 'required',
        'gambar'=>'file|image|mimes:svg',
      ]);

      $slug = Str::slug(strtolower($req->nama_kategori));
      $new_data = Categories::find($id);
      $new_data->variable = $slug;
      $new_data->name_category = strtolower($req->nama_kategori);
      $new_data->link_category = $slug;

      if ($req->gambar != null) {
        $gambar = $req->file('gambar');
        $fileInfo = $gambar->getClientOriginalExtension();
        $newname = $slug.'.'.$fileInfo;
        $dir = 'upload/label';

        File::delete('upload/label/'.$new_data->icon_category);

        $gambar->move($dir,$newname);
        $new_data->icon_category = $newname;
      }

      $new_data->save();

      Session::flash('infoCRUD','Data berhasil di upload.');
      return redirect('/prev/labels');
    }
  }

  public function labelsDelete() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');

    } else {
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      $labels = Categories::onlyTrashed()->orderBy('id','desc')->get();
      return view('prev/labels/labels',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'labels'          => $labels,
      ]);
    }
  }

  public function deleteLabel($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $categories = Categories::find($id);
      $categories->delete();

      $categories_posts = Categoriesposts::where('categories_id',$id);
      $categories_posts->delete();

      Session::flash('infoCRUD','Data berhasil di hapus.');
      return redirect()->back();
    }
  }

  public function deleteLabelPermanent($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $post_tags = Categoriesposts::onlyTrashed()->where('categories_id',$id);
      $post_tags->delete();

      $categories = Categories::onlyTrashed()->where('id',$id);
      $d = $categories->get();
      File::delete('upload/label/'.$d[0]->icon_category);
      $categories->forceDelete();

      Session::flash('infoCRUD','Data berhasil di hapus permanen .');
      return redirect()->back();
    }
  }

  public function restoreLabel($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $post_tags = Categoriesposts::onlyTrashed()->where('categories_id',$id);
      $post_tags->restore();

      $categories = Categories::onlyTrashed()->where('id',$id);
      $categories->restore();

      Session::flash('infoCRUD','Data berhasil di kembalikan.');
      // return redirect('/prev/articles');
      return redirect()->back();
    }
  }

  public function labelRestoreAll() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $post_tags = Categoriesposts::onlyTrashed();
      $post_tags->restore();

      $categories = Categories::onlyTrashed();
      $categories->restore();

      Session::flash('infoCRUD','Semua data berhasil di kembalikan.');
      return redirect()->back();
    }
  }
  // ------------ Labels -------------

  // ------------ Pages -------------
  public function pages(){
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');

    } else {
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      $pages = Pages::orderBy('id','desc')->get();
      return view('prev/pages/pages',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'pages'        => $pages,
      ]);
    }
  }

  public function pagesPublish(){
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');

    } else {
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      $pages = Pages::where('statuses_id',1)->orderBy('id','desc')->get();
      return view('prev/pages/pages',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'pages'        => $pages,
      ]);
    }
  }

  public function pagesPending(){
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');

    } else {
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      $pages = Pages::where('statuses_id',2)->orderBy('id','desc')->get();
      return view('prev/pages/pages',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'pages'        => $pages,
      ]);
    }
  }

  public function pagesDelete() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');

    } else {
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      $pages = Pages::onlyTrashed()->orderBy('id','desc')->get();
      return view('prev/pages/pages',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'pages'        => $pages,
      ]);
    }
  }

  public function deletepage($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $page = Pages::find($id);
      $page->delete();
      $Viewpages = Viewpages::where('Pages_id',$id);
      $Viewpages->delete();
      Session::flash('infoCRUD','Data berhasil di hapus.');
      // return redirect('/prev/pages');
      return redirect()->back();
    }
  }

  public function deletepagePermanent($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $page = Pages::onlyTrashed()->where('id',$id);
      $d = $page->get();
      File::delete('upload/pages/'.$d[0]->image_page);
      $page->forceDelete();

      $Viewpages = Viewpages::onlyTrashed()->where('Pages_id',$id);
      $Viewpages->forceDelete();
      Session::flash('infoCRUD','Data berhasil di hapus permanen .');
      return redirect()->back();
    }
  }

  public function restorepage($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $page = Pages::onlyTrashed()->where('id',$id);
      $page->restore();

      $Viewpages = Viewpages::onlyTrashed()->where('Pages_id',$id);
      $Viewpages->restore();

      Session::flash('infoCRUD','Data berhasil di kembalikan.');
      // return redirect('/prev/pages');
      return redirect()->back();
    }
  }

  public function pagesRestoreAll() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $page = Pages::onlyTrashed();
      $page->restore();

      $Viewpages = Viewpages::onlyTrashed();
      $Viewpages->restore();

      Session::flash('infoCRUD','Semua data berhasil di kembalikan.');
      // return redirect('/prev/pages');
      return redirect()->back();
    }
  }

  public function publishpage($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $page = Pages::find($id);
      $page->statuses_id = 1;
      $page->save();

      Session::flash('infoCRUD','Data berhasil di publish.');
      // return redirect('/prev/pages');
      return redirect()->back();
    }
  }

  public function pendingpage($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $page = Pages::find($id);
      $page->statuses_id = 2;
      $page->save();

      Session::flash('infoCRUD','Data berhasil di pending.');
      // return redirect('/prev/pages');
      return redirect()->back();
    }
  }

  public function createpage() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      return view('prev/pages/create',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
      ]);
    }
  }

  public function storePage(Request $req) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $this->validate($req,[
        'judulpage'=>'required|min:8|max:200',
        'gambar'=>'required|file|image|mimes:jpeg,png,jpg',
        'konten'=>'required|min:50',
        'link_page'=>'required|min:4|max:200',
        'deskripsi'=>'required|min:20'
      ]);
      $user_id = Session::get('id_user');
      $slug = Str::slug(strtolower($req->link_page));
      $gambar = $req->file('gambar');
      if ($gambar != null) {
        $fileInfo = $gambar->getClientOriginalExtension();
        $newname = $slug.'.'.$fileInfo;

        $dir = 'upload/pages';
        $gambar->move($dir,$newname);

      } else {
        $newname = 'default.jpg';
      }

      $new_page = new Pages();
      $new_page->users_id = $user_id;
      if ($req->has('publish')) {
        $new_page->statuses_id = 1;
      } elseif ($req->has('pending')) {
        $new_page->statuses_id = 2;
      }
      $new_page->link_page = $slug;
      $new_page->title_page = $req->judulpage;
      $new_page->description_page = $req->deskripsi;
      $new_page->content_page = $req->konten;

      $new_page->image_page = $newname;

      $new_page->save();

      Session::flash('infoCRUD','Data berhasil di upload.');
      return redirect('/prev/pages');
    }
  }

  public function editPage($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $data = Pages::find($id);
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      return view('prev/pages/update',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'data'      => $data,
      ]);
    }
  }

  public function updatePage(Request $req, $id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $this->validate($req,[
        'judulpage'=>'required|min:8|max:200',
        'konten'=>'required|min:50',
        'gambar'=>'file|image|mimes:jpeg,png,jpg',
        'link_page'=>'required|min:4|max:200',
        'deskripsi'=>'required|min:20'
      ]);
      $user_id = Session::get('id_user');
      $slug = Str::slug(strtolower($req->link_page));

      $new_page = Pages::find($id);
      $new_page->users_id = $user_id;
      if ($req->has('publish')) {
        $new_page->statuses_id = 1;
      } elseif ($req->has('pending')) {
        $new_page->statuses_id = 2;
      }
      $new_page->link_page = $req->link_page;
      $new_page->title_page = $req->judulpage;
      $new_page->description_page = $req->deskripsi;
      $new_page->content_page = $req->konten;

      if ($req->gambar != null) {
        $gambar = $req->file('gambar');
        $fileInfo = $gambar->getClientOriginalExtension();
        $newname = $slug.'.'.$fileInfo;

        File::delete('upload/pages/'.$new_page->image_page);

        $dir = 'upload/pages';
        $gambar->move($dir,$newname);

        $new_page->image_page = $newname;
      }

      $new_page->save();

      Session::flash('infoCRUD','Data berhasil di update.');
      return redirect('/prev/pages');
    }
  }
  // ------------ Pages -------------

  // ------------ Statistic -------------
  public function statisticArticles() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');

    }else {
      $hari = date('Y-m-d');
      $bulan = date('Y-m');
      $tahun = date('Y');
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();

      $ViewDay = Views::select(DB::raw('*,count(*) as countVisitor'))->where('created_at','LIKE',"%$hari%")->groupBy('posts_id')->orderBy('posts_id','asc')->get();
      $cDay = Views::select(DB::raw('count(*) as countVisitor'))->where('created_at','LIKE',"%$hari%")->get();
      $countryDay = Views::select(DB::raw('id,location,count(*) as countries'))->where('created_at','LIKE',"%$hari%")->groupBy('location')->orderBy('location','asc')->get();

      $ViewMonth = Views::select(DB::raw('*,count(*) as countVisitor'))->where('created_at','LIKE',"%$bulan%")->groupBy('posts_id')->orderBy('posts_id','asc')->get();
      $cMonth = Views::select(DB::raw('count(*) as countVisitor'))->where('created_at','LIKE',"%$bulan%")->get();
      $countryMonth = Views::select(DB::raw('id,location,count(*) as countries'))->where('created_at','LIKE',"%$bulan%")->groupBy('location')->orderBy('location','asc')->get();

      $ViewYear = Views::select(DB::raw('*,count(*) as countVisitor'))->where('created_at','LIKE',"%$tahun%")->groupBy('posts_id')->orderBy('posts_id','asc')->get();
      $cYear = Views::select(DB::raw('count(*) as countVisitor'))->where('created_at','LIKE',"%$tahun%")->get();
      $countryYear = Views::select(DB::raw('id,location,count(*) as countries'))->where('created_at','LIKE',"%$tahun%")->groupBy('location')->orderBy('location','asc')->get();

      return view('prev/statistics/articles',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'color'           => $this->color,

        'viewDay'         => $ViewDay,
        'totalVisitorDay'    => $cDay,
        'countryDay'         => $countryDay,
        'viewMonth'         => $ViewMonth,
        'totalVisitorMonth'    => $cMonth,
        'countryMonth'         => $countryMonth,
        'viewYear'         => $ViewYear,
        'totalVisitorYear'    => $cYear,
        'countryYear'         => $countryYear,

      ]);
    }
  }

  public function statisticPages() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');

    }else {
      $hari = date('Y-m-d');
      $bulan = date('Y-m');
      $tahun = date('Y');
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();

      $ViewDay = Viewpages::select(DB::raw('*,count(*) as countVisitor'))->where('created_at','LIKE',"%$hari%")->groupBy('pages_id')->orderBy('pages_id','asc')->get();
      $cDay = Viewpages::select(DB::raw('count(*) as countVisitor'))->where('created_at','LIKE',"%$hari%")->get();
      $countryDay = Viewpages::select(DB::raw('id,location,count(*) as countries'))->where('created_at','LIKE',"%$hari%")->groupBy('location')->orderBy('location','asc')->get();

      $ViewMonth = Viewpages::select(DB::raw('*,count(*) as countVisitor'))->where('created_at','LIKE',"%$bulan%")->groupBy('pages_id')->orderBy('pages_id','asc')->get();
      $cMonth = Viewpages::select(DB::raw('count(*) as countVisitor'))->where('created_at','LIKE',"%$bulan%")->get();
      $countryMonth = Viewpages::select(DB::raw('id,location,count(*) as countries'))->where('created_at','LIKE',"%$bulan%")->groupBy('location')->orderBy('location','asc')->get();

      $ViewYear = Viewpages::select(DB::raw('*,count(*) as countVisitor'))->where('created_at','LIKE',"%$tahun%")->groupBy('pages_id')->orderBy('pages_id','asc')->get();
      $cYear = Viewpages::select(DB::raw('count(*) as countVisitor'))->where('created_at','LIKE',"%$tahun%")->get();
      $countryYear = Viewpages::select(DB::raw('id,location,count(*) as countries'))->where('created_at','LIKE',"%$tahun%")->groupBy('location')->orderBy('location','asc')->get();

      return view('prev/statistics/pages',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'color'           => $this->color,

        'viewDay'         => $ViewDay,
        'totalVisitorDay'    => $cDay,
        'countryDay'         => $countryDay,
        'viewMonth'         => $ViewMonth,
        'totalVisitorMonth'    => $cMonth,
        'countryMonth'         => $countryMonth,
        'viewYear'         => $ViewYear,
        'totalVisitorYear'    => $cYear,
        'countryYear'         => $countryYear,

      ]);
    }
  }

  public function statisticVisitors() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');

    }else {
      $bulan = date('Y-m-d');
      // $bulan = '2020-02';
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      $Tadmins = count(Users::where('levels_id','2')->get());
      $Tusers = count(Users::where('levels_id','3')->get());
      $Tarticles = count(Posts::all());
      $Tpages = count(Pages::all());
      $ViewDay = Views::select(DB::raw('*,count(*) as countVisitor'))->where('created_at','LIKE',"%$bulan%")->groupBy('posts_id')->orderBy('created_at','asc')->get();
      $c = Views::select(DB::raw('count(*) as countVisitor'))->where('created_at','LIKE',"%$bulan%")->get();
      $country = Views::select(DB::raw('id,location,count(*) as countries'))->where('created_at','LIKE',"%$bulan%")->groupBy('location')->orderBy('location','asc')->get();
      return view('prev/statistics/visitors',[
                  "titleWeb"        => $this->titleWeb,
                  "sloganWeb"       => $this->sloganWeb,
                  "descWeb"         => $this->descWeb,
                  "iconWeb"         => $this->iconWeb,
                  "url"             => $this->url,
                  "img"             => $this->img,
                  "versionWeb"      => $this->versionWeb,
                  "created_at"      => $this->created_at,
                  "updated_at"      => $this->updated_at,
                  'myProfile'       => $this->userProfile,
                  'Tadmins'         => $Tadmins,
                  'Tusers'          => $Tusers,
                  'Tarticles'       => $Tarticles,
                  'Tpages'          => $Tpages,
                  'viewDay'         => $ViewDay,
                  'totalVisitor'    => $c,
                  'country'         => $country,
                  'color'           => $this->color,
                ]);
    }
  }

  // ------------ Statistic -------------

  // ------------ Gallery -------------
  public function gallery() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      $data = Folders::orderBy('id','desc')->get();
      return view('prev/gallery/folder',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'data'      => $data,
      ]);
    }
    }

    public function galleryFolder($id) {
      if (empty(Session::get('login'))) {
        Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
        return redirect('/prev/login');
      } else {
        $this->userProfile = Users::where('id',Session::get('id_user'))->get();
        $data = Images::with('folders')->having('folders_id','=',$id)->get();
        // dd($data);
        return view('prev/gallery/images',[
          "titleWeb"        => $this->titleWeb,
          "sloganWeb"       => $this->sloganWeb,
          "descWeb"         => $this->descWeb,
          "iconWeb"         => $this->iconWeb,
          "url"             => $this->url,
          "img"             => $this->img,
          "versionWeb"      => $this->versionWeb,
          "created_at"      => $this->created_at,
          "updated_at"      => $this->updated_at,
          'myProfile'       => $this->userProfile,
          'data'      => $data,
        ]);
      }
    }

    public function galleryFolderStore(Request $req) {
      if (empty(Session::get('login'))) {
        Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
        return redirect('/prev/login');
      } else {
        $this->validate($req, [
          'nama_folder' => 'required'
        ]);

        $slug = Str::slug(strtolower($req->nama_folder));
        $new_data = new Folders();
        $new_data->name_folder = $slug;
        $new_data->save();

        File::makeDirectory('upload/content/'.$slug);
        Session::flash('infoCRUD','Folder berhasil dibuat.');
        return redirect()->back();
      }
    }

    public function galleryImageStore(Request $req, $id) {
      if (empty(Session::get('login'))) {
        Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
        return redirect('/prev/login');
      } else {
        $this->validate($req, [
          'gambar'=>'required|file|image|mimes:jpeg,png,jpg,gif',
        ]);
        $folder = Folders::find($id);
        $file = $req->file('gambar');
        $nfolder = $folder->name_folder;
        $tmp_newname = time().'-'.strtolower($file->getClientOriginalName()).'-'.$nfolder;
        $newname =  md5(Crypt::encryptString($tmp_newname).'.'.$file->getClientOriginalExtension());

        $new_data = new Images();
        $new_data->folders_id = $folder->id;
        $new_data->image = $newname;
        $new_data->save();

        $dir = 'upload/content/'.$nfolder;
        $file->move($dir,$newname);

        Session::flash('infoCRUD','Folder berhasil dibuat.');
        return redirect()->back();
      }
    }

    public function galleryFolderDelete($id) {
      if (empty(Session::get('login'))) {
        Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
        return redirect('/prev/login');
      } else {
        $folder = Folders::find($id);
        $fid = $folder->id;
        $slug = $folder->name_folder;

        // return $slug;

        File::deleteDirectory('upload/content/'.$slug);
        $image = Images::where('folders_id',$fid);
        $image->delete();
        $folder->delete();

        Session::flash('infoCRUD','Folder berhasil dihapus.');
        return redirect()->back();
      }
    }

  // ------------ Gallery -------------

  // ------------ Settings -------------
    public function keywords() {
      if (empty(Session::get('login'))) {
        Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
        return redirect('/prev/login');
      } else {
        $data = Keywords::all();
        $this->userProfile = Users::where('id',Session::get('id_user'))->get();
        return view('prev/keywords/keywords',[
          "titleWeb"        => $this->titleWeb,
          "sloganWeb"       => $this->sloganWeb,
          "descWeb"         => $this->descWeb,
          "iconWeb"         => $this->iconWeb,
          "url"             => $this->url,
          "img"             => $this->img,
          "versionWeb"      => $this->versionWeb,
          "created_at"      => $this->created_at,
          "updated_at"      => $this->updated_at,
          'myProfile'       => $this->userProfile,
          'data'      => $data,
        ]);
      }
    }

    public function keywordsCreate() {
      if (empty(Session::get('login'))) {
        Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
        return redirect('/prev/login');
      } else {
        $this->userProfile = Users::where('id',Session::get('id_user'))->get();
        return view('prev/keywords/create',[
          "titleWeb"        => $this->titleWeb,
          "sloganWeb"       => $this->sloganWeb,
          "descWeb"         => $this->descWeb,
          "iconWeb"         => $this->iconWeb,
          "url"             => $this->url,
          "img"             => $this->img,
          "versionWeb"      => $this->versionWeb,
          "created_at"      => $this->created_at,
          "updated_at"      => $this->updated_at,
          'myProfile'       => $this->userProfile,
        ]);
      }
    }

    public function keywordsStore(Request $req) {
      if (empty(Session::get('login'))) {
        Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
        return redirect('/prev/login');
      } else {
        $this->validate($req, [
          'nama_keyword' => 'required'
        ]);
        $str = strtolower($req->nama_keyword);
        $new_data = new Keywords();
        $new_data->name_keyword = $str;
        $new_data->save();

        Session::flash('infoCRUD','Keyword berhasil dibuat.');
        return redirect('prev/keywords');
      }
    }

    public function keywordsDelete($id) {
      if (empty(Session::get('login'))) {
        Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
        return redirect('/prev/login');
      } else {
        $data = Keywords::find($id);
        $data->delete();

        Session::flash('infoCRUD','Keyword berhasil dihapus.');
        return redirect('prev/keywords');
      }
    }

  // ------------ Settings -------------
  public function profileWeb() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $data = DB::table('titlewebs')->take(1)->get();
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      return view('prev/profile/profileweb',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'data'            => $data,
      ]);
    }
  }

  public function profileWebUpdate(Request $req) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $this->validate($req, [
        'icon'        => 'file|image|mimes:jpeg,png,jpg',
        'title'       => 'required|max:25',
        'version'     => 'required|max:4',
        'slogan'      => 'required|max:250',
        'description' => 'required|max:500',
        'thumbnail'   => 'file|image|mimes:jpeg,png,jpg',
      ]);

      $rData = Titlewebs::find(1);

      $icon = $req->file('icon');
      $thumbnail = $req->file('thumbnail');
      $title = ucwords($req->title);
      $version = $req->version;
      $slogan = $req->slogan;
      $description = $req->description;
      if ($icon == null) {
        $newname_icon =  $rData->icon_web;
      } else {
        $newname_icon =  'logo-'.date('dhmis').'.'.$icon->getClientOriginalExtension();
      }

      if ($thumbnail == null) {
        $newname_thumbnail =  $rData->thumbnail_web;
      } else {
        $newname_thumbnail =  'dashboard-'.date('dhmis').'.'.$thumbnail->getClientOriginalExtension();
      }

      $new_data = Titlewebs::find(1);
      $new_data->title_web = $title;
      $new_data->slogan_web = $slogan;
      $new_data->description_web = $description;
      $new_data->icon_web = $newname_icon;
      $new_data->thumbnail_web = $newname_thumbnail;
      $new_data->version_web = $version;
      $new_data->save();

      if ($icon != null) {
        $icon->move(public_path(),$newname_icon);
      }
      if ($thumbnail != null) {
        $thumbnail->move(public_path(),$newname_thumbnail);
      }


      Session::flash('infoCRUD','Profil Web berhasil di update.');
      return redirect('prev/profile-web');
    }
  }

  public function users() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      // $str = ['$','!','%','^','0','1','&','=','.','A','y','x','X','c','s',','];
      // $n1 = $str[8].$str[2].$str[4].$str[6].$str[0].$str[10].$str[12].$str[14];
      // $n2 = $str[1].$str[3].$str[5].$str[7].$str[9].$str[2].$str[0].$str[1].$str[11].$str[15].$str[13].$str[7].$str[9];
      // $pass = $n1.md5(sha1($req->password)).$n2;
      //
      $data = Users::all();
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      return view('prev/users/users',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'data'      => $data,
      ]);
    }
  }

  public function usersDelete() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $data = Users::onlyTrashed()->orderBy('id','desc')->get();
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      return view('prev/users/users',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'data'      => $data,
      ]);
    }
  }

  public function usersTrash($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {

      $user = Users::find($id);
      $user->delete();
      Session::flash('infoCRUD','Data berhasil di hapus.');
      return redirect()->back();
    }
  }

  public function deleteUserPermanent($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $user = Users::onlyTrashed()->where('id',$id);
      $d = $user->get();
      File::delete('upload/profile/'.$d[0]->avatar);
      $user->forceDelete();

      Session::flash('infoCRUD','Data berhasil di hapus permanen .');
      return redirect()->back();
    }
  }

  public function restoreUser($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $user = Users::onlyTrashed()->where('id',$id);
      $user->restore();
      Session::flash('infoCRUD','Data berhasil di kembalikan.');
      return redirect()->back();
    }
  }

  public function usersRestoreAll() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $user = Users::onlyTrashed();
      $user->restore();
      Session::flash('infoCRUD','Semua data berhasil di kembalikan.');
      return redirect()->back();
    }
  }

  public function activeUser($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $users = Users::find($id);
      $users->status = 'active';
      $users->save();
      Session::flash('infoCRUD','User berhasil di aktifkan.');
      return redirect()->back();
    }
  }

  public function pendingUser($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $users = Users::find($id);
      $users->status = 'pending';
      $users->save();
      Session::flash('infoCRUD','User berhasil di Nonaktifkan.');
      return redirect()->back();
    }
  }

  public function usersCreate() {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $level = Levels::orderBy('id','desc')->get();
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      return view('prev/users/create',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'levels'          => $level,
      ]);
    }
  }

  public function usersStore(Request $req) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $this->validate($req, [
        'user_nama' => 'required|min:3',
        'foto_profil' => 'required|file|image|mimes:jpeg,png,jpg,svg',
        'user_email' => 'required|min:8',
        'user_username' => 'required|min:3',
        'user_password' => 'required|min:8',
        'user_level' => 'required',
      ]);
      $str = ['$','!','%','^','0','1','&','=','.','A','y','x','X','c','s',','];
      $n1 = $str[8].$str[2].$str[4].$str[6].$str[0].$str[10].$str[12].$str[14];
      $n2 = $str[1].$str[3].$str[5].$str[7].$str[9].$str[2].$str[0].$str[1].$str[11].$str[15].$str[13].$str[7].$str[9];
      $pass_tmp = $n1.md5(sha1($req->user_password)).$n2;
      $pass = "c1".md5($pass_tmp);


      $avatar_tmp = $req->file('foto_profil');
      $avatar = uniqid().'.'.$avatar_tmp->getClientOriginalExtension();

      $new_data = new Users();
      $new_data->levels_id = $req->user_level;
      $new_data->avatar = $avatar;
      $new_data->name_user = $req->user_nama;
      $new_data->email_user = $req->user_email;
      $new_data->username = $req->user_username;
      $new_data->password = $pass;
      $new_data->status = 'pending';
      $new_data->save();

      $dir = 'upload/profile';
      $avatar_tmp->move($dir,$avatar);

      Session::flash('infoCRUD','User berhasil dibuat.');
      return redirect('prev/users');
    }
  }

  public function usersEdit($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $data = Users::find($id);
      $level = Levels::orderBy('id','desc')->get();
      $this->userProfile = Users::where('id',Session::get('id_user'))->get();
      return view('prev/users/update',[
        "titleWeb"        => $this->titleWeb,
        "sloganWeb"       => $this->sloganWeb,
        "descWeb"         => $this->descWeb,
        "iconWeb"         => $this->iconWeb,
        "url"             => $this->url,
        "img"             => $this->img,
        "versionWeb"      => $this->versionWeb,
        "created_at"      => $this->created_at,
        "updated_at"      => $this->updated_at,
        'myProfile'       => $this->userProfile,
        'levels'          => $level,
        'data'            => $data,
      ]);
    }
  }

  public function usersUpdate(Request $req, $id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {

      if ($req->user_password != null) {
        $this->validate($req, [
          'user_nama' => 'required|min:3',
          'foto_profil' => 'file|image|mimes:jpeg,png,jpg,svg',
          'user_email' => 'required|min:8',
          'user_username' => 'required|min:3',
          'user_password' => 'required|min:8',
          'user_level' => 'required',
        ]);
      } else {
        $this->validate($req, [
          'user_nama' => 'required|min:3',
          'foto_profil' => 'file|image|mimes:jpeg,png,jpg,svg',
          'user_email' => 'required|min:8',
          'user_username' => 'required|min:3',
          'user_level' => 'required',
        ]);
      }




      $new_data = Users::find($id);
      $new_data->levels_id = $req->user_level;

      if ($req->foto_profil != null) {
        $avatar_tmp = $req->file('foto_profil');
        $avatar = uniqid().'.'.$avatar_tmp->getClientOriginalExtension();


        File::delete('upload/profile/'.$new_data->avatar);

        $dir = 'upload/profile';
        $avatar_tmp->move($dir,$avatar);

        $new_data->avatar = $avatar;
      }

      $new_data->name_user = $req->user_nama;
      $new_data->email_user = $req->user_email;
      $new_data->username = $req->user_username;

      if ($req->user_password != null) {
        $str = ['$','!','%','^','0','1','&','=','.','A','y','x','X','c','s',','];
        $n1 = $str[8].$str[2].$str[4].$str[6].$str[0].$str[10].$str[12].$str[14];
        $n2 = $str[1].$str[3].$str[5].$str[7].$str[9].$str[2].$str[0].$str[1].$str[11].$str[15].$str[13].$str[7].$str[9];
        $pass_tmp = $n1.md5(sha1($req->user_password)).$n2;
        $pass = "c1".md5($pass_tmp);

        $new_data->password = $pass;
      }

      $new_data->save();


      Session::flash('infoCRUD','User berhasil diupdate.');
      return redirect('prev/users');
    }
  }



}
