<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_prokeg extends CI_Model{
	
	function program($bidang,$unit,$sub,$upb){

		$kb    = $this->session->userdata('kd_bidang');
		$ku    = $this->session->userdata('kd_unit');
		$ks    = $this->session->userdata('kd_sub_unit');
		$kupb  = $this->session->userdata('kd_upb');
		$thn   = $this->session->userdata('tahun_anggaran');
		$where = "";

		if ($this->session->userdata('lvl') == 01){
				$where .= " AND (a.Kd_Bidang = $bidang)";
				$where .= " AND (a.Kd_Unit = $unit)";
				$where .= " AND (a.Kd_Sub = $sub)";
		}else{
			$where .= " AND (a.Kd_Bidang = $kb)";
			$where .= " AND (a.Kd_Unit = $ku)";
			$where .= " AND (a.Kd_Sub = $ks)";
			$where .= " AND (a.Kd_UPB = $kupb)";
		}
		$where .= " AND Tahun=$thn";

		$query = "SELECT * FROM Ta_Program a WHERE 1=1 $where";

		// print_r($query); exit();

		return $this->db->query($query);		
	}


	function kegiatan($bidang,$unit,$sub,$program){

		$kb    = $this->session->userdata('kd_bidang');
		$ku    = $this->session->userdata('kd_unit');
		$ks    = $this->session->userdata('kd_sub_unit');
		$kupb  = $this->session->userdata('kd_upb');
		$thn   = $this->session->userdata('tahun_anggaran');
		$where = "";

		if ($this->session->userdata('lvl') == 01){
				$where .= " AND (a.Kd_Bidang = $bidang)";
				$where .= " AND (a.Kd_Unit = $unit)";
				$where .= " AND (a.Kd_Sub = $sub)";
		}else{
			$where .= " AND (a.Kd_Bidang = $kb)";
			$where .= " AND (a.Kd_Unit = $ku)";
			$where .= " AND (a.Kd_Sub = $ks)";
			$where .= " AND (a.Kd_UPB = $kupb)";
		}
		$where .= " AND Tahun=$thn";
		$where .= " AND Kd_Prog=$program";

		$query = "SELECT * FROM Ta_Kegiatan a WHERE 1=1 $where";

		// print_r($query); exit();

		return $this->db->query($query);		
	}
	
}