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
use App\Models\User;

class Auth extends Controller
{

    var $err = "";
    var $cek = "";
    var $content = "";
    public function index()
    {

      foreach ($_REQUEST as $key => $value) {
				  $$key = $value;
			}


      if(sqlRowCount(sqlQuery("select * from users where email = '$email' and password = '".md5($password)."'")) != 0){
        $getDataUser = sqlArray(sqlQuery("select * from member where email='$email'"));
        // if(!empty($getDataUser['device_code'])){
        //   if($getDataUser['device_code'] != $deviceCode){
        //     $this->err = "Login Gagal, Jangan Ganti HP lah !";
        //   }
        // }else{
        //   if(sqlRowCount(sqlQuery("select * from member where device_code = '$deviceCode'")) != 0){
        //     $this->err = "Login Gagal One Phone One Account Bro !";
        //   }else{
        //     sqlQuery("update member set device_code = '$deviceCode' where id = '".$getDataUser['id']."'");
        //   }
        // }
      }else{
        $this->err = "Login Gagal";
      }
      $this->content = array(
        "id" => $getDataUser['id'],
        "email" => $getDataUser['email'],
        "nama" => $getDataUser['nama'],
        "nomor_telepon" => $getDataUser['nomor_telepon'],
        "saldo" => $getDataUser['saldo'],
        "jumlah_barang" => $getDataUser['jumlah_barang'],
        "nama_bank" => $getDataUser['nama_bank'],
        "nomor_rekening" => $getDataUser['nomor_rekening'],
        "nama_rekening" => $getDataUser['nama_rekening'],
        "profit" => $getDataUser['komisi'],
        "lisensi" => $getDataUser['lisensi'],
        "status" => $getDataUser['status'],
      );
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
}
