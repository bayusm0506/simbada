<?php
defined('BASEPATH') or exit('No direct script access');

function range_year($start,$end)
{ 
  return array_combine(array_merge(array(''=>''),range($start,$end)), array_merge(array(''=>'- pilih -'),range($start,$end)));
}

function inc_app($dir,$file)
{
  include(APPPATH.'views/'.$dir.'/'.$file.'.php');
}

function get_header()
{ 
  inc_app('themes','header');
}

function get_footer()
{ 
  inc_app('themes','footer');
}


function encrypt_url($string) {
   return base64_encode($string);
}

function decrypt_url($string) {
   return base64_decode($string);
}

function terbilang($x){
    $arr = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    if ($x < 12)
    return " " . $arr[$x];
    elseif ($x < 20)
    return terbilang($x - 10) . " belas";
    elseif ($x < 100)
    return terbilang($x / 10) . " puluh" . terbilang($x % 10);
    elseif ($x < 200)
    return " seratus" . terbilang($x - 100);
    elseif ($x < 1000)
    return terbilang($x / 100) . " ratus" . terbilang($x % 100);
    elseif ($x < 2000)
    return " seribu" . terbilang($x - 1000);
    elseif ($x < 1000000)
    return terbilang($x / 1000) . " ribu" . terbilang($x % 1000);
    elseif ($x < 1000000000)
    return terbilang($x / 1000000) . " juta" . terbilang($x % 1000000);
}

/* end of file application/helpers/MY_date_helper.php */