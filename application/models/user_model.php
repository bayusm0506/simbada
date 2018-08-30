<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model{
	
	var $table = 'web_users';
	
	function get_data_user($like)
	{
		$like == '' ? '' : $this->db->like($like);
		
		if ($this->session->userdata('lvl') == 03){
			$this->db->where('web_users.kd_bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('web_users.kd_unit',$this->session->userdata('kd_unit'));
			$this->db->where('web_users.kd_sub_unit',$this->session->userdata('kd_sub_unit'));
			$this->db->where('web_users.kd_upb',$this->session->userdata('kd_upb'));	
		}elseif ($this->session->userdata('lvl') == 02){
			$this->db->where('web_users.kd_bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('web_users.kd_unit',$this->session->userdata('kd_unit'));
			$this->db->where('web_users.kd_sub_unit',$this->session->userdata('kd_sub_unit'));
		}
		
		$this->db->select('web_users.*,Ref_UPB.Nm_UPB');
        $this->db->from('web_users');
        $this->db->join('Ref_UPB', 'web_users.kd_prov = Ref_UPB.Kd_Prov AND web_users.kd_kab_kota = Ref_UPB.Kd_Kab_Kota AND web_users.kd_bidang = Ref_UPB.Kd_Bidang AND web_users.kd_unit = Ref_UPB.Kd_Unit AND web_users.kd_sub_unit = Ref_UPB.Kd_Sub AND web_users.kd_upb = Ref_UPB.Kd_UPB');
		return $this->db->get();
	}
	
	function get_all_user()
	{
		$query = "SELECT * FROM web_users LEFT JOIN Ref_UPB ON web_users.kd_prov = Ref_UPB.Kd_Prov
				AND web_users.kd_kab_kota = Ref_UPB.Kd_Kab_Kota AND web_users.kd_bidang = Ref_UPB.Kd_Bidang 
				AND web_users.kd_unit = Ref_UPB.Kd_Unit AND web_users.kd_sub_unit = Ref_UPB.Kd_Sub AND web_users.kd_upb = Ref_UPB.Kd_UPB ORDER BY lvl";
		
        return $this->db->query($query);
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
	 * Mendapatkan data sebuah users
	 */
	function get_data_user_by_id($id_user)
	{
		
		if ($this->session->userdata('lvl') == 03){
			$this->db->where('kd_bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('kd_unit',$this->session->userdata('kd_unit'));
			$this->db->where('kd_sub_unit',$this->session->userdata('kd_sub_unit'));
			$this->db->where('kd_upb',$this->session->userdata('kd_upb'));	
		}elseif ($this->session->userdata('lvl') == 02){
			$this->db->where('kd_bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('kd_unit',$this->session->userdata('kd_unit'));
			$this->db->where('kd_sub_unit',$this->session->userdata('kd_sub_unit'));
		}
		return $this->db->get_where($this->table, array('id_user' => $id_user), 1);
	}
	
	/**
	 * Tampilkan 10 
	 */
	function get_10_user($limit,$offset)
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
			$x = "WHERE kd_bidang=$kb AND Kd_unit=1 and kd_sub_unit=1";
		}else{
			$x = '';
		}
		
		$query= $this->db->query("SELECT     * FROM (SELECT     *, ROW_NUMBER() OVER (ORDER BY id_user) AS no_urut FROM web_users $x) as   web_users   WHERE  no_urut BETWEEN $first AND $last");
							
 		return $query;
	}
	
	/**
	 * Tampilkan jumlah data
	 */
	function get_jumlah_data()
	{	
		if ($this->session->userdata('lvl') == 02){
			$this->db->where('kd_bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('kd_unit',$this->session->userdata('kd_unit'));
			$this->db->where('kd_sub_unit',$this->session->userdata('kd_sub_unit'));	
		}
	
		$this->db->select('*');
        $this->db->from($this->table);
		return $this->db->get();		
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
	 * Tambah data users
	 */
	function add($user)
	{
		$sql = $this->db->insert($this->table, $user);
		if($sql)
			return TRUE;
	}
	
	/**
	 * Update data users
	 */
	function update($id_user, $user)
	{
		$this->db->where('id_user', $id_user);
		$sql = $this->db->update($this->table, $user);
		
		if($sql)
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
	
}