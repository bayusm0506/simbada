<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sub_unit_model extends CI_Model{
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'Ref_Sub_Unit';
	
	/**
	 * Tampilkan semua data SKPD
	 */
	function get_unit($bidang='',$unit='')
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
		}elseif ($this->session->userdata('lvl') == 02){

			$where .= " AND (a.Kd_Bidang = $kb)";
			$where .= " AND (a.Kd_Unit = $ku)";
		}else{
			$where .= " AND (a.Kd_Bidang = $kb)";
			$where .= " AND (a.Kd_Unit = $ku)";
		}

		$query = "SELECT * FROM Ref_Unit a WHERE 1=1 $where";

		// print_r($query); exit();

		return $this->db->query($query);		
	}

	function get_sub_unit($bidang='',$unit='',$sub='',$upb='')
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
		}elseif ($this->session->userdata('lvl') == 02){

			$where .= " AND (a.Kd_Bidang = $kb)";
			$where .= " AND (a.Kd_Unit = $ku)";
			$where .= " AND (a.Kd_Sub = $ks)";
		}else{
			$where .= " AND (a.Kd_Bidang = $kb)";
			$where .= " AND (a.Kd_Unit = $ku)";
			$where .= " AND (a.Kd_Sub = $ks)";
		}

		$query = "SELECT * FROM Ref_Sub_Unit a WHERE 1=1 $where";

		// print_r($query); exit();

		return $this->db->query($query);		
	}

	function sub_unit()
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
	
	function nama_skpd($bidang,$unit,$sub)
	{	
		$query= $this->db->query("SELECT Nm_Sub_Unit FROM Ref_Sub_Unit WHERE Kd_Bidang=$bidang AND Kd_Unit=$unit AND Kd_Sub=$sub")->row();
		return $query->Nm_Sub_Unit;
	}


	//model untuk user
	function get_10_sub_unit($limit,$offset)
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
				FROM    Ref_Sub_Unit $x) AS Ref_Sub_Unit WHERE no_urut BETWEEN $first AND $last");				
 		return $query;
	}
	
	
	function get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)
	{
		$query= $this->db->query("select ref_bidang.Nm_bidang,ref_unit.Nm_unit,ref_sub_unit.Nm_sub_unit,ref_upb.* from 
				ref_upb inner join ref_sub_unit
				ON ref_upb.kd_bidang=ref_sub_unit.kd_bidang
				AND ref_upb.kd_Unit=ref_sub_unit.kd_Unit
				AND ref_upb.kd_sub=ref_sub_unit.kd_sub INNER JOIN ref_bidang 
				ON ref_upb.kd_bidang=ref_bidang.kd_bidang INNER JOIN ref_unit
				ON ref_upb.kd_bidang=ref_unit.kd_bidang AND ref_upb.kd_unit=ref_unit.kd_unit
				where ref_upb.kd_bidang=$kd_bidang AND ref_upb.kd_unit=$kd_unit AND ref_upb.kd_sub=$kd_sub AND ref_upb.kd_upb=$kd_upb");
							
 		return $query;
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
	 * Mendapatkan semua data users, diurutkan berdasarkan id_users
	 */
	function get_users($limit, $offset)
	{
		$this->db->limit($limit, $offset);
		$this->db->order_by('id_users');
		return $this->db->get('users');
	}
	
	/**
	 * Tampilkan 10 
	 */
	function get_10_user($limit,$offset,$kd_bidang,$kd_unit,$kd_sub)
	{
		 if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

		$kb = $this->session->userdata('kd_bidang');
		$ku = $this->session->userdata('kd_unit');
		$ks = $this->session->userdata('kd_sub_unit');
		
		if ($this->session->userdata('lvl') == 02){
		$x = "WHERE kd_bidang=$kb AND Kd_unit=$ku and kd_sub_unit=$ks";
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
	 * Tambah data users
	 */
	function add($user)
	{
		$this->db->insert($this->table, $user);
		return TRUE;
	}
	
	/**
	 * Update data users
	 */
	function update($id_user, $user)
	{
		$this->db->where('id_user', $id_user);
		$this->db->update($this->table, $user);
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