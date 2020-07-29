<?php
/**
 * Created by O2System Framework File Generator.
 * DateTime: 24/06/2018 13:06
 */

// ------------------------------------------------------------------------

namespace App\Controllers;

// ------------------------------------------------------------------------

use O2System\Framework\Http\Controllers\Restful as Controller;


/**
 * Class Login
 *
 * @package \App\Controllers
 */
class Training extends Controller
{
    var $err = "";
    var $cek = "";
    var $content = array();
    public function index()
    {
      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}
      $limitPost = "limit $from,$to";
      
      $this->cek = "select * from training   order by urutan desc  $limitPost";
      $getDataMember = sqlArray(sqlQuery("select * from member where email = '$email'"));

      $getJumlahItem = sqlArray(sqlQuery("select count(id) from training "));
      //where id_member = '".$getDataMember['id']."'
      $getDataTraining = sqlQuery("select * from training   order by urutan desc  $limitPost");
      while ($dataTraining = sqlArray($getDataTraining)) {

        $arrayTrainings[] = array(
          "id" => $dataTraining['id'],
          "judul_materi" => $dataTraining['judul_materi'],
          "deskripsi_materi" =>  base64_decode($dataTraining['deskripsi_materi']),
          "video_souce" =>  $dataTraining['video_souce'],
          "thumbnail" =>  $dataTraining['thumbnail'],
          "kategori" => $dataTraining['kategori'],
          "wajib_tonton" => $dataTraining['wajib_tonton'],
          "urutan" => $dataTraining['urutan'],
          "tanggal_update" => $this->generateDate($dataTraining['tanggal_update']),
          "jam_update" => $dataTraining['jam_update'],
          "durasi_video" => $dataTraining['durasi_video'],
          "status" => $dataTraining['status'],
          "maxItem" => $getJumlahItem['count(id)']
        );
      }




      $this->content = $arrayTrainings;
      $this->sendPayload(
          [
              'request' => [
                  'method' => $_SERVER[ 'REQUEST_METHOD' ],
                  'time'   => $_SERVER[ 'REQUEST_TIME' ],
                  'uri'    => $_SERVER[ 'REQUEST_URI' ],
                  'agent'  => $_SERVER[ 'HTTP_USER_AGENT' ],
              ],
              'cek'  => $this->cek,
              'content'  => $this->content,
              'err' => $this->err
          ]
      );

    }

    function numberFormat($value,$angka = 0){
      // return number_format($value,$angka,'.',',');
      return number_format($value,$angka,",",".");
    }
    function convertTimeToInteger($concatTanggalJam){
      $explodeTanggalJamConcat = explode(";",$concatTanggalJam);
      $integerTanggal = $this->dateToInteger($this->generateDate($explodeTanggalJamConcat[0]));
      $integerJam = $this->timeToInteger($explodeTanggalJamConcat[1]);
      $integerValue = $integerTanggal + $integerJam;
      return $integerValue;
    }
    function dateToInteger($date){
      $explodeTanggal = explode("-",$date);
      $hari = $explodeTanggal[2] * (24 * 60 ) ;
      $bulan = $explodeTanggal[1] * (30 * (24 * 60) ) ;
      $tahun = $explodeTanggal[0] * (365 * (30 * (24 * 60) ) ) ;
      $integerValue = $tahun + $bulan + $hari;
      return $integerValue;
    }
    function timeToInteger($time){
      $explodeJam = explode(":",$time);
      $jam = $explodeJam[0] * 60;
      $menit = $explodeJam[1] ;
      $integerValue = $jam + $menit;
      return $integerValue;
    }

    function generateDate($tanggal){
          $tanggal = explode('-',$tanggal);
          $tanggal = $tanggal[2]."-".$tanggal[1]."-".$tanggal[0];
          return $tanggal;
    }
}
