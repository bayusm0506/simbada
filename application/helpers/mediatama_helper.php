<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function kib($kd='')
{
   		if($kd==1){
			$out = "KIB A. Tanah";
		}elseif($kd==2){
			$out ="KIB B. Peralatan dan Mesin";
		}elseif($kd==3){
			$out ="KIB C. Gedung dan Bangunan";
		}elseif($kd==4){
			$out ="KIB D. Jalan, Irigasi dan Jaringan";
		}elseif($kd==5){
			$out ="KIB E. Aset Tetap Lainya";
		}elseif($kd==6){
			$out ="KIB F. Konstruksi Dalam Pengerjaan";
		}else{
			$out ="ASET LAIN";
		}
	return $out;
}

function jenis_dokumen($kd='')
{
   		if($kd==1){
			$out = "Berita Acara Serah Terima";
		}elseif($kd==2){
			$out ="Berita Acara Pinjam Pakai";
		}elseif($kd==3){
			$out ="Surat Perjanjian Pinjam Pakai";
		}else{
			$out ="Lainya";
		}
	return $out;
}

function kode($kd='')
{
   		if($kd==1){
			$out = "BAST";
		}elseif($kd==2){
			$out ="BAPP";
		}elseif($kd==3){
			$out ="SPPP";
		}else{
			$out ="LAIN";
		}
	return $out;
}


function rupiah($n){
		$rp = "Rp ".number_format($n,2,',','.');
		return $rp;
	}

function clean($string) {
    //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
    $string = strtolower($string);
    //Strip any unwanted characters
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", " ", $string);
    return $string;
}

function seo_format($string) {
    //Unwanted:  {UPPERCASE} ; / ? : @ & = + $ , . ! ~ * ' ( )
    $string = strtolower($string);
    //Strip any unwanted characters
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}

function getLabel($string){
	$array = explode("_", $string);
	$arrayUpperCase = array_map("ucwords", $array);
	$string = implode(" ", $arrayUpperCase);
	$string = str_replace(" Id", "", $string);
	return $string;
}

function getClassName($string){
	$array = explode("_", $string);
	$arrayUpperCase = array_map("ucwords", $array);
	$string = implode("", $arrayUpperCase);
	return $string;
}

function getStrippedClass($camelCaseClass){
	preg_match_all('/((?:^|[A-Z])[a-z]+)/',$camelCaseClass ,$matches);
	$strippedClass = changeClassName($matches[0]);
	return $strippedClass;
}

function getUnderscoredClass($camelCaseClass){
	preg_match_all('/((?:^|[A-Z])[a-z]+)/',$camelCaseClass ,$matches);
	$strippedClass = changeClassName($matches[0], "_");
	return $strippedClass;
}

function changeClassName($arrClassName = null, $str = "-"){
	if($arrClassName){
		$newClass = "";
		foreach ($arrClassName as $i => $value) {
			if($i==0){
				$newClass .= strtolower($value);
			}else{
				if(strtolower($value) == "controller")
					break;
				$newClass .= $str.strtolower($value);
			}
		}
		return $newClass;
	}
}

function createFile($folder, $filename, $content){
	$filepath = $folder.$filename;
	if(is_writable($folder)){
		if(!$file = fopen($filepath,'w')){
			return array('status' => 0, 'message'=> 'Failed to create the file '.$filepath);
		}

		if(!fwrite($file, $content)){
			return array('status' => 0, 'message'=> 'Failed to write content on the file '.$filepath);
		}

		fclose($file);
		return TRUE;
	}
	return array('status' => 0, 'message'=> $folder.' is not writable.');
}

function debug($var){
	echo "<pre>";
		print_r($var);
	echo "</pre>";
}

function getRandomString($length = 5) {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    
    return implode($pass); //turn the array into a string
}

if(false === function_exists('lcfirst'))
{
    function lcfirst( $str ) {
        $str[0] = strtolower($str[0]);
        return (string)$str;
    }
}

function deleteFolder($path)
{
    if (is_dir($path) === true)
    {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file)
        {
            deleteFolder(realpath($path) . '/' . $file);
        }

        return rmdir($path);
    }

    else if (is_file($path) === true)
    {
        return unlink($path);
    }

    return false;
}

