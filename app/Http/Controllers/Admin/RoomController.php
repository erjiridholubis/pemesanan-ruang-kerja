<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Session;
use Str;
use Hash;
use Alert;

use App\Room;
use App\Type;
use App\Facility;

class RoomController extends Controller
{
    public function __construct() {
    $this->middleware('auth');
  }

  public function index() {
    $data = Room::with('type')->get();
    $type = Type::all();
    $facilities = Facility::all();
    $title = "Semua Ruangan";
    $description = "Data semua ruangan";

    return view('admin.room.room',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
      'type' => $type,
      'facilities' => $facilities,
    ]);
  }

  public function jsonTags($id) {
    
      $data = DB::table('facility_room')
      ->select('facility_room.facility_id')
      ->whereIn('facility_room.room_id', function($query) use ($id){
        $query->select('id')
        ->from('rooms')
        ->where("rooms.id","=",$id);
      })
      ->groupBy('facility_room.facility_id')
      ->get();
      
      return $data;
      
    }

  public function trash() {
    $data = Room::with('type')->onlyTrashed()->get();
    $type = Type::all();
    $facilities = Facility::all();
    $title = "Ruangan Terhapus";
    $description = "Data semua ruangan yang terhapus";

    return view('admin.room.room',[
      'data' => $data,
      'title' => $title,
      'description' => $description,
      'type' => $type,
      'facilities' => $facilities,
    ]);
  }

  public function store(Request $req) {
    $this->validate($req,[
      'name' => 'required|min:4|max:255',
      'image' => 'required|file|image|mimes:jpeg,png,jpg',
      'type' => 'required',
      "facilities" => 'required',
    ]);

    $image = $req->file('image');
    $img_name = "room-".$req->type;
    $fileInfo = $image->getClientOriginalExtension();
    $newname = $img_name.'-'.uniqid().'.'.$fileInfo;
    $dir = 'uploads/rooms/';

    $data = new Room();
    $data->type_id = $req->type;
    $data->name = $req->name;
    $data->image = $newname;
    $data->save();

    $data->Facility()->sync($req->facilities);
    
    $image->move($dir,$newname);

    alert()->success('Data berhasil di tambah');
    return redirect()->back();

  }

  public function update(Request $req, $id) {
    $this->validate($req,[
      'name' => 'required|min:4|max:255',
      'image' => 'file|image|mimes:jpeg,png,jpg',
      'type' => 'required',
    ]);

    
    $data = Room::find($id);

    $img_name = "room-".$req->type;
    $image = $req->file('image');
    if ($image != null) {
        $fileInfo = $image->getClientOriginalExtension();
        $newname = $img_name.'-'.uniqid().'.'.$fileInfo;
    } else {
        $newname = $data->image;
    }
    $dir = 'uploads/rooms/';


    $data->type_id = $req->type;
    $data->name = $req->name;
    if ($image != null) {
      $image->move($dir,$newname);
      File::delete($dir.$data->image);
    }
    $data->image = $newname;
    $data->save();

    $data->Facility()->sync($req->facilities);

    alert()->success('Data berhasil di update');
    return redirect()->back();

  }

  public function delete($id) {
    $data = Room::find($id);
    $data->delete();

    alert()->success('Data berhasil di hapus');
    return redirect()->back();
  }

  public function deletePermanent($id) {
    $data = Room::onlyTrashed()->where('id',$id);
    $data->forceDelete();

    alert()->success('Data berhasil di hapus permanen');
    return redirect()->back();
  }

  public function restore() {
    $data = Room::onlyTrashed();
    $data->restore();

    alert()->success('Semua data berhasil di restore');
    return redirect()->back();

  }

  public function restoreRoom($id) {
    $data = Room::where("id",$id)->onlyTrashed();
    $data->restore();

    alert()->success('Data berhasil di restore');
    return redirect()->back();

  }

}
