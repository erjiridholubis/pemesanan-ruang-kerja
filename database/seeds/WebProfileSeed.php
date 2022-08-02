<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebProfileSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('web_profile')->insert([
          'logo' => 'logo.png',
          'thumbnail' => 'dashboard.jpg',
          'title' => 'PT. Sentral Kreasindo Indonesia',
          'slogan' => 'PT. Sentral Kreasindo Indonesia',
          'description' => 'PT. Sentral Kreasindo Indonesia adalah perusahaan yang bergerak di bidang coworking space dan caffe bernama My Office Coworking Space',
          'version' => '1.0',
          'phone' => '0808080808123',
          'email' => 'contact@polsri.ac.id',
          'ig' => '@polsri',
          'line' => '@polsri',
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
