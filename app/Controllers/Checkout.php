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
class Checkout extends Controller
{
    var $err = "";
    var $cek = "";
    var $content = array();
    public function index()
    {
      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}
      $getDataMember = sqlArray(sqlQuery("select * from member where email = '$email'"));

      $decodeProvinsi = json_decode($jsonProvinsiPengiriman);
      $decodeKota = json_decode($jsonKotaPengiriman);
      $decodeServicePengiriman = json_decode($jsonServicePengiriman);
      $arrayService = $decodeServicePengiriman[0]->costs;
      for ($i=0; $i < sizeof($arrayService) ; $i++) {
        if($arrayService[$i]->cost[0]->value == $servicePengiriman ){
          $servicePengiriman = $arrayService[$i]->description;
        }
      }
      $namaProvinsi = $decodeProvinsi[array_search($idProvinsi, array_column($decodeProvinsi, 'province_id'))]->province;
      $namaKota = $decodeKota[array_search($idKota, array_column($decodeKota, 'city_id'))]->type." ".$decodeKota[array_search($idKota, array_column($decodeKota, 'city_id'))]->city_name;
      $getJumlahTransaksiHariIni = sqlArray(sqlQuery("select count(id) from transaksi where tanggal = '".date("Y-m-d")."'"));
			$kodeUnik = $getJumlahTransaksiHariIni['count(id)'] + 1;
      $dataTransaksi = array(
        "id_member" => $getDataMember['id'],
        "nama_pembeli" => $namaPembeli,
        "email_pembeli" => $emailPembeli,
        "nomor_telepon" => $nomorTelepon,
        "keterangan" => $keterangan,
        "ongkir" => $ongkir,
        "sub_total" => $subTotal,
        "total" => $subTotal + $ongkir + $kodeUnik,
        "provinsi_pengiriman" => $decodeProvinsi[array_search($idProvinsi, array_column($decodeProvinsi, 'province_id'))]->province,
        "kota_pengiriman" => $decodeKota[array_search($idKota, array_column($decodeKota, 'city_id'))]->type." ".$decodeKota[array_search($idKota, array_column($decodeKota, 'city_id'))]->city_name,
        "alamat_pengiriman" => $alamatPengiriman,
        "kode_pos_pengiriman" => $kodePos,
        "kecamatan_pengiriman" => $kecamatanPembeli,
        "tanggal" => date("Y-m-d"),
        "status" => "BELUM BAYAR",
        "jenis_transaksi" => "PENJUALAN",
        "service_pengiriman" => $decodeServicePengiriman[0]->name." => ". $servicePengiriman,
        "id_provinsi" => $idProvinsi,
        "id_kota" => $idKota,
        "update_time" => date("Y-m-d H:i:s"),
        "kode_unik" => $this->genNumber($kodeUnik,3),
      );
      $queryInsertTransaksi = sqlInsert("transaksi",$dataTransaksi);
      sqlQuery($queryInsertTransaksi);
      $getIdTransaksi = sqlArray(sqlQuery("select max(id) from transaksi where tanggal = '".date("Y-m-d")."' and id_member = '".$getDataMember['id']."'"));
      $decodeCart = json_decode($cart);
      for ($i=0; $i < sizeof($decodeCart) ; $i++) {
        $dataDetailTransaksi= array(
          "id_transaksi" => $getIdTransaksi['max(id)'],
          "id_produk" => $decodeCart[$i]->id_produk,
          "jumlah" => $decodeCart[$i]->jumlah,
          "harga" => $decodeCart[$i]->harga,
          "total" => $decodeCart[$i]->sub_total,
        );
        $getDataProduk = sqlArray(sqlQuery("select * from produk where id = '".$decodeCart[$i]->id_produk."'"));
        $listDetailTransaksi .= "
        <tr class='text-right'>
           <td class='text-left'>".($i + 1)."</td>
           <td class='text-left'>".$getDataProduk['nama_produk']."</td>
           <td class='text-right'>".$decodeCart[$i]->harga."</td>
           <td>".$decodeCart[$i]->jumlah."</td>
           <td>".$this->numberFormat($decodeCart[$i]->sub_total)."</td>
         </tr>
        ";
        $queryInsertDetailTransaksi = sqlInsert("detail_transaksi",$dataDetailTransaksi);
        sqlQuery($queryInsertDetailTransaksi);
      }






