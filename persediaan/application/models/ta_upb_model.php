<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ta_upb_model extends CI_Model{
	
	var $table = 'Ta_UPB';
	
	function get_data_umum($bidang='',$unit='',$sub='',$upb='',$tahun)
	{			
		$ses_kb    =  $this->session->userdata('kd_bidang');
		$ses_ku    =  $this->session->userdata('kd_unit');
		$ses_ks    =  $this->session->userdata('kd_sub_unit');
		$ses_kupb  =  $this->session->userdata('kd_upb');
		$where = "";
		if ($this->session->userdata('lvl') == 01){
			if($bidang){
				$where = " AND (Kd_Bidang = $bidang)";
			}
			if($unit){
				$where .= " AND (Kd_Unit = $unit)";
			}
			if($sub){
				$where .= " AND (Kd_Sub = $sub)";
			}
			if($upb){
				$where .= " AND (Kd_UPB = $upb)";
			}
		}elseif ($this->session->userdata('lvl') == 02){
			if($bidang){
				$where = " AND (Kd_Bidang = $ses_kb)";
			}
			if($unit){
				$where .= " AND (Kd_Unit = $ses_ku)";
			}
			if($sub){
				$where .= " AND (Kd_Sub = $ses_ks)";
			}
			if($upb){
				$where .= " AND (Kd_UPB = $upb)";
			}
		}else{
			if($bidang){
				$where = " AND (Kd_Bidang = $ses_kb)";
			}
			if($unit){
				$where .= " AND (Kd_Unit = $ses_ku)";
			}
			if($sub){
				$where .= " AND (Kd_Sub = $ses_ks)";
			}
			if($upb){
				$where .= " AND (Kd_UPB = $ses_kupb)";
			}
		}
		
		/*Jika data UPB kosong maka ke Ta_Pemda*/
		if(!empty($bidang) AND !empty($unit) AND !empty($sub)){
			$query = "SELECT TOP 1 * FROM Ta_UPB WHERE 1=1 ".$where;
		}else{
			$query = "SELECT TOP 1 * FROM Ta_Pemda WHERE 1=1";
		}

		$query .= " AND Tahun = {$tahun}";

		/*print_r($query); exit();*/
		return $this->db->query($query);
	}

	/*function get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)
	{
		
		if ($this->session->userdata('lvl') == 01){
			$this->db->where('Kd_Bidang',$kd_bidang);
			$this->db->where('Kd_Unit',$kd_unit);
			$this->db->where('Kd_Sub',$kd_sub);
			$this->db->where('Kd_UPB',$kd_upb);	
		}elseif ($this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));	
			$this->db->where('Kd_UPB',$kd_upb);
		}else{
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));
			$this->db->where('Kd_UPB',$this->session->userdata('kd_upb'));	
		}
		
		$this->db->select('*');
		$this->db->where('Tahun',$tahun);
		$this->db->from('Ta_UPB');
		$result = $this->db->get();
     
        return $result;
	}*/
	
	function get_data_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)
	{
		$like == '' ? $this->db->like('Tahun',$this->session->userdata('tahun_anggaran')) : $like == 'all' ? '' : $this->db->like($like);
		
		if ($this->session->userdata('lvl') == 01){
			$this->db->where('Kd_Bidang',$kd_bidang);
			$this->db->where('Kd_Unit',$kd_unit);
			$this->db->where('Kd_Sub',$kd_sub);
			$this->db->where('Kd_UPB',$kd_upb);	
		}elseif ($this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));	
			$this->db->where('Kd_UPB',$kd_upb);
		}else{
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));
			$this->db->where('Kd_UPB',$this->session->userdata('kd_upb'));	
		}
		
		$this->db->select('*');
		$this->db->from('Ta_UPB');
		$result = $this->db->get();
     
        return $result;
	}
	
	/**
	 * Menampilkan semua data kib b
	 */
	function now_data_umum($bidang,$unit,$sub,$upb,$like)
	{	
		/*$like != 'all' ? $like == '' ? '' : $this->db->like($like) : $this->db->like('Tgl_Perolehan',$this->session->userdata('tahun_anggaran'));*/
		/*$like == '' ? $this->db->like('Tahun',$this->session->userdata('tahun_anggaran')) : $like == 'all' ? '' : $this->db->like($like);*/
		
		if ($this->session->userdata('lvl') == 01){
			$this->db->where('Kd_Bidang',$bidang);
			$this->db->where('Kd_Unit',$unit);
			$this->db->where('Kd_Sub',$sub);
			$this->db->where('Kd_UPB',$upb);	
		}elseif ($this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));	
			$this->db->where('Kd_UPB',$upb);
		}else{
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));
			$this->db->where('Kd_UPB',$this->session->userdata('kd_upb'));	
		}
		$this->db->select('*');
        $this->db->from('Ta_UPB');
		return $this->db->get();
	}	
	
	function LevelList(){
		$result = array();
		$this->db->select('*');
		$this->db->from('level');
		$this->db->order_by('level_id','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result[0]= '-Pilih Level-';
            $result[$row->level_id]= $row->level_id.".  ".$row->level_nama;
        }
        
        return $result;
	}
	
	
	/**
	 * Tampilkan jumlah data
	 */
	function get_jumlah_data()
	{	
		$this->db->select('*');
		if ($this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));	
		}
        $this->db->from($this->table);
		return $this->db->get();		
	}
	
	/**
	 * Menambah data umum
	 */
	function add($dataumum)
	{
		$this->db->insert($this->table, $dataumum);
		return TRUE;
	}
	
	
	/**
	 * Mendapatkan data sebuah users
	 */
	function get_data_user($id_user)
	{
		
		return $this->db->get_where($this->table, array('id_user' => $id_user), 1)->row();
	}
	
	function get_all()
	{
		$this->db->select('users.*,level.level_nama');
		$this->db->from($this->table);
		$this->db->join('level', 'users.level = level.level_id');
		//$this->db->limit($limit, $offset);
		//$this->db->order_by('level_id', 'asc');
		return $this->db->get()->result();
	}
	
	function count_all()
	{
		return $this->db->count_all($this->table);
	}
	
	/**
	 * Menghapus sebuah data users
	 */
	function delete($id_user)
	{
		$this->db->delete($this->table, array('id_user' => $id_user));
	}
	
	/**
	 * Update data umum
	 */
	function update($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun,$dataumum)
	{
		$this->db->where('Kd_Bidang', $kd_bidang);
		$this->db->where('Kd_Unit', $kd_unit);
		$this->db->where('Kd_Sub', $kd_sub);
		$this->db->where('Kd_UPB', $kd_upb);
		$this->db->where('Tahun', $tahun);
		$this->db->update($this->table, $dataumum);
		return TRUE;
	}
	
	/**
	 * mendapatkan id terakhir
	 */
	function get_last_id()
	{	
		$this->db->select_MAX('id_user');
        $this->db->from($this->table);
		$data = $this->db->get();
        
		foreach ($data->result() as $row)
        {
            $result = $row->id_user;
        }
        return $result+1;
		
	}
	
		/**
	 * Mengaktifkan sebuah semester dan menonaktifkan lainnya, menggunakan transaksi
	 */
	function aktif($id_user)
	{
		$this->db->where('id_user', $id_user);
		$this->db->update($this->table, array('blokir'=>'N'));
		return TRUE;
	}
	
	/**
	 * Menonaktifkan sebuah semester dan mengaktifkan lainnya, menggunakan transaksi
	 */
	 
	 function nonaktif($id_user)
	{
		$this->db->where('id_user', $id_user);
		$this->db->update($this->table, array('blokir'=>'Y'));
		return TRUE;
	}
	
	function get_all_data_umum($tahun){
		$this->db->select('*'); 
		$this->db->where('Tahun',$tahun); 
		$this->db->from('Ta_UPB'); 
		$result = $this->db->get(); 
		return $result; 
	}

	
}