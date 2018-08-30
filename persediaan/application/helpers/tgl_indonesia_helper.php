<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



if ( ! function_exists('tgl'))
{
	function tgl($tgl)
	{
		$date = new DateTime($tgl);
		return $date->format('d');
	}
}

if ( ! function_exists('bln'))
{
	function bln($tgl)
	{
		$date = new DateTime($tgl);
		return $date->format('m');
	}
}

if ( ! function_exists('thn'))
{
	function thn($tgl)
	{
		$date = new DateTime($tgl);
		return $date->format('Y');
	}
}

if ( ! function_exists('tgl_indo'))
{
	function tgl_indo($tgl)
	{
		$date = new DateTime($tgl);
		return $date->format('d-m-Y');
	}
}

if ( ! function_exists('tgl_dmy'))
{
	function tgl_dmy($tgl)
	{
		if($tgl == null){
			return "";
		}else{
			$date = new DateTime($tgl);
			return $date->format('d-m-Y');
		}
	}
}

if ( ! function_exists('tgl_dmy2'))
{
	function tgl_dmy2($tgl)
	{
		$pecah 		= explode("-",$tgl);
		$tanggal 	= $pecah[2];
		$bulan 		= $pecah[1];
		$tahun 		= $pecah[0];
		return $bulan.'/'.$tanggal.'/'.$tahun;
	}
}

if ( ! function_exists('tgl_ymd'))
{
	function tgl_ymd($tgl)
	{
		if($tgl == null){
			return "";
		}else{
			$date = new DateTime($tgl);
			return $date->format('Y-m-d');
		}
	}
}


if ( ! function_exists('tahun'))
{
	function tahun($tgl)
	{
		return date('Y', strtotime($tgl));
	}
}

if ( ! function_exists('bulan'))
{
	function bulan($bln)
	{
		switch ($bln)
		{
			case 1:
				return "Januari";
				break;
			case 2:
				return "Februari";
				break;
			case 3:
				return "Maret";
				break;
			case 4:
				return "April";
				break;
			case 5:
				return "Mei";
				break;
			case 6:
				return "Juni";
				break;
			case 7:
				return "Juli";
				break;
			case 8:
				return "Agustus";
				break;
			case 9:
				return "September";
				break;
			case 10:
				return "Oktober";
				break;
			case 11:
				return "November";
				break;
			case 12:
				return "Desember";
				break;
		}
	}
}

if ( ! function_exists('nama_hari'))
{
	function nama_hari($tanggal)
	{
		$ubah = gmdate($tanggal, time()+60*60*8);
		$pecah = explode("-",$ubah);
		$tgl = $pecah[2];
		$bln = $pecah[1];
		$thn = $pecah[0];

		$nama = date("l", mktime(0,0,0,$bln,$tgl,$thn));
		$nama_hari = "";
		if($nama=="Sunday") {$nama_hari="Minggu";}
		else if($nama=="Monday") {$nama_hari="Senin";}
		else if($nama=="Tuesday") {$nama_hari="Selasa";}
		else if($nama=="Wednesday") {$nama_hari="Rabu";}
		else if($nama=="Thursday") {$nama_hari="Kamis";}
		else if($nama=="Friday") {$nama_hari="Jumat";}
		else if($nama=="Saturday") {$nama_hari="Sabtu";}
		return $nama_hari;
	}
}

if ( ! function_exists('hitung_mundur'))
{
	function hitung_mundur($wkt)
	{
		$waktu=array(	365*24*60*60	=> "tahun",
						30*24*60*60		=> "bulan",
						7*24*60*60		=> "minggu",
						24*60*60		=> "hari",
						60*60			=> "jam",
						60				=> "menit",
						1				=> "detik");

		$hitung = strtotime(gmdate ("Y-m-d H:i:s", time () +60 * 60 * 8))-$wkt;
		$hasil = array();
		if($hitung<5)
		{
			$hasil = 'kurang dari 5 detik yang lalu';
		}
		else
		{
			$stop = 0;
			foreach($waktu as $periode => $satuan)
			{
				if($stop>=6 || ($stop>0 && $periode<60)) break;
				$bagi = floor($hitung/$periode);
				if($bagi > 0)
				{
					$hasil[] = $bagi.' '.$satuan;
					$hitung -= $bagi*$periode;
					$stop++;
				}
				else if($stop>0) $stop++;
			}
			$hasil=implode(' ',$hasil).' yang lalu';
		}
		return $hasil;
	}
}


function combo_date($name, $mulai,$sampai)
{
	$hasil = "<select class='input-mini' name='$name'>";
	for($i=$mulai;$i<=$sampai;$i++) 
	{     
	    $hasil.= "<option value='$i'>$i</option>";   
	}    

    $hasil.="</select>";  
    return $hasil;   
}

function combonamabln($var,$mulai,$sampai,$terpilih=''){
  $nama_bln=array(1=> "Januari", "Februari", "Maret", "April", "Mei", 
                      "Juni", "Juli", "Agustus", "September", 
                      "Oktober", "November", "Desember");
  echo "<select name=$var>";
  for ($bln=$mulai; $bln<=$sampai; $bln++){
      if ($bln==$terpilih)
         echo "<option value=$bln selected>$nama_bln[$bln]</option>";
      else
        echo "<option value=$bln>$nama_bln[$bln]</option>";
  }
  echo "</select> ";
}

