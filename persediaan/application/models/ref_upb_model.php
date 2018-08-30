<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_upb_model extends CI_Model{
	
	var $table = 'Ref_UPB';
	
	function get_upb($bidang='',$unit='',$sub='',$upb='')
	{	

		$kb    = $this->session->userdata('kd_bidang');
		$ku    = $this->session->userdata('kd_unit');
		$ks    = $this->session->userdata('kd_sub_unit');
		$kupb  = $this->session->userdata('kd_upb');
		$where = "";

		if ($this->session->userdata('lvl') == 01){

			if($bidang){
				$where .= " AND (a.Kd_Bidang = $bidang)";
			}
			if($unit){
				$where .= " AND (a.Kd_Unit = $unit)";
			}
			if($sub){
				$where .= " AND (a.Kd_Sub = $sub)";
			}
			if($upb){
				$where .= " AND (a.Kd_UPB = $upb)";
			}
		}elseif ($this->session->userdata('lvl') == 02){

			$where .= " AND (a.Kd_Bidang = $kb)";
			$where .= " AND (a.Kd_Unit = $ku)";
			$where .= " AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= " AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where .= " AND (a.Kd_Bidang = $kb)";
			$where .= " AND (a.Kd_Unit = $ku)";
			$where .= " AND (a.Kd_Sub = $ks)";
			$where .= " AND (a.Kd_UPB = $kupb)";
		}

		$query = "SELECT * FROM Ref_UPB a WHERE 1=1 $where";

		// print_r($query); exit();

		return $this->db->query($query);		
	}

	function all_upb(){
		
		$this->db->select('*');
		if ($this->session->userdata('lvl') == 01){
			
		}elseif ($this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));	
		}else{
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));	
		}

        $this->db->from($this->table);
		return $this->db->get();
	}

	function upb($kd_bidang,$kd_unit,$kd_sub,$like){
		
		$like != '' ? $this->db->like('Nm_UPB',$like) : '';
		$this->db->select('*');
		if ($this->session->userdata('lvl') == 01){
			$this->db->where('Kd_Bidang',$kd_bidang);
			$this->db->where('Kd_Unit',$kd_unit);
			$this->db->where('Kd_Sub',$kd_sub);	
		}elseif ($this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));	
		}else{
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));	
		}
        $this->db->from($this->table);
		return $this->db->get();
	}
	
	function get_nama_upb($bidang='',$unit='',$sub='',$upb='')
	{
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$where = "";
		if ($this->session->userdata('lvl') == 01){
			if($bidang){
				$where = " AND (ref_upb.kd_bidang = $bidang)";
			}
			if($unit){
				$where .= " AND (ref_upb.kd_unit = $unit)";
			}
			if($sub){
				$where .= " AND (ref_upb.kd_sub = $sub)";
			}
			if($upb){
				$where .= " AND (ref_upb.kd_upb = $upb)";
			}
		}elseif ($this->session->userdata('lvl') == 02){
			if($bidang){
				$where = " AND (ref_upb.kd_bidang = $kb)";
			}
			if($unit){
				$where .= " AND (ref_upb.kd_unit = $ku)";
			}
			if($sub){
				$where .= " AND (ref_upb.kd_sub = $ks)";
			}
			if($upb){
				$where .= " AND (ref_upb.kd_upb = $upb)";
			}
		}else{
			if($bidang){
				$where = " AND (ref_upb.kd_bidang = $kb)";
			}
			if($unit){
				$where .= " AND (ref_upb.kd_unit = $ku)";
			}
			if($sub){
				$where .= " AND (ref_upb.kd_sub = $ks)";
			}
			if($upb){
				$where .= " AND (ref_upb.kd_upb = $kupb)";
			}
		}

		$query= "SELECT TOP 1 ref_bidang.Nm_bidang,ref_unit.Nm_unit,ref_sub_unit.Nm_sub_unit,ref_upb.* from 
				ref_upb inner join ref_sub_unit
				ON ref_upb.kd_bidang=ref_sub_unit.kd_bidang
				AND ref_upb.kd_Unit=ref_sub_unit.kd_Unit
				AND ref_upb.kd_sub=ref_sub_unit.kd_sub INNER JOIN ref_bidang 
				ON ref_upb.kd_bidang=ref_bidang.kd_bidang INNER JOIN ref_unit
				ON ref_upb.kd_bidang=ref_unit.kd_bidang AND ref_upb.kd_unit=ref_unit.kd_unit
				WHERE 1=1 ".$where;
		/*print_r($query); exit();*/
 		return $this->db->query($query);
	}
	
	function get_nama_all_upb(){
	$query= "select ref_bidang.Nm_bidang,ref_unit.Nm_unit,ref_sub_unit.Nm_sub_unit,ref_upb.* from ref_upb inner join ref_sub_unit  
			ON ref_upb.kd_bidang=ref_sub_unit.kd_bidang  AND ref_upb.kd_Unit=ref_sub_unit.kd_Unit  AND ref_upb.kd_sub=ref_sub_unit.kd_sub INNER JOIN ref_bidang ON ref_upb.kd_bidang=ref_bidang.kd_bidang INNER JOIN ref_unit  ON ref_upb.kd_bidang=ref_unit.kd_bidang AND ref_upb.kd_unit=ref_unit.kd_unit";   
	return $this->db->query($query);  
	}
	
	/**
	 * Konvert nama UPB
	 */
	function nama_upb($bidang,$unit,$sub,$upb)
	{	
		$this->db->select('Nm_UPB');
		$this->db->where('Kd_Bidang',$bidang);
		$this->db->where('Kd_Unit',$unit);
		$this->db->where('Kd_Sub',$sub);
		$this->db->where('Kd_UPB',$upb);
        $this->db->from($this->table);
		$sql = $this->db->get();
		foreach ($sql->result() as $row)
		{
			$result = $row->Nm_UPB;
		}
		return $result;
	}
	
}