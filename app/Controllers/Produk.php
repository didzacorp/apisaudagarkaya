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
class Produk extends Controller
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
      $getDataProduk = sqlQuery("select * from produk where status ='AKTIF'");
      while ($dataProduk =  sqlArray($getDataProduk)) {
        $arrayJsonMedia = json_decode($dataProduk['media']);
        if($getDataMember['lisensi'] == "PREMIUM"){
          $profit = $dataProduk['profit_premium'];
        }else{
          $profit = $dataProduk['profit'];
        }
        $arrayProduk[] = array(
          "id" => $dataProduk['id'],
          "nama_produk" => $dataProduk['nama_produk']." ( ".$dataProduk['berat']." Gram )",
          "harga" => $this->numberFormat($dataProduk['harga']),
          "profit" => $this->numberFormat($profit),
          // "profit_premium" => $dataProduk['profit_premium'],
          "deskripsi" => $dataProduk['deskripsi'],
          "komisi" => $dataProduk['komisi'],
          "media" => $dataProduk['media'],
          "main_image" => $arrayJsonMedia[0]->sourceMedia,
          "berat" => $dataProduk['berat'],
          "kategori" => $dataProduk['kategori'],
        );
      }
      // $arrayProduk[] = array(
      //   "id" => "1",
      //   "title" => "Super Goat",
      //   "price" => "75.000",
      //   "description" => "Susu SuperGoat merupakan sebuah inovasi baru susu kambing etawa bubuk yang dikolaborasikan dengan gula aren murni. Meski sudah diolah menjadi bubuk, khasiat dan manfaat dari susu alami ini masih terjaga. Hal ini dikarenakan produksinya diawasi dengan sangat ketat dari hulu sampai hilir, sehingga ada jaminan kualitas terbaik yang akan dirasakan oleh konsumen.",
      //   "stock" => "100",
      //   "gambar" => "http://supergoat.biz/wp-content/uploads/2020/01/1-6.png",
      // );
      // $arrayProduk[] = array(
      //   "id" => "2",
      //   "title" => "Super Goat Vanilla",
      //   "price" => "75.000",
      //   "description" => "Susu SuperGoat merupakan sebuah inovasi baru susu kambing etawa bubuk yang dikolaborasikan dengan gula aren murni. Meski sudah diolah menjadi bubuk, khasiat dan manfaat dari susu alami ini masih terjaga. Hal ini dikarenakan produksinya diawasi dengan sangat ketat dari hulu sampai hilir, sehingga ada jaminan kualitas terbaik yang akan dirasakan oleh konsumen.",
      //   "stock" => "100",
      //   "gambar" => "http://supergoat.biz/wp-content/uploads/2020/01/SUSU-ANYAR.png",
      // );

      $this->content = $arrayProduk;
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
    public function detail()
    {
      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}

      $this->cek = "";
      $getDataMember = sqlArray(sqlQuery("select * from member where email = '$email'"));
      $getDataProduk = sqlArray(sqlQuery("select * from produk where status ='AKTIF' and id = '$id_produk'"));
      $arrayJsonMedia = json_decode($getDataProduk['media']);
      if($getDataMember['lisensi'] == "PREMIUM"){
        $profit = $getDataProduk['profit_premium'];
      }else{
        $profit = $getDataProduk['profit'];
      }
      $arrayProduk[] = array(
        "id" => $getDataProduk['id'],
        "nama_produk" => $getDataProduk['nama_produk']." ( ".$getDataProduk['berat']." Gram )",
        "harga" => $this->numberFormat($getDataProduk['harga']),
        "profit" => $this->numberFormat($profit),
        "deskripsi" => base64_decode($getDataProduk['deskripsi']),
        "komisi" => $getDataProduk['komisi'],
        "media" => $getDataProduk['media'],
        "main_image" => $arrayJsonMedia[0]->sourceMedia,
        "berat" => $getDataProduk['berat'],
        "kategori" => $getDataProduk['kategori'],
      );

      $this->content = $arrayProduk;
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
    public function getWeight()
    {
      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}

      $this->cek = "";
      $explodeArrayCart = explode(";",$implodeIdAndJumlah);
      for ($i=0; $i < sizeof($explodeArrayCart) ; $i++) {
        $explodeIdProduk = explode(",",$explodeArrayCart[$i]);
        $getDataProduk = sqlArray(sqlQuery("select * from produk where id = '".$explodeIdProduk[0]."'"));
        $beratBarang += $getDataProduk['berat'] * $explodeIdProduk[1];
      }
      $arrayProduk= array(
        "beratBarang" => $beratBarang
      );

      $this->content = $arrayProduk;
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
