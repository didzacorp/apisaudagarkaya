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
class CopyWriting extends Controller
{
    var $err = "";
    var $cek = "";
    var $content = array();
    public function index()
    {
      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}

      $this->cek = "";
      // $getDataMember = sqlArray(sqlQuery("select * from member where email = '$email'"));
      $getDataCopyWriting = sqlQuery("select * from copy_writing where status ='AKTIF'");
      while ($dataCopyWriting =  sqlArray($getDataCopyWriting)) {

        $arrayCopyWriting[] = array(
          "id" => $dataCopyWriting['id'],
          "judul" => $dataCopyWriting['judul'],
          "isi_content" => $dataCopyWriting['isi_content'],
          "tanggal" => $dataCopyWriting['tanggal'],
          "status" => $dataCopyWriting['status'],

        );
      }

      // $arrayCopyWriting[] = array(
      //   "id_payment" => "1",
      //   "id_trade_point" => "1",
      //   "tukar_point_title" => "JUDUL",
      //   "tanggal" => "22-03-2020",
      //   "jam" => "14:00",
      //   "status" => "OK",
      // );


      $this->content = $arrayCopyWriting;
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
