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
class Ongkir extends Controller
{
    var $err = "";
    var $cek = "";
    var $content = array();
    public function index()
    {
      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}




      $this->content = $arrayOngkir;
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
    public function getProvinsi()
    {
      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}

      $this->cek = "";
      $data = array('servicePengiriman' => $servicePengiriman );
			$curl = curl_init('https://saudagarkaya.com/api/getProvinsi') ;
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		  $resultCurl = curl_exec($curl);

      // $this->content = str_replace("\"","",str_replace('\\',"",$resultCurl));
      $this->content =  $resultCurl;
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
    public function getKota()
    {
      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}

      $this->cek = "";
      $data = array('servicePengiriman' => $servicePengiriman,'ProvinsiPembeli' => $idProvinsi );
			$curl = curl_init('https://saudagarkaya.com/api/getKota') ;
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		  $resultCurl = curl_exec($curl);

      $this->content = $resultCurl;
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
    public function getOngkir()
    {
      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}

      $this->cek = "";

      $layananPengiriman = "jne";
      $beratBarang= 250;
      $idProvinsi = 11;
      $idKota = 80;
      $servicePengiriman = 0;

      $data = array(
        'servicePengiriman' => $servicePengiriman,
        'ProvinsiPembeli' => $idProvinsi,
        "KotaPembeli" => $idKota,
        "destination" => $idKota,
        "weight" => $beratBarang,
        "jsonOngkir" => '[{"code":"jne","name":"Jalur Nugraha Ekakurir (JNE)","costs":[{"service":"OKE","description":"Ongkos Kirim Ekonomis","cost":[{"value":16000,"etd":"3-6","note":""}]},{"service":"REG","description":"Layanan Reguler","cost":[{"value":20000,"etd":"2-3","note":""}]}]}]',
      );
			$curl = curl_init('https://saudagarkaya.com/api/getOngkir') ;
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		  $resultCurl = curl_exec($curl);

      $this->content = $resultCurl;
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
    public function getService()
    {
      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}

      $this->cek = "";

      // $layananPengiriman = "pos";
      // $beratBarang= 250;
      // $idProvinsi = 11;
      // $idKota = 80;

      $data = array(
        'ProvinsiPembeli' => $idProvinsi,
        "KotaPembeli" => $idKota,
        "destination" => $idKota,
        "layananPengiriman" => $layananPengiriman,
        "weight" => $beratBarang,
      );
			$curl = curl_init('https://saudagarkaya.com/api/getService') ;
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		  $resultCurl = curl_exec($curl);

      $this->content = $resultCurl;
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