function getMenuBerita(){
			$_this = & get_Instance();
			$hasil = "";
			$w     = $_this->db->query("SELECT * from kategori where id_subkategori='0'");
			$count = $w->num_rows();
			$hasil .= '<li class="menuparent"><span><a href="'.base_url().'berita">Berita</a></span>
	                   <ul>';

			foreach($w->result() as $h)
			{
						$wa= $_this->db->query("SELECT * from kategori where id_subkategori='".$h->id_kategori."' ORDER BY nama_kategori");
					if ($wa->num_rows() > 0){
						$hasil .= '<li class="menuparent"><span class="menuparent nolink">'.$h->nama_kategori.'</span>';
						$hasil .= "<ul>";
						foreach($wa->result() as $ha)
						{
						$hasil .= "<li><a href=".base_url().$ha->url."> ".$ha->nama_kategori."</a></li>";
						}
						$hasil .= "</ul></li>";
					}else{
						$hasil .= "<li><a href=".base_url().$h->url.">".$h->nama_kategori."</a></li>";
					}
			}

			$hasil .= "</ul></li>";

       		return $hasil;
		}



function getDistanceBetween($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'Mi') 
{ 
	$theta = $longitude1 - $longitude2; 
	$distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2)))  + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta))); 
	$distance = acos($distance); 
	$distance = rad2deg($distance); 
	$distance = $distance * 60 * 1.1515; 
	switch($unit) 
	{ 
		case 'Mi': break; 
		case 'Km' : $distance = $distance * 1.609344; 
	} 
	//return (round($distance,2));
	if (isset($latitude2) || !isset($longitude2)) {
	 	$hasil = formatLength($distance);
	 } else {
	 	$hasil = "jarak tidak diketahui";
	 }
	return $hasil;
}

function formatLength($meters) {
	$km     = floor($meters / 1000);
	$km2    = $meters / 1000;
	$meters = $km2 * 1000;
	
	$ret    = "";
	if ($km > 0){
		$ret    .= $km." km ";
	}

	if ($km < 10){
		$ret    .= round($meters,2)." meter";
	}

	return $ret;
}

function meter($satuan) {

	
	$ret    = "";
	if ($satuan === "9999999999"){
		$ret    = "jarak tidak diketahui";
	}else{
		$meters = floor($satuan * 1000);
		$km     = floor($satuan);
		$rkm 	= $meters % 1000;

		if ($km > 0){
		$ret    .= $km." km ";
		}
		
		if ($rkm > 0){
		$ret    .= round($rkm,2)." meter";
		}
	}

	return $ret;
}

function rand_string( $length ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";    
 
    $size = strlen( $chars );
    for( $i = 0; $i < $length; $i++ ) {
        $str .= $chars[ rand( 0, $size - 1 ) ];
    }
 
    return $str;
}

function kategori(){
			$_this = & get_Instance();
			$hasil = "";
			$w     = $_this->db->query("SELECT * from kategori ORDER BY id_kategori");
			$count = $w->num_rows();
			foreach($w->result() as $h)
			{
					$hasil .= "<li><span class='icon-globe'></span><a href='".base_url().$h->url."'>".$h->nama_kategori."</a></li>";
			}

       		return $hasil;
		}

function kecamatan($kab,$kec){
	if(isset($kab) AND isset($kec)){
		$_this = & get_Instance();
		$query = $_this->db->query("SELECT * FROM ref_kecamatan WHERE lokasi_propinsi = 12 AND lokasi_kabupatenkota = {$kab} AND lokasi_kecamatan = {$kec}");
		if($query->num_rows() > 0){
			return $query->row()->nama_kecamatan;
		}else{
			return "tidak ada";
		}
	}else{
		return "tidak dikenal";
	}
}

function kelurahan($kab,$kec,$kel){
	if(isset($kab) AND isset($kec) AND isset($kel)){
		$_this = & get_Instance();
		$query = $_this->db->query("SELECT * FROM ref_kelurahan WHERE lokasi_propinsi = 12 AND lokasi_kabupatenkota = {$kab} AND lokasi_kecamatan = {$kec} AND lokasi_kelurahan = {$kel}");
		if($query->num_rows() > 0){
			return $query->row()->nama_kelurahan;
		}else{
			return "tidak ada";
		}
	}else{
		return "tidak dikenal";
	}
}

function nama_sekolah($jenjang,$id){
	if(isset($id)){
		$_this = & get_Instance();
		$query = $_this->db->query("SELECT * FROM tb_data_{$jenjang} WHERE id_sekolah = {$id}")->row();
		return $query->nm_sekolah;
	}else{
		return "tidak dikenal";
	}
}

