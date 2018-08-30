<?php
class Chain_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
		
	function getSubUnitList(){

		$this->db->select('*');
		if ($this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));	
		}
		$this->db->from('Ref_Sub_Unit');
		$this->db->order_by('Kd_Bidang','ASC');
        return $this->db->get();
	}
	
	
	function getUPB($limit,$offset,$bidang,$unit,$sub)
	{
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }
		
		$kb = $this->session->userdata('kd_bidang');
		if ($this->session->userdata('lvl') == 02){
			$x = "WHERE Kd_Bidang=$kb AND Kd_Unit=1 and Kd_Sub=1";
		}else{
			$x = '';
		}
		
		$query= $this->db->query("SELECT     no_urut,* FROM 
				(SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Prov) AS no_urut
				FROM    Ref_UPB $x) AS Ref_UPB WHERE no_urut BETWEEN $first AND $last");				
 		return $query;
	}
	
	function getUpbList(){
		$kd_bidang = $this->input->post('kd_bidang');
		$kd_unit = $this->input->post('kd_unit');
		$kd_sub_unit = $this->input->post('kd_sub_unit');
		
		if ($this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));	
		}
		
		
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
            $result[0]= '-Pilih UPB-';
            $result[$row->Kd_UPB]= $row->Kd_UPB.". ".$row->Nm_UPB;
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
            $result[0]= '-Pilih UPB-';
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
            $result[0]= '-Pilih Level-';
            $result[$row->Kd_Level]= $row->Kd_Level.".  ".$row->Nm_Level;
        }
        
        return $result;
	}

	function getAlasanList(){
		$result = array();
		$this->db->select('*');
		$this->db->from('Ref_Alasan');
		
		$this->db->order_by('Kd_Alasan','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result[0]= '- Pilih Alasan -';
            $result[$row->Kd_Alasan]= $row->Kd_Alasan.".  ".$row->Ur_Alasan;
        }
        
        return $result;
	}

}
?>
