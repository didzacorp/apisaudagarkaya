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
class Leads extends Controller
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
      $getDataMember = sqlArray(sqlQuery("select * from member where email = '$email'"));

      $limitPost = "limit $from,$to";
      $getJumlahItem = sqlArray(sqlQuery("select count(id) from member where upline_level_1 = '".$getDataMember['id']."' "));
      //where id_member = '".$getDataMember['id']."'
      $getDataLead = sqlQuery("select * from member where upline_level_1 = '".$getDataMember['id']."'  order by id desc  $limitPost");
      while ($dataLead = sqlArray($getDataLead)) {
        if(!empty($dataLead['foto'])){
          $foto = "http://member.saudagarkaya.com/assets/images/profile/".$dataLead['foto'];
        }else{
          $foto = "http://member.saudagarkaya.com/assets/images/profile/2_kszxpo.jpg";
        }
        $arrayLeads[] = array(
          "id_member" => $dataLead['id'],
          "nama" => $dataLead['nama'],
          "username" =>  $dataLead['username'],
          "email" =>  $dataLead['email'],
          "alamat" =>  $dataLead['alamat'],
          "nomor_telepon" => $dataLead['nomor_telepon'],
          "tanggal_join" => $this->generateDate($dataLead['tanggal_join']),
          "profit" => $this->numberFormat("5000"),
          "jumlah_barang_terjual" => $this->numberFormat("28"),
          "lisensi" => $dataLead['lisensi'],
          "foto" => $foto,
          "status" => $dataLead['status'],
          "maxItem" => $getJumlahItem['count(id)']
        );
      }
      // $arrayLeads[] = array(
      //   "id_member" => "1",
      //   "nama" => "Dzakir Harist Abdullah",
      //   "username" => "kszxpo",
      //   "email" => "dzakirharista@gmail.com",
      //   "alamat" => "Jl Junaedi no 6 Kota Bandung",
      //   "nomor_telepon" => "081223744803",
      //   "tanggal_join" => $this->generateDate("2019-03-14"),
      //   "profit" => $this->numberFormat("5000"),
      //   "jumlah_barang_terjual" => $this->numberFormat("28"),
      //   "lisensi" => "PREMIUM",
      //   "foto" => "http://member.saudagarkaya.com/assets/images/profile/2_kszxpo.jpg",
      //   "status" => "AKTIF",
      //   "maxItem" => "10"
      // );




      $this->content = $arrayLeads;
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
