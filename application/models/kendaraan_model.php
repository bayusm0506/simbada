<?php
/**
 * Contoh_model Class
 */
class Kendaraan_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
	var $table = 'Ta_KIB_B';
	
	function get_page($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }
		
		if ($like == ''){
			$like2 = '';
		}else{
			$like2 = "AND $like";	
		}
		
		$query= $this->db->query("SELECT    * FROM  (
SELECT     Ta_KIB_B.*,Ref_Rek_Aset5.Nm_Aset5, ROW_NUMBER() OVER (ORDER BY Kd_Prov) AS 
no_urut		FROM  Ta_KIB_B INNER JOIN Ref_Rek_Aset5 ON 
Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
AND Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5  WHERE $where $like2 AND
Ta_KIB_B.Kd_Aset1 = '2' AND Ta_KIB_B.Kd_Aset2 ='3') AS Ta_KIB_B WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC");				
 		return $query;
 	}
	
	/* count kib b total page */
	function count_kib($where, $like) {
		
		if ($like == ''){
			$like2 = '';
		}else{
			$like2 = "WHERE ".$like;	
		}
					
 		return $this->db->query("SELECT     * FROM         (SELECT     *	FROM         Ta_KIB_B WHERE $where AND
Ta_KIB_B.Kd_Aset1 = '2' AND Ta_KIB_B.Kd_Aset2 = '3') AS 
Ta_KIB_B INNER JOIN Ref_Rek_Aset5 ON Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND 
Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND 
Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 $like2");	
    }
	
	
	/**
	 * Tampilkan total harga kib b
	 */
	function total_kib($where, $like)
	{	
		if ($like == ''){
			$like2 = '';
		}else{
			$like2 = "AND $like";	
		}
	
		$query= "SELECT   SUM(Harga) AS Harga FROM  Ta_KIB_B INNER JOIN Ref_Rek_Aset5 ON 
Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
AND Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5  WHERE $where $like2 AND
Ta_KIB_B.Kd_Aset1 = '2' AND Ta_KIB_B.Kd_Aset2 = '3'";
			
		$sql = $this->db->query($query);
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	}
	
	/**
	 * Menampilkan semua data kib b
	 */
	function now_data_kendaraan($bidang,$unit,$sub,$upb,$like)
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
		$this->db->select('Ta_Kib_B.*,Ref_Rek_Aset5.Nm_Aset5');
		$this->db->where('Ta_Kib_B.Kd_Aset1',2);
		$this->db->where('Ta_Kib_B.Kd_Aset2',3);
        $this->db->from('Ta_Kib_B');
		$this->db->order_by('Tgl_Perolehan', 'ASC');
        $this->db->join('Ref_Rek_Aset5', 'Ta_Kib_B.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Ta_Kib_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_Kib_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_Kib_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_Kib_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5');
		return $this->db->get();
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
		
		$this->db->where('Ta_Kib_B.Kd_Aset1',2);
		$this->db->where('Ta_Kib_B.Kd_Aset2',3);
		$this->db->join('Ref_Rek_Aset5', 'Ta_Kib_B.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Ta_Kib_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_Kib_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_Kib_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_Kib_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5');
		 
		$this->db->select_SUM('Harga');
		$array_keys_values = $this->db->get($this->table);
        
		foreach ($array_keys_values->result() as $row)
        {
            $result = $row->Harga;
        }
        
        return $result;
	}
	
	
	/**
	 * Mendapatkan data sebuah kendaraan
	 */
	function get_kendaraan_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
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
		
		$this->db->where('Ta_Kib_B.Kd_Aset1',2);
		$this->db->where('Ta_Kib_B.Kd_Aset2',3);
		return $this->db->get_where($this->table, array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register), 1);
	}
	
	/**
	 * Menampilkan semua data tanah
	 */
	function data_kib_b($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)
	{		
		$this->db->select('Ta_KIB_B.*,Ref_Rek_Aset5.Nm_Aset5');
        $this->db->from('Ta_KIB_B');
        $this->db->join('Ref_Rek_Aset5', 'Ta_KIB_B.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5');
		$this->db->where('Ta_KIB_B.Kd_Bidang',$kd_bidang);
		$this->db->where('Ta_KIB_B.Kd_Unit',$kd_unit);
		$this->db->where('Ta_KIB_B.Kd_Sub',$kd_sub);
		$this->db->where('Ta_KIB_B.Kd_UPB',$kd_upb);
		$this->db->like('Tgl_Perolehan',$tahun);
		return $this->db->get();
	}
		
	/**
	 * Menghitung jumlah baris dalam sebuah tabel, ada kaitannya dengan pagination
	 */
	function count_all_num_rows()
	{
		return $this->db->count_all($this->table);
	}
	
	function getTotalRowAllData($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun){
			$this->db->where('Ta_KIB_B.Kd_Bidang',$kd_bidang);
			$this->db->where('Ta_KIB_B.Kd_Unit',$kd_unit);
			$this->db->where('Ta_KIB_B.Kd_Sub',$kd_sub);
			$this->db->where('Ta_KIB_B.Kd_UPB',$kd_upb);
			$this->db->like('Ta_KIB_B.Tgl_Perolehan',$tahun);
			return $this->db->count_all($this->table);
	}
	
	/**
	 * pencarian semua data tanah
	 */
	function caridata($limit, $offset,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$q,$s){	
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }
		
		if ($q == 'all'){
			$like = '';
		}else{
			$like = "AND Ta_KIB_B.$q like '%$s%'";	
		}
		
		$query= $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Prov) AS no_urut
		FROM         Ta_KIB_B where Ta_KIB_B.Kd_Bidang=$kd_bidang AND Ta_KIB_B.Kd_Unit=$kd_unit AND Ta_KIB_B.Kd_Sub=$kd_sub AND Ta_KIB_B.Kd_UPB=$kd_upb
		AND Ta_KIB_B.Kd_Aset1 = '2' AND Ta_KIB_B.Kd_Aset2  '3' $like) AS Ta_KIB_B INNER JOIN Ref_Rek_Aset5 ON Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
		AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 WHERE no_urut BETWEEN $first AND $last");				
							
 		return $query;
 	}

	function getAllGrid($start,$limit,$sidx,$sord,$where){
    $this->db->select('*');
    $this->db->limit($limit);
    if($where != NULL)$this->db->where($where,NULL,FALSE);
    $this->db->order_by($sidx,$sord);
    $query = $this->db->get('Ref_Rek_Aset5',$limit,$start);

    return $query->result();
  }
	
	/**
	 * Menampilkan semua data tanah
	 */
	function get_all()
	{		
		$this->db->limit('10');
		$this->db->select('Ta_KIB_B.*,Ref_UPB.Nm_UPB');
        $this->db->from('Ta_KIB_B');
        $this->db->join('Ref_UPB', 'Ta_KIB_B.Kd_Prov = Ref_UPB.Kd_Prov AND Ta_KIB_B.Kd_Kab_Kota = Ref_UPB.Kd_Kab_Kota AND Ta_KIB_B.Kd_Bidang = Ref_UPB.Kd_Bidang AND Ta_KIB_B.Kd_Unit = Ref_UPB.Kd_Unit AND Ta_KIB_B.Kd_Sub = Ref_UPB.Kd_Sub AND Ta_KIB_B.Kd_UPB = Ref_UPB.Kd_UPB');
		return $this->db->get();
	}
	
	
	/**
	 * Menampilkan semua data tanah
	 */
	function hitung_data_kib_b($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)
	{	$query= $this->db->query("select SUM(Harga) from ref_upb
				where ref_upb.kd_bidang=$kd_bidang AND ref_upb.kd_unit=$kd_unit AND ref_upb.kd_sub=$kd_sub AND ref_upb.kd_upb=$kd_upb");
							
 		return $query;
	}
	
	/**
	 * Tampilkan 10 baris absen terkini, diurutkan berdasarkan tanggal (Descending)
	 */
	function get_10_data($limit, $offset,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)
	{			
        if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }
		
		$query= $this->db->query("SELECT   no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Prov) AS no_urut
		FROM         Ta_KIB_B where Ta_KIB_B.Kd_Bidang=$kd_bidang AND Ta_KIB_B.Kd_Unit=$kd_unit AND Ta_KIB_B.Kd_Sub=$kd_sub AND Ta_KIB_B.Kd_UPB=$kd_upb
		AND Ta_KIB_B.Kd_Aset1 = '2' AND Ta_KIB_B.Kd_Aset2  '3' AND Ta_KIB_B.Tgl_Perolehan like '%$tahun%' ) AS Ta_KIB_B INNER JOIN Ref_Rek_Aset5 ON Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
		AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 WHERE no_urut BETWEEN $first AND $last");
							
 		return $query;
		
	}
	
	/**
	 * Tampilkan jumlah data
	 */
	function get_jumlah_data($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)
	{	
		$this->db->select('*');
        $this->db->from($this->table);
		$this->db->where('Kd_Aset1',2);
		$this->db->where('Kd_Aset2',3);
		$this->db->where('Kd_Bidang',$kd_bidang);
		$this->db->where('Kd_Unit',$kd_unit);
		$this->db->where('Kd_Sub',$kd_sub);
		$this->db->where('Kd_UPB',$kd_upb);
		$this->db->like('Tgl_Perolehan',$tahun);
		return $this->db->get();		
	}
	
	
	/**
	 * Tampilkan total harga pencarian
	 */
	function total_cari($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$q,$s)
	{	
		$this->db->select_SUM('Harga');
        $this->db->from($this->table);
		$this->db->where('Kd_Aset1',2);
		$this->db->where('Kd_Aset2 ',3);
		$this->db->where('Kd_Bidang',$kd_bidang);
		$this->db->where('Kd_Unit',$kd_unit);
		$this->db->where('Kd_Sub',$kd_sub);
		$this->db->where('Kd_UPB',$kd_upb);
		$q != 'all' ? $this->db->like($q,$s) : '';
		$sql = $this->db->get();
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	}
	
	/**
	 * Tampilkan jumlah data pencarian
	 */
	function get_jumlah_datacari($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$q,$s)
	{	
	
		$this->db->select('*');
        $this->db->from($this->table);
		$this->db->where('Kd_Bidang',$kd_bidang);
		$this->db->where('Kd_Unit',$kd_unit);
		$this->db->where('Kd_Sub',$kd_sub);
		$this->db->where('Kd_UPB',$kd_upb);
		$q != 'all' ? $this->db->like($q,$s) : '';
		return $this->db->get();
	}
	
	
	
	/**
	 * mendapatkan no register terakhir
	 */
	function get_last_noregister($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5)
	{	
	
		$this->db->select_MAX('No_Register');
		$array_keys_values = $this->db->get_where($this->table,array('Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,
		'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5));
        
		foreach ($array_keys_values->result() as $row)
        {
            $result = $row->No_Register;
        }
        
        return $result+1;
		
	}
	
	/**
	 * memeriksa register
	 */
	function cek_register($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{	
		$result = $this->db->get_where($this->table,array('Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,
		'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register));
        
        return $result;
		
	}
	
	/**
	 * Menambah data siswa
	 */
	function add($kendaraan)
	{
		$this->db->insert($this->table, $kendaraan);
		return TRUE;
	}
	
	
	/**
	 * Menghapus sebuah data kendaraan
	 */
	function hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		$this->db->delete($this->table, array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register));
	}
	
	
	/**
	 * Update data kendaraan
	 */
	function update($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kendaraan)
	{
		$this->db->where('Kd_Bidang', $kd_bidang);
		$this->db->where('Kd_Unit', $kd_unit);
		$this->db->where('Kd_Sub', $kd_sub);
		$this->db->where('Kd_UPB', $kd_upb);
		
		$this->db->where('Kd_Aset1', $kd_aset1);
		$this->db->where('Kd_Aset2', $kd_aset2);
		$this->db->where('Kd_Aset3', $kd_aset3);
		$this->db->where('Kd_Aset4', $kd_aset4);
		$this->db->where('Kd_Aset5', $kd_aset5);
		$this->db->where('No_Register', $no_register);
		$this->db->update($this->table, $kendaraan);
		
		return TRUE;
		
		
	}	
	
	/**
	 * pindah data ke skpd
	 */
	function pindah($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kibb)
	{
		$this->db->where('Kd_Bidang', $kd_bidang);
		$this->db->where('Kd_Unit', $kd_unit);
		$this->db->where('Kd_Sub', $kd_sub);
		$this->db->where('Kd_UPB', $kd_upb);
		
		$this->db->where('Kd_Aset1', $kd_aset1);
		$this->db->where('Kd_Aset2', $kd_aset2);
		$this->db->where('Kd_Aset3', $kd_aset3);
		$this->db->where('Kd_Aset4', $kd_aset4);
		$this->db->where('Kd_Aset5', $kd_aset5);
		$this->db->where('No_Register', $no_register);
		$this->db->update($this->table, $kibb);
		
		return TRUE;
		
	}
	
}

/* End of file Contoh_model.php */
/* Location: ./system/application/models/Contoh_model.php */