<?php
class Model_chain extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
	function getBidangList(){
		$result = array();
		$this->db->select('*');
		$this->db->from('ref_bidang');
		if ($this->session->userdata('lvl') == 03 or $this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
		}
		$this->db->order_by('Kd_Bidang','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result['']= '-Pilih Bidang-';
            $result[$row->Kd_Bidang]= $row->Kd_Bidang.".  ".$row->Nm_Bidang;
        }
        
        return $result;
	}

	function getUnitList(){
		$kd_bidang = $this->input->post('kd_bidang');
		$result = array();
		$this->db->select('*');
		$this->db->from('ref_unit');
		if ($this->session->userdata('lvl') == 03 or $this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));	
		}else{
			$this->db->where('Kd_Bidang',$kd_bidang);
		}
		$this->db->order_by('Kd_Bidang','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result['']= '-Pilih Unit-';
            $result[$row->Kd_Unit]= $row->Kd_Unit.".  ".$row->Nm_Unit;
        }
        return $result;
	}
	
	function getSubUnitList(){
		$kd_bidang = $this->input->post('kd_bidang');
		$kd_unit = $this->input->post('kd_unit');
		$result = array();
		$this->db->select('*');
		$this->db->from('ref_sub_unit');
		
		if ($this->session->userdata('lvl') == 03){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));
		}elseif ($this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));
		}else{
			$this->db->where('Kd_Bidang',$kd_bidang);
			$this->db->where('Kd_Unit',$kd_unit);
		}
		
		$this->db->order_by('Kd_Bidang','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result['']= '-Pilih Sub Unit-';
            $result[$row->Kd_Sub]= $row->Kd_Sub.". ".$row->Nm_Sub_Unit;
        }
        
        return $result;
	}
	
	
	function getUpbList(){
		$kd_bidang = $this->input->post('kd_bidang');
		$kd_unit = $this->input->post('kd_unit');
		$kd_sub_unit = $this->input->post('kd_sub_unit');
		
		$result = array();
		$this->db->select('*');
		$this->db->from('ref_upb');
		
		if ($this->session->userdata('lvl') == 03){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));	
			$this->db->where('Kd_UPB',$this->session->userdata('kd_upb'));
		}elseif ($this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));
		}else{		
			$this->db->where('Kd_Bidang',$kd_bidang);
			$this->db->where('Kd_Unit',$kd_unit);
			$this->db->where('Kd_Sub',$kd_sub_unit);
		}
		
		$this->db->order_by('Kd_Bidang','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result['']= '-Pilih UPB-';
            $result[$row->Kd_UPB]= $row->Kd_UPB.". ".$row->Nm_UPB;
        }
        
        return $result;
	}
	
	
	function getRuangList(){
		$kd_bidang = $this->input->post('kd_bidang');
		$kd_unit = $this->input->post('kd_unit');
		$kd_sub_unit = $this->input->post('kd_sub_unit');
		$kd_upb = $this->input->post('kd_upb');
		
		$result = array();
		$this->db->select('*');
		$this->db->from('Ta_Ruang');
		
		if ($this->session->userdata('lvl') == 03){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));	
			$this->db->where('Kd_UPB',$this->session->userdata('kd_upb'));
		}elseif ($this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));
			$this->db->where('Kd_UPB',$kd_upb);
		}else{		
			$this->db->where('Kd_Bidang',$kd_bidang);
			$this->db->where('Kd_Unit',$kd_unit);
			$this->db->where('Kd_Sub',$kd_sub_unit);
			$this->db->where('Kd_UPB',$kd_upb);
		}
		//$this->db->where('Tahun',$this->session->userdata('tahun_anggaran'));
		$this->db->order_by('Kd_Ruang','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result['']= '- Pilih RUANG -';
            $result[$row->Kd_Ruang]= $row->Kd_Ruang.". ".$row->Nm_Ruang;
        }
        
        return $result;
	}
	
	function sessionUpbList($kd_bidang,$kd_unit,$kd_sub_unit){
		$result = array();
		$this->db->select('*');
		$this->db->from('ref_upb');
		$this->db->where('Kd_Bidang',$kd_bidang);
		$this->db->where('Kd_Unit',$kd_unit);
		$this->db->where('Kd_Sub',$kd_sub_unit);
		$this->db->order_by('Kd_Bidang','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result['']= '-Pilih UPB-';
            $result[$row->Kd_UPB]= $row->Kd_UPB.". ".$row->Nm_UPB;
        }
        
        return $result;
	}
	
	
	function getLevelList(){
		$result = array();
		$this->db->select('*');
		$this->db->from('Ref_Tingkat');
		
		if ($this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Level <>',01);
		}
		
		$this->db->order_by('Kd_Level','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result['']= '-Pilih Level-';
            $result[$row->Kd_Level]= $row->Kd_Level.".  ".$row->Nm_Level;
        }
        
        return $result;
	}

	function getJabatanList(){
		$result = array();
		$this->db->select('*');
		$this->db->from('jabatan_user');
				
		$this->db->order_by('id','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result['']= '- Pilih Jabatan -';
            $result[$row->id]= $row->id.".  ".$row->jabatan." (".$row->keterangan.")";
        }
        
        return $result;
	}

	function getRiwayatList(){
		$result = array();
		$this->db->select('*');
		$this->db->from('Ref_Riwayat');
		$this->db->where('Aktif','Y');
		$this->db->order_by('Kd_Riwayat','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result['']= '- Pilih Riwayat -';
            $result[$row->Kd_Riwayat]= $row->Nm_Riwayat;
        }
        
        return $result;
	}

	function getRiwayatList_F(){
		$result = array();

		$val = array('3', '7', '19', '21', '23');

		$this->db->select('*');
		$this->db->from('Ref_Riwayat');
		$this->db->where_in('Kd_Riwayat', $val);
		$this->db->order_by('Kd_Riwayat','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result['']= '- Pilih Riwayat -';
            $result[$row->Kd_Riwayat]= $row->Nm_Riwayat;
        }
        
        return $result;
	}

	function getSKList(){
		$result = array();
		$this->db->select('*');
		$this->db->from('Ta_Penghapusan');
		$this->db->order_by('Tgl_SK','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result['']= '- Pilih SK -';
            $result[$row->No_SK]= $row->No_SK;
        }
        
        return $result;
	}

}
?>
