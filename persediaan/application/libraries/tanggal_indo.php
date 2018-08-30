<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class tanggal_indo{
		function tgl_indo($t){
						$hari_array = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
						$hr = date('w', strtotime($t));
						$hari = $hari_array[$hr];
						$tgl = date('d-m-Y', strtotime($t));
						$hr_tgl = "$hari, $tgl";
						return $hr_tgl;
		}
		
		function thn_indo($t){
						$tgl = date('Y', strtotime($t));
						return $tgl;
		}
}