function getKabKota($output){
		$_this = & get_Instance();
		$sql = "SELECT * FROM Ref_Kab_Kota WHERE 1=1";
        $sql .=" AND Kd_Prov = 2";
        $sql .=" AND Kd_Kab_Kota = 0";
		$query = $_this->db->query($sql);
		if($query->num_rows() > 0){							
			return $query->row()->$output;
		}else {
			return null;
		}
}

function getInfoPemda($output){
		$_this = & get_Instance();
		$sql = "SELECT * FROM Ref_Pemda WHERE 1=1";
        $sql .=" AND Kd_Prov = 2";
        $sql .=" AND Kd_Kab_Kota = 0";
		$query = $_this->db->query($sql);
		if($query->num_rows() > 0){							
			return $query->row()->$output;
		}else {
			return null;
		}
}

function getInfoKD($output){
		$_this = & get_Instance();
		$sql = "SELECT * FROM Ta_Pemda WHERE 1=1";
        $sql .=" AND Tahun = ".$this->session->userdata('tahun');
		$query = $_this->db->query($sql);
		if($query->num_rows() > 0){							
			return $query->row()->$output;
		}else {
			return null;
		}
}

function getInfoUPB($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb){
		$_this = & get_Instance();
		$query = $_this->db->get_where('Ref_UPB', array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
			'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb), 1)->row();

		return $query->Nm_UPB;
}

function RelativeTime( $timestamp ){
    if( !is_numeric( $timestamp ) ){
        $timestamp = strtotime( $timestamp );
        if( !is_numeric( $timestamp ) ){
            return "";
        }
    }

    $difference = time() - $timestamp;
        // Customize in your own language.
    $periods = array( "detik", "menit", "jam", "hari", "minggu", "bulan", "tahun", "dekade" );
    $lengths = array( "60","60","24","7","4.35","12","10");

    if ($difference > 0) { // this was in the past
        $ending = "yang lalu";
    }else { // this was in the future
        $difference = -$difference;
        $ending = "baru saja";
    }
    for( $j=0; $difference>=$lengths[$j] and $j < 7; $j++ )
        $difference /= $lengths[$j];
    $difference = round($difference);
    if( $difference != 1 ){
                // Also change this if needed for an other language
        $periods[$j].= "";
    }
    $text = "$difference $periods[$j] $ending";
    return $text;
}

function id_attr($val)
{
	$_this = & get_Instance();
	$tahun = $_this->session->userdata('tahun');
	$hasil = "";
	$d     = $_this->db->query("SELECT * from Ta_Pemda WHERE Tahun='$tahun' LIMIT 1")->row();

    switch ($val) {

    	case 'Nm_PimpDaerah':
    		$hasil = $d->Nm_PimpDaerah;
    		break;

    	case 'Jab_PimpDaerah':
    		$hasil = $d->Jab_PimpDaerah;
    		break;

    	case 'Nm_Sekda':
    		$hasil = $d->Nm_Sekda;
    		break;

    	case 'Nip_Sekda':
    		$hasil = $d->Nip_Sekda;
    		break;

    	case 'Jbt_Sekda':
    		$hasil = $d->Jbt_Sekda;
    		break;

    	case 'Nm_Ka_Umum':
    		$hasil = $d->Nm_Ka_Umum;
    		break;

    	case 'Nip_Ka_Umum':
    		$hasil = $d->Nip_Ka_Umum;
    		break;

    	case 'Jbt_Ka_Umum':
    		$hasil = $d->Jbt_Ka_Umum;
    		break;

    	case 'Nip_Ka_Umum':
    		$hasil = $d->Nip_Ka_Umum;
    		break;

    	case 'Nip_Ka_Keu':
    		$hasil = $d->Nip_Ka_Keu;
    		break;

    	case 'Jbt_Ka_Keu':
    		$hasil = $d->Jbt_Ka_Keu;
    		break;
    	
    	default:
    		$hasil = "-";
    		break;
    }

	return $hasil;
}


/*function getInfoKIB_B($output){
		$_this = & get_Instance();
		$sql = "SELECT * FROM Ta_KIB_B WHERE 1=1";
        $sql .=" AND Tahun = ".$this->session->userdata('tahun');
		$query = $_this->db->query($sql);
		if($query->num_rows() > 0){							
			return $query->row()->$output;
		}else {
			return null;
		}
}*/

?>