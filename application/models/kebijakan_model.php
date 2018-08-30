<?php
/**
 * Contoh_model Class
 */
class Kebijakan_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
	/* Inisialisasi nama tabel yang digunakan */
	var $table = 'Ref_Kebijakan';
	
	
	function get_page($limit, $offset, $like=''){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit - 1);
        }

        if($like){
        	$like = "AND Tahun =".$like;
        }

		$query= "SELECT * FROM  (
				SELECT Ref_Kebijakan.*,
				ROW_NUMBER() OVER (ORDER BY Ref_Kebijakan.Tahun DESC) AS urutan
				FROM  Ref_Kebijakan WHERE 1=1 $like";
		$query .= ") as Ref_Kebijakan WHERE urutan BETWEEN 1 AND 10 ORDER BY Tahun DESC";

		// print_r($query); exit();				
 		return $this->db->query($query);
 	}

	function save($data){

		$cek = $this->db->get_where($this->table, array('Tahun' => $data['Tahun'],'Kd_Aset1' => $data['Kd_Aset1']), 1)->num_rows();

		if($cek){
			$this->session->set_flashdata('message', 'data sudah ada !');
				redirect('kebijakan');
		}
        
        $arr = array(
			'Tahun'    => $data['Tahun'],
			'Kd_Aset1' => $data['Kd_Aset1'],
			'Harga'    => $data['Harga']
        );
                
        $this->db->insert('Ref_Kebijakan',$arr);
        return true;
    }


    function update($data){

    	$arr = array(
			'Tahun'    => $data['Tahun'],
			'Kd_Aset1' => $data['Kd_Aset1'],
			'Harga'    => $data['Harga']
        );
        $this->db->update('Ref_Kebijakan',$arr,array('Tahun'=>$this->session->userdata('Tahun'),'Kd_Aset1'=>$this->session->userdata('Kd_Aset1')));  
    	return true;
	}

	function get_data_id($tahun,$kd_aset){
        $sql = "SELECT * FROM Ref_Kebijakan WHERE 1=1";
        if($kd_aset){
            $sql .=" AND Tahun = '{$tahun}' AND Kd_Aset1 = '{$kd_aset}'";
        }else{
            redirect('login');
        }
        return $this->db->query($sql)->row_array();
    }


	function delete($Tahun,$Kd_Aset1){
		$this->db->delete("Ref_Kebijakan", array('Tahun' => $Tahun, 'Kd_Aset1' => $Kd_Aset1));
	}	
}

/* End of file Contoh_model.php */
/* Location: ./system/application/models/Contoh_model.php */