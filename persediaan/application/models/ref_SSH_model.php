<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_SSH_model extends CI_Model{
	
	var $table = 'Ref_SSH';

	function nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6)
	{	
		$this->db->select('Nm_Aset6');
		$this->db->where('Kd_Aset1',$kd_aset1);
		$this->db->where('Kd_Aset2',$kd_aset2);
		$this->db->where('Kd_Aset3',$kd_aset3);
		$this->db->where('Kd_Aset4',$kd_aset4);
		$this->db->where('Kd_Aset5',$kd_aset5);
		$this->db->where('Kd_Aset6',$kd_aset6);
        $this->db->from($this->table);
		$sql = $this->db->get();
		foreach ($sql->result() as $row)
		{
			$result = $row->Nm_Aset6;
		}
		return $result;
	}
	
	function json_all($keyword){
		$this->db->LIMIT(10);
		$this->db->select('*')->from('Ref_SSH');
        $this->db->where('Tahun',$this->session->userdata('tahun_anggaran'));
        $this->db->like('Nm_Aset6',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	function count_all($like) {
		$like != '' ? $this->db->like($like) : '';
        return $this->db->count_all_results('Ref_SSH');
    }

    function rekening_bm($keyword){
		$this->db->LIMIT(10);
		$this->db->select('*')->from('Ref_Rek_5');
		$this->db->where('Kd_Rek_1',5);
		$this->db->where('Kd_Rek_2',2);
		$this->db->where('Kd_Rek_3',3);
        $this->db->like('Nm_Rek_5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}

	function json_persediaan($keyword){
		$this->db->LIMIT(10);
		$this->db->select('*')->from('Ref_SSH');
        // $this->db->where('Tahun',$this->session->userdata('tahun_anggaran'));
        $this->db->like('Nm_Aset6',$keyword);
        // $this->db->or_like('Spesifikasi',$keyword);
        $query = $this->db->get();
        
        return $query->result();
	}

	function SatuanList(){
		$result = array();
		$this->db->select('*');
		$this->db->from("Ref_Satuan_Harga");
		$this->db->order_by('Nm_Satuan','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result['']= '- Pilih Satuan -';
            $result[$row->Kd_Satuan]= $row->Nm_Satuan;
        }
        
        return $result;
	}

	function countKodePersediaan($where="") {
		$sql = "SELECT * FROM Ref_SSH WHERE 1=1 $where";
		return $this->db->query($sql);
    }

	function getKodePersediaan($where="", $sidx="", $sord="", $limit="", $start="") {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }
		
        $sql = "SELECT no_urut, * FROM  (
        		SELECT *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
				FROM Ref_SSH WHERE 1=1 $where ) AS a WHERE no_urut BETWEEN $first AND $last";

		return $this->db->query($sql);

	}
	
}