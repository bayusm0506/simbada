<?php
/**
 * pemilik_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Pemilik_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
	var $table = 'Ref_Pemilik';
	
	
	function PemilikList(){
		$result = array();
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->order_by('Kd_Pemilik','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result['']= '- Pilih Pemilik -';
            $result[$row->Kd_Pemilik]= $row->Kd_Pemilik.'-'.$row->Nm_Pemilik;
        }
        
        return $result;
	}
	
	/**
	 * Mendapatkan semua data pemilik, diurutkan berdasarkan id_pemilik
	 */
	function get_pemilik()
	{
		$this->db->order_by('id_pemilik');
		return $this->db->get('pemilik');
	}
	
	/**
	 * Mendapatkan data sebuah pemilik
	 */
	function get_pemilik_by_id($id_pemilik)
	{
		return $this->db->get_where($this->table, array('id_pemilik' => $id_pemilik), 1)->row();
	}
	
	function get_all()
	{
		$this->db->order_by('id_pemilik');
		return $this->db->get($this->table);
	}
	
	/**
	 * Menghapus sebuah data pemilik
	 */
	function delete($id_pemilik)
	{
		$this->db->delete($this->table, array('id_pemilik' => $id_pemilik));
	}
	
	/**
	 * Tambah data pemilik
	 */
	function add($pemilik)
	{
		$this->db->insert($this->table, $pemilik);
	}
	
	/**
	 * Update data pemilik
	 */
	function update($id_pemilik, $pemilik)
	{
		$this->db->where('id_pemilik', $id_pemilik);
		$this->db->update($this->table, $pemilik);
	}
	
	/**
	 * Validasi agar tidak ada pemilikd dengan id ganda
	 */
	function valid_id($id_pemilik)
	{
		$query = $this->db->get_where($this->table, array('id_pemilik' => $id_pemilik));
		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

/* End of file pemilik_model.php */
/* Location: ./system/application/models/pemilik_model.php */