      $subject = ' Pembayaran Pembelian Produk';
			$html_body =  "<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <title>Pearl UI</title>
    <link rel='stylesheet' href='<?= base_url();?>assets/ui-member/css/style.css'>
    <style type='text/css'>
      table, th, td {
        border: 1px solid black;
      }
    </style>
</head>

<body>
<p>&nbsp;</p>
<div class='container-scroller'>
    <div class='container-fluid page-body-wrapper'>
        <div class='main-panel' style='width: 100%;'>
            <div class='content-wrapper' style='padding: 1%;'>
                <div class='row'>
                    <div class='col-12 grid-margin'>
                        <div class='card'>
                            <h3 class='card-title ' style='text-align: center; background: #c3ccd6; color: white; padding: 1%;'><img style='width: 90px; float: left;' src='http://member.saudagarkaya.com/assets/ui-member/images/logo/logo.png' /> Pembayaran Pembelian Produk</h3>
                            <div class='card-body'>
                                <div class='container-fluid d-flex justify-content-between' style='width: 100%;'>
                                    <div class='col-lg-3 pl-0' style='float: left;'>
                                        <p class='mt-2 mb-2'><strong id='namaTransaksi'>".$namaPembeli."</strong></p>
                                        <p><span id='alamatTransaksi'>".$alamatPengiriman.",</span>
                                            <br /><span id='kecamatanTransaksi'>".$kecamatanPembeli." (".$kodePos."),</span>
                                            <br /><span id='kotaTransaksi'>".$namaKota.",</span>
                                            <br /><span id='provinsiTransaksi'>".$namaProvinsi.".</span></p>
                                    </div>
                                    <div class='col-lg-3 pr-0' style='float: right;'>
                                        <p class='mt-2 mb-2 text-right'><strong>#".$getIdTransaksi['max(id)']."</strong></p>
                                        <p class='mt-2 mb-2 text-right'><strong>Kontak Pembeli</strong></p>
                                        <p class='text-right'><span id='emailTransaksi'>".$emailPembeli."</span>
                                            <br /> <span id='noTelpnTransaksi'>".$nomorTelepon."</span></p>
                                    </div>
                                </div>
                                <div class='container-fluid d-flex justify-content-between' style='width: 100%; float: left;'>
                                    <div class='col-lg-3 pl-0' style='float: left;'>
                                        <p class='mb-0 mt-2'>Tanggal Transaksi : <span id='TanggalTransaksi'>".date("d-m-Y")."</span></p>
                                        <p>Layanan Pengiriman : <span id='pengirimanTransaksi'>".$decodeServicePengiriman[0]->name." => ". $servicePengiriman."</span></p>
                                    </div>
                                    <div style='width: 100%; float: left;'>
                                        <table class='table' style='width: 100%; float: left;'>
                                            <thead>
                                                <tr class='bg-dark text-white'>
                                                    <th>#</th>
                                                    <th>Produk</th>
                                                    <th class='text-right'>Harga</th>
                                                    <th class='text-right'>Jumlah</th>
                                                    <th class='text-right'>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id='DetailTransaksi'>
																						$listDetailTransaksi
																						</tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class='container-fluid mt-5 w-100' style='width: 100%; text-align: right; float: right;'>
                                    <p class='text-right mb-2'>Sub - Total : <span id='totalTransaksi'>".$this->numberFormat($subTotal)."</span></p>
                                    <p class='text-right mb-2'>Kode Unik : <span id='diskonTransaksi'>".$this->genNumber($kodeUnik,3)."</span></p>
                                    <p class='text-right'>Ongkir : <span id='ongkirTransaksi'>".$this->numberFormat($ongkir)."</span></p>
                                    <h4 class='text-right mb-5'>Grand Total : <span id='grandTotalTransaksi'>".number_format($subTotal + $ongkir + $kodeUnik)."</span></h4>
                                    <hr />
                                    <div style='text-align: center;'>
                                        <div class='bank-box'>
                                            <h5>Silahkan Transfer Ke Salah Satu Rekening Dibawah ini, sejumlah ".number_format($subTotal + $ongkir + $kodeUnik)."</h5>
                                            <div class='p-2 icon-bank'><img style='width: 150px;' src='https://img2.pngdownload.id/20180802/lcs/kisspng-bank-central-asia-logo-bca-finance-business-logo-bank-central-asia-bca-format-cdr-amp-pn-5b63687e470088.3520223915332414702908.jpg' /></div>
                                            <h4><strong>437.128.5843</strong></h4>
                                            <h5>Atas Nama Andy Sudaryanto</h5>
                                        </div>
                                        <div class='option-divider-bordered'>
                                            <div class='row justify-content-center overlap-row'>
                                                <div class='pills-heading'><strong>ATAU</strong></div>
                                            </div>
                                        </div>
                                        <div class='bank-box'>
                                            <div class='p-2 icon-bank'><img style='width: 150px;' src='https://upload.wikimedia.org/wikipedia/id/thumb/f/fa/Bank_Mandiri_logo.svg/1280px-Bank_Mandiri_logo.svg.png' /></div>
                                            <h4><strong>131.001.363.9408</strong></h4>
                                            <h5>Atas Nama Andy Sudaryanto</h5>
                                        </div>
                                        <div class='option-divider-bordered'>
                                            <div class='row justify-content-center overlap-row'>
                                                <div class='pills-heading'><strong>ATAU</strong></div>
                                            </div>
                                        </div>
                                        <div class='bank-box'>
                                            <div class='p-2 icon-bank'><img style='width: 150px;' src='https://upload.wikimedia.org/wikipedia/commons/9/97/Logo_BRI.png' /></div>
                                            <h4><strong>763.701.002.274.508</strong></h4>
                                            <h5>Atas Nama Andy Sudaryanto</h5>
                                        </div>
																				<h5 >Setelah transfer klik tombol konfirmasi </h5>
																				<center>

