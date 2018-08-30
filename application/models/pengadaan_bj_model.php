<?php
/**
 * Contoh_model Class
 */
class Pengadaan_bj_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
	/* Inisialisasi nama tabel yang digunakan */
	var $table = 'Ta_Pengadaan_BJ';
	
	
	function get_page($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }
        $thn   =  $this->session->userdata('tahun_anggaran');

        $sql ="SELECT  * FROM  (
		SELECT     a.*, ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS no_urut
			FROM  Ta_Pengadaan_BJ a 
			WHERE 1=1 $where $like 

		) AS a WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC";

		// print_r($sql); exit();
 		return $this->db->query($sql);
 	}
	
	/**
	 * Tampilkan total harga kib b
	 */
	function count_page($where, $like)
	{	
		$query ="SELECT COUNT(*) as Jumlah, SUM(Harga*Jumlah) as Total
			FROM  Ta_Pengadaan_BJ a 
			WHERE 1=1 $where $like ";

		// print_r($query); exit();
			
		return $result = $this->db->query($query)->row();
	}
		
	
	/**
	 * Mendapatkan data sebuah pengadaan
	 */
	function get_data_by_id($tahun,$kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id)
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
	
		return $this->db->get_where($this->table, array('Tahun' => $tahun,'Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'No_ID' => $no_id), 1);

	}

	/**
	 * Menambah data tanah
	 */
	function save($data)
	{
		$id = $this->get_last_noid($this->session->userdata('kd_prov'), $this->session->userdata('kd_kab_kota'), $data['Kd_Bidang'], $data['Kd_Unit'], $data['Kd_Sub'], $data['Kd_UPB']);

		$arr = array(
				'Tahun'           => $this->session->userdata('tahun_anggaran'),
				'Kd_Prov'         => $this->session->userdata('kd_prov'),
				'Kd_Kab_Kota'     => $this->session->userdata('kd_kab_kota'),
				'Kd_Bidang'       => $data['Kd_Bidang'],
				'Kd_Unit'         => $data['Kd_Unit'],
				'Kd_Sub'          => $data['Kd_Sub'],
				'Kd_UPB'          => $data['Kd_UPB'],
				'No_ID'           => $id,
				'Uraian_Kegiatan' => $data['Uraian_Kegiatan'],
				'Tgl_Kontrak'     => $data['Tgl_Kontrak'],
				'No_Kontrak'      => $data['No_Kontrak'],
				'Tgl_Kuitansi'    => $data['Tgl_Kuitansi'],
				'No_Kuitansi'     => $data['No_Kuitansi'],
				'Jumlah'          => 1,
				'Harga'           => $data['Harga'],
				'Dipergunakan'    => $data['Dipergunakan'],
				'Keterangan'      => $data['Keterangan'],
				'Log_entry'       => date("Y-m-d H:i:s"),
				'Log_User'        => $this->session->userdata('username'));

		// print_r($arr); exit();

        return $this->db->insert('Ta_Pengadaan_BJ',$arr);
	}
	
	function update($tahun,$kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id,$data){

        $arr = array(
				'Uraian_Kegiatan' => $data['Uraian_Kegiatan'],
				'Tgl_Kontrak'     => $data['Tgl_Kontrak'],
				'No_Kontrak'      => $data['No_Kontrak'],
				'Tgl_Kuitansi'    => $data['Tgl_Kuitansi'],
				'No_Kuitansi'     => $data['No_Kuitansi'],
				'Harga'           => $data['Harga'],
				'Dipergunakan'    => $data['Dipergunakan'],
				'Keterangan'      => $data['Keterangan'],
				'Log_entry'       => date("Y-m-d H:i:s"),
				'Log_User'        => $this->session->userdata('username'));

        $this->db->where('Tahun', $tahun);
        $this->db->where('Kd_Prov', $kd_prov);
		$this->db->where('Kd_Kab_Kota', $kd_kab);
		$this->db->where('Kd_Bidang', $kd_bidang);
		$this->db->where('Kd_Unit', $kd_unit);
		$this->db->where('Kd_Sub', $kd_sub);
		$this->db->where('Kd_UPB', $kd_upb);
		$this->db->where('No_ID', $no_id);

        $this->db->update('Ta_Pengadaan_BJ',$arr);

        // echo $this->db->last_query(); exit();
    }
	
	
	/**
	 * Menghapus sebuah data
	 */
	function delete($tahun,$kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id)
	{
		$this->db->delete("Ta_Pengadaan_BJ", array('Tahun' => $tahun,'Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'No_ID' => $no_id));
	}
	
	

	function get_last_noid($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb)
	{	
		$thn   =  $this->session->userdata('tahun_anggaran');
		$where  = "";
		$where .= "AND Tahun = $thn ";
		$where .= "AND Kd_Prov = $kd_prov AND Kd_Kab_Kota = $kd_kab AND Kd_Bidang = $kd_bidang
				   AND Kd_Unit = $kd_unit AND Kd_Sub=$kd_sub AND Kd_UPB=$kd_upb";
		$query  = "SELECT ISNULL(MAX(No_ID),0) as No_ID FROM Ta_Pengadaan_BJ WHERE 1=1 $where";

		// print_r($query); exit();

        $row = $this->db->query($query)->row_array();

        return $row['No_ID']+1;
		
	}

	function laporan_pengadaan($bidang,$unit,$sub,$upb,$like)
	{	
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$where = "";

		if ($this->session->userdata('lvl') == 01){
			if($bidang){
				$where = "AND (a.Kd_Bidang = $bidang)";
			}
			if($unit){
				$where .= "AND (a.Kd_Unit = $unit)";
			}
			if($sub){
				$where .= "AND (a.Kd_Sub = $sub)";
			}
			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}elseif ($this->session->userdata('lvl') == 02){
			if($bidang){
				$where = "AND (a.Kd_Bidang = $kb)";
			}
			if($unit){
				$where .= "AND (a.Kd_Unit = $ku)";
			}
			if($sub){
				$where .= "AND (a.Kd_Sub = $ks)";
			}
			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			if($bidang){
				$where = "AND (a.Kd_Bidang = $kb)";
			}
			if($unit){
				$where .= "AND (a.Kd_Unit = $ku)";
			}
			if($sub){
				$where .= "AND (a.Kd_Sub = $ks)";
			}
			if($upb){
				$where .= "AND (a.Kd_UPB = $kupb)";
			}
		}

		$tgl_perolehan 	= " AND Tgl_Kontrak ".$like;

		$query = "SELECT     a.*
			FROM  Ta_Pengadaan_BJ a 
 			WHERE 1=1 $where $tgl_perolehan ORDER BY Log_entry";

 		// print_r($query); exit();
		return $this->db->query($query);
	}
		
	
}

/* End of file Contoh_model.php */
/* Location: ./system/application/models/Contoh_model.php */