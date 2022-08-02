<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use App\WebProfile;
use App\Keyword;
// use GuzzleHttp\Psr7\Request;

use Jenssegers\Agent\Agent;
use PulkitJalan\GeoIP\GeoIP;


class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public $keyword;
  public $logo;
  public $thumbnail;
  public $title;
  public $slogan;
  public $description;
  public $version;
  public $phone;
  public $email;
  public $instagram;
  public $line;
  public $created_at;
  public $updated_at;
  public $url;
  public $return;

  public $ip;
  public $loc;
  public $browser;
  public $os;
  public $device;
  public $robot;

  public function __construct(Request $req)
  {
    $agent = new Agent();
    $geoip = new GeoIP();

    $this->ip = $req->ip();
    $geoip->setIp($this->ip);

    $this->device = $agent->device();
    $this->os = $agent->platform();
    $this->browser = $agent->browser();

    // $this->loc = $geoip->getCountry();
    $this->loc = "Indonesia";

    $this->keyword = Keyword::all();
    $profile = WebProfile::first();

    $this->logo = url($profile->logo);
    $this->thumbnail = url($profile->thumbnail);
    $this->title = $profile->title;
    $this->slogan = $profile->slogan;
    $this->description = $profile->description;
    $this->version = $profile->version;
    $this->phone = $profile->phone;
    $this->email = $profile->email;
    $this->instagram = $profile->ig;
    $this->line = $profile->line;
    $this->created_at = $profile->created_at;
    $this->updated_at = $profile->updated_at;
    $this->url = url('');

    $this->return = [
      "keywords" => $this->keyword,
      "logo" => $this->logo,
      "thumbnail" => $this->thumbnail,
      "title" => $this->title,
      "slogan" => $this->slogan,
      "description" => $this->description,
      "version" => $this->version,
      "phone" => $this->phone,
      "email" => $this->email,
      "instagram" => $this->instagram,
      "line" => $this->line,
      "created_at" => $this->created_at,
      "updated_at" => $this->updated_at,
      "url" => $this->url,
    ];
  }
}