																				<a href='https://api.whatsapp.com/send?phone=62".substr($getDataMember['nomor_telepon'],1)."&text=halo+saya+ingin+konfirmasi+nomor+order+%23".$getIdTransaksi['max(id)']."+' style='background-color:rgb(0, 128, 0);border-bottom-color:rgb(255, 255, 255);border-bottom-left-radius:5px;border-bottom-right-radius:5px;border-bottom-style:none;border-bottom-width:0px;border-image-outset:0px;border-image-repeat:stretch;border-image-slice:100%;border-image-source:none;border-image-width:1;border-left-color:rgb(255, 255, 255);border-left-style:none;border-left-width:0px;border-right-color:rgb(255, 255, 255);border-right-style:none;border-right-width:0px;border-top-color:rgb(255, 255, 255);border-top-left-radius:5px;border-top-right-radius:5px;border-top-style:none;border-top-width:0px;box-sizing:border-box;color:rgb(255, 255, 255);display:block;font-family:Roboto, sans-serif;font-size:20px;font-stretch:100%;font-style:normal;font-variant-caps:normal;font-variant-east-asian:normal;font-variant-ligatures:normal;font-variant-numeric:normal;font-weight:400;height:63px;hyphens:manual;line-height:28px;margin-bottom:0px;margin-left:0px;margin-right:0px;margin-top:0px;outline-color:rgb(255, 255, 255);outline-style:none;outline-width:0px;padding-bottom:15px;padding-left:10px;padding-right:10px;padding-top:15px;text-size-adjust:100%;transition-delay:0s, 0s, 0s, 0s, 0s, 0s;transition-duration:0.3s, 0.3s, 0.3s, 0.3s, 0.3s, 0.3s;transition-property:background, border, border-radius, box-shadow, -webkit-border-radius, -webkit-box-shadow;transition-timing-function:ease, ease, ease, ease, ease, ease;vertical-align:baseline;visibility:visible;width:387.328px;-webkit-font-smoothing:antialiased;text-decoration: none;' >KONFIRMASI</a>
																				</center>
                                        <h5 style='color: blue;'>*MOHON DIPERHATIKAN : Jika Anda melakukan transfer dari rekening bank selain 3 bank di atas, kami sarankan Anda transfernya ke akun Bank BCA kami, untuk proses verifikasi yang lebih cepat. Terima kasih.</h5>
                                        <br />
                                        <p class='card-text'>Transaksi ini bersifat <strong>non refundable / tidak bisa dikembalikan</strong> dan Setelah Anda melakukan transaksi ini maka Anda telah setuju dengan semua ketentuan yang berlaku.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</html>";
			$data = array('email_penerima' => $emailPembeli , 'subjek_email' => $subject, "body_email" => $html_body);
			$curl = curl_init('https://admin.saudagarkaya.com/sendEmail.php') ;
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_exec($curl);


			$data = array('email_penerima' => "admin@saudagarkaya.com" , 'subjek_email' => "Pesanan Baru", "body_email" => "#Nomor Pesanan ".$getIdTransaksi['max(id)']. " senilai ". $this->numberFormat($subTotal + $ongkir + $kodeUnik));
			$curl = curl_init('https://admin.saudagarkaya.com/sendEmail.php') ;
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_exec($curl);
      $content = array(
        "idTransaksi" => $getIdTransaksi['max(id)'],
      );




      $this->content = $content;
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
    function genNumber($num, $dig=3){
  		return sprintf("%0".$dig."d", $num);
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
    public function getCheckout()
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
        "jsonCheckout" => '[{"code":"jne","name":"Jalur Nugraha Ekakurir (JNE)","costs":[{"service":"OKE","description":"Ongkos Kirim Ekonomis","cost":[{"value":16000,"etd":"3-6","note":""}]},{"service":"REG","description":"Layanan Reguler","cost":[{"value":20000,"etd":"2-3","note":""}]}]}]',
      );
			$curl = curl_init('https://saudagarkaya.com/api/getCheckout') ;
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

    function invoiceList(){
      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}
      $arrayListInvoice = array();
      $getDataMember = sqlArray(sqlQuery("select * from member where email = '$email'"));

      $limitPost = "limit $from,$to";
      // where id_member = '".$getDataMember['id']."'
      $getJumlahItem = sqlArray(sqlQuery("select count(id) from transaksi where id_member = '".$getDataMember['id']."'"));
      $getDataInvoice = sqlQuery("select * from transaksi  where id_member = '".$getDataMember['id']."' order by id desc  $limitPost");
      while ($dataInvoice = sqlArray($getDataInvoice)) {
        $arrayListInvoice[] = array(
          "id" => $dataInvoice['id'],
          "tanggal" => $this->generateDate($dataInvoice['tanggal']),
          "deskipsi" => "Product purchase",
          "total" => $dataInvoice['total'],
          "nama_pembeli" => $dataInvoice['nama_pembeli'],
          "status" => $dataInvoice['status'],
          "maxItem" => $getJumlahItem['count(id)']
        );
      }
      $this->content = $arrayListInvoice;
      $this->cek = "select * from transaksi where id_member = '".$getDataMember['id']."' order by id desc  $limitPost";
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
