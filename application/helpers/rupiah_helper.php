<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function nilai($value) {
    if (is_double($value)){
    	$hasil = round($value, 2);
    }else{
    	$hasil = round($value);
    }

    return $hasil;
}

if ( ! function_exists('rupiah'))
{

	function rupiah($n)
	{
		$rp = number_format($n,2,',','.');
		return $rp;
	}
}

if ( ! function_exists('rp'))
{
	function rp($n)
	{
		$rp = number_format(floatval($n),0,',','.');
		return $rp;
	}
}

if ( ! function_exists('to_value'))
{

	function to_value($n)
	{
		$rp = number_format($n,0,",",".");
		return $rp;
	}
}

if ( ! function_exists('idr'))
{

	function idr($n)
	{
		$rp = "Rp ".number_format($n,2,',','.');
		return $rp;
	}
}

if ( ! function_exists('ribu'))
{

	function ribu($n)
	{
		$n2 = $n*1;
		$rp = number_format($n2,2,',','.');
		return $rp;
	}
}

if ( ! function_exists('rupiah_format')) { 

function rupiah_format($rp) { 
	$rupiah = "";
	$pjg 	= strlen($rp);
	while ($pjg --> 3) {
		$rupiah = "." . substr($rp, -3) . $rupiah;
		$lbr = strlen($rp) - 3;
		$rp = substr($rp, 0, $lbr);
		$pjg = strlen($rp);
	}
	 
	$rupiah = $rp . $rupiah;
	return $rupiah;
 
}
 
}


if ( ! function_exists('cari'))
{

	function cari($n)
	{
		$x ="";
		if ($n == "Nm_Aset5"){
			$x = "nama aset";
		}elseif ($n == "Alamat"){
			$x = "alamat";	
		}elseif ($n == "Sertifikat_Nomor"){
			$x = "nomor sertifikat";	
		}elseif ($n == "Harga"){
			$x = "harga";	
		}elseif ($n == "Keterangan"){
			$x = "keterangan";	
		}
		return $x;
	}
}
?>