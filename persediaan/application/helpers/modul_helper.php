<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function d2($val){
	return sprintf ("%02u", $val);
}
function pencarian_persediaan_barang_masuk()
{
   		$out = array(''=>'- pilih pencarian -',
					 'Nm_Rekanan'=>'Dari/Nama Rekanan',
					 'No_Kontrak'=>'No Kontrak',
					 'No_BA_Pemeriksaan'=>'No BA Penerimaan',
					 'Keterangan'=>'Keterangan',
					 'all'=>'Semua Data');
	return $out;
}

function pencarian_persediaan_barang_keluar()
{
   		$out = array(''=>'- pilih pencarian -',
					 'Nm_Aset6'=>'Nama Aset',
					 'Harga'=>'Harga Satuan',
					 'Kepada'=>'Kepada',
					 'Keterangan'=>'Keterangan',
					 'all'=>'Semua Data');
	return $out;
}


?>