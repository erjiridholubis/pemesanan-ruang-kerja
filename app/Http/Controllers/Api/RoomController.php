<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Response;

use App\Room;
use App\Type;
use App\Facility;

class RoomController extends Controller
{
    public function __construct() {
      $this->middleware('token');
    }

    public function index() {
      $data = Room::with('type')->get();
      $data = $data->isEmpty() ? "not found":$data;
      return Response::json(['data'=>$data],$data = $data ? 200:404);
    }

    public function show($id) {
      $data = Room::with('type')->where('id',$id)->get()->first();
      $data = $data ? $data: "not found";
      return Response::json(['data'=>$data],$data = $data ? 200:404);
    }

    public function jsonTags($id) {
    if (empty(Session::get('login'))) {
      Session::flash('infoLogin','<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>Anda belum login !</div>');
      return redirect('/prev/login');
    } else {
      $data = DB::table('facility_room')
      ->select('facility_room.facility_id')
      ->whereIn('facility_room.room_id', function($query) use ($id){
        $query->select('id')
        ->from('rooms')
        ->where("rooms.id","=",$id);
      })
      ->groupBy('facility_room.facilty_id')
      ->get();

      $data = str_replace('"facility_id":','',$data);
      $data = str_replace('{','',$data);
        $data = str_replace('}','',$data);
        return $data;
      }
    }
}
