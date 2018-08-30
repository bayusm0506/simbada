<?php
class Ta_ruang_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
	var $table = 'Ta_Ruang';
	
	/**
	 * Menampilkan semua data kib b
	 */
	function ruang_kir($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$ruang,$like)
	{	

		$query = "select DISTINCT * from ta_ruang WHERE (Kd_Bidang = $kd_bidang) 
				  AND (Kd_Unit = $kd_unit) 
				  AND (Kd_Sub = $kd_sub) 
				  AND (Kd_UPB = $kd_upb) AND Kd_Ruang=$ruang";
		return $this->db->query($query);
	}
	/* konversi nama ruang */
	function nama_ruang($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_ruang)
	{	
		$query= $this->db->query("SELECT DISTINCT Nm_Ruang FROM Ta_Ruang WHERE Kd_Bidang=$kd_bidang AND Kd_Unit=$kd_unit
								 AND Kd_Sub=$kd_upb AND Kd_UPB=$kd_upb AND Kd_Ruang=$kd_ruang")->row();
		return $query;
	}
	
	/**
	 * Menampilkan semua data ruang
	 */
	function now_data_ruang($bidang,$unit,$sub,$upb,$like)
	{	
		
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

		if($this->session->userdata('tahun')){
			$this->db->where('Tahun',$this->session->userdata('tahun'));
		}else{
			$this->db->where('Tahun',$this->session->userdata('tahun_anggaran'));
		}

		$this->db->order_by('Nm_Ruang','ASC');
		$this->db->select('Tahun,Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Ruang,Nm_Ruang');
        $this->db->from($this->table);
		return $this->db->distinct()->get();
	}
	
	function get_data_ruang($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$ruang,$like)
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
		$this->db->where('Kd_Ruang',$ruang);
		$this->db->select('*');
		$this->db->from('Ta_ruang');
		$result = $this->db->get();
     
        return $result;
	}
	
	
	/**
	 * Tampilkan total harga
	 */
	function total($bidang,$unit,$sub,$upb,$like)
	{	
	
		$like == '' ? $this->db->like('Tgl_Perolehan',$this->session->userdata('tahun_anggaran')) : $like == 'all' ? '' : $this->db->like($like);
		
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
	
		$this->db->select_SUM('Harga');
        $this->db->from($this->table);
		$sql = $this->db->get();
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	}
	
	
	/**
	 * Mendapatkan data sebuah ruang
	 */
	function get_ruang_by_id($tahun,$kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_ruang)
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

		if($this->session->userdata('tahun')){
				$this->db->where('Tahun',$this->session->userdata('tahun'));
			}else{
				$this->db->where('Tahun',$this->session->userdata('tahun_anggaran'));
			}

		$this->db->where('Kd_Ruang',$kd_ruang);
		return $this->db->get($this->table);
	}
	
	
	/**
	 * mendapatkan no register terakhir
	 */
	function get_last_ruang($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)
	{	
	
		$this->db->select_MAX('Kd_Ruang');
		$array_keys_values = $this->db->get_where($this->table,array('Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,
		'Kd_UPB' => $kd_upb));
        
		foreach ($array_keys_values->result() as $row)
        {
            $result = $row->Kd_Ruang;
        }
        
        return $result+1;
		
	}
	
	function RuangList($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun){
		$result = array();
		$this->db->select('*');
		$this->db->where('Kd_Prov',$kd_prov);
		$this->db->where('Kd_Kab_Kota',$kd_kab);
		$this->db->where('Kd_Bidang',$kd_bidang);
		$this->db->where('Kd_Unit',$kd_unit);
		$this->db->where('Kd_Sub',$kd_sub);
		$this->db->where('Kd_UPB',$kd_upb);
		/*$this->db->like('Tahun',$tahun);*/
		$this->db->from($this->table);
		$this->db->order_by('Kd_Ruang','ASC');
		$array_keys_values = $this->db->get();
        foreach ($array_keys_values->result() as $row)
        {
            $result['']= '- Pilih Ruang -';
            $result[$row->Kd_Ruang]= $row->Kd_Ruang.'-'.$row->Nm_Ruang;
        }
        
        return $result;
	}
	
	/**
	 * Mendapatkan semua data Ruang, diurutkan berdasarkan id_Ruang
	 */
	function get_Ruang()
	{
		$this->db->order_by('id_Ruang');
		return $this->db->get('Ruang');
	}
	
	function get_all()
	{
		$this->db->order_by('id_Ruang');
		return $this->db->get($this->table);
	}
	
	/**
	 * Menghapus sebuah data kiba
	 */
	function hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_ruang,$tahun)
	{
		$this->db->delete($this->table, array('Tahun' => $tahun,'Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Ruang' => $kd_ruang));
	}
	
	/**
	 * Tambah data Ruang
	 */
	function add($Ruang)
	{
		$this->db->insert($this->table, $Ruang);
		return TRUE;
	}
	
	/**
	 * Update data umum
	 */
	function update($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kdruang,$tahun,$ruang)
	{
		$this->db->where('Kd_Bidang', $kd_bidang);
		$this->db->where('Kd_Unit', $kd_unit);
		$this->db->where('Kd_Sub', $kd_sub);
		$this->db->where('Kd_UPB', $kd_upb);
		$this->db->where('Tahun', $tahun);
		$this->db->where('Kd_Ruang', $kdruang);
		$this->db->update($this->table, $ruang);
		return TRUE;
	}
	
	/**
	 * Validasi agar tidak ada Ruangd dengan id ganda
	 */
	function valid_id($id_Ruang)
	{
		$query = $this->db->get_where($this->table, array('id_Ruang' => $id_Ruang));
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

/* End of file Ruang_model.php */
/* Location: ./system/application/models/Ruang_model.php */