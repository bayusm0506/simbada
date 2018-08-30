<?php
/**
 * Contoh_model Class
 */
class Kibar_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
	/* Inisialisasi nama tabel yang digunakan */
	var $table = 'Ta_KIBAR';
	
	
	function get_page($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

		$query= $this->db->query("SELECT    * FROM  (
SELECT     Ref_UPB.Nm_UPB,Ta_KIBAR.*,Ref_Rek_Aset5.Nm_Aset5, ROW_NUMBER() OVER (ORDER BY Ta_KIBAR.Kd_Prov) AS 
urutan		FROM  Ta_KIBAR INNER JOIN Ref_Rek_Aset5 ON 
Ta_KIBAR.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIBAR.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
AND Ta_KIBAR.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIBAR.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
AND Ta_KIBAR.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 LEFT JOIN Ref_UPB ON Ta_KIBAR.Kd_Bidang=Ref_UPB.Kd_Bidang AND Ta_KIBAR.Kd_Unit=Ref_UPB.Kd_Unit AND
Ta_KIBAR.Kd_Sub=Ref_UPB.Kd_Sub AND Ta_KIBAR.Kd_UPB=Ref_UPB.Kd_UPB
   WHERE $where $like AND
Ta_KIBAR.Kd_Aset1 = '1') AS Ta_KIBAR WHERE urutan BETWEEN $first AND $last  ORDER BY urutan ASC");				
 		return $query;
 	}
	
	/* count kib b total page */
	function count_kib($where, $like) {
 		return $this->db->query("SELECT     * FROM (SELECT     *	FROM   Ta_KIBAR WHERE $where AND
Ta_KIBAR.Kd_Aset1 = '1' ) AS 
Ta_KIBAR INNER JOIN Ref_Rek_Aset5 ON Ta_KIBAR.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND 
Ta_KIBAR.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIBAR.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
AND Ta_KIBAR.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND 
Ta_KIBAR.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 $like");	
    }
	
	
	/**
	 * Tampilkan total harga kib b
	 */
	function total_kib($where, $like)
	{		
		$query= "SELECT   SUM(Harga) AS Harga FROM  Ta_KIBAR INNER JOIN Ref_Rek_Aset5 ON 
Ta_KIBAR.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIBAR.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
AND Ta_KIBAR.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIBAR.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
AND Ta_KIBAR.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5  WHERE $where $like AND
Ta_KIBAR.Kd_Aset1 = '1'";
			
		$sql = $this->db->query($query);
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	}
	
	/**
	 * Menampilkan data laporan kiba
	 */
	function laporan_kiba($bidang,$unit,$sub,$upb,$like)
	{	
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "(Ta_KIBAR.Kd_Bidang = $bidang) 
			  AND (Ta_KIBAR.Kd_Unit = $unit) 
			  AND (Ta_KIBAR.Kd_Sub = $sub) 
			  AND (Ta_KIBAR.Kd_UPB = $upb) AND";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIBAR.Kd_Bidang = $kb )
			  AND (Ta_KIBAR.Kd_Unit =$ku )
			  AND (Ta_KIBAR.Kd_Sub = $ks) 
			  AND (Ta_KIBAR.Kd_UPB = $upb) AND";
		}else{
			$where = "(Ta_KIBAR.Kd_Bidang = $kb) 
			  AND (Ta_KIBAR.Kd_Unit = $ku) 
			  AND (Ta_KIBAR.Kd_Sub = $ks) 
			  AND (Ta_KIBAR.Kd_UPB = $kupb) AND";
		}

		$query = "select * from Ta_KIBAR inner join Ref_Rek_Aset5 on 
Ta_KIBAR.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
Ta_KIBAR.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND 
Ta_KIBAR.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
Ta_KIBAR.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
Ta_KIBAR.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 WHERE $where $like";
		return $this->db->query($query);
	}
	
	
	/**
	 * Tampilkan total harga laporan kiba
	 */
	function total_laporan_kiba($bidang,$unit,$sub,$upb,$like)
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
	
		$this->db->select_SUM('Harga');
		$this->db->where("$like");	
        $this->db->from($this->table);
		$sql = $this->db->get();
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	}
	
	
	/**
	 * Menampilkan data kib A Mutasi
	 */
	function kiba_mutasi($bidang,$unit,$sub,$upb,$like)
	{			
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "(Ta_KIBAR.Kd_Bidang = $bidang) 
			  AND (Ta_KIBAR.Kd_Unit = $unit) 
			  AND (Ta_KIBAR.Kd_Sub = $sub) 
			  AND (Ta_KIBAR.Kd_UPB = $upb) AND";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIBAR.Kd_Bidang = $kb )
			  AND (Ta_KIBAR.Kd_Unit =$ku )
			  AND (Ta_KIBAR.Kd_Sub = $ks) 
			  AND (Ta_KIBAR.Kd_UPB = $upb) AND";
		}else{
			$where = "(Ta_KIBAR.Kd_Bidang = $kb) 
			  AND (Ta_KIBAR.Kd_Unit = $ku) 
			  AND (Ta_KIBAR.Kd_Sub = $ks) 
			  AND (Ta_KIBAR.Kd_UPB = $kupb) AND";
		}

		$query = "SELECT   Ref_Rek_Aset5.Nm_Aset5,COUNT(Ta_KIBAR.No_register) as jumlah_register,MIN(Ta_KIBAR.No_register) as min_register,
MAX(Ta_KIBAR.No_register) as max_register,
Ta_KIBAR.Kd_Prov, Ta_KIBAR.Kd_Kab_Kota, Ta_KIBAR.Kd_Bidang, Ta_KIBAR.Kd_Unit,
Ta_KIBAR.Kd_Sub, Ta_KIBAR.Kd_UPB, Ta_KIBAR.Kd_Aset1, Ta_KIBAR.Kd_Aset2, Ta_KIBAR.Kd_Aset3, 
Ta_KIBAR.Kd_Aset4, Ta_KIBAR.Kd_Aset5, Ta_KIBAR.Kd_Pemilik,DATENAME(yyyy,Ta_KIBAR.Tgl_Perolehan) as Tahun, Ta_KIBAR.Luas_M2, 
Ta_KIBAR.Alamat, Ta_KIBAR.Hak_Tanah, Ta_KIBAR.Sertifikat_Tanggal,Ta_KIBAR.Sertifikat_Nomor,
Ta_KIBAR.Penggunaan,Ta_KIBAR.Asal_usul,COUNT(*) as Jumlah,
SUM(Ta_KIBAR.Harga) as Harga,Ta_KIBAR.Keterangan

FROM  Ta_KIBAR INNER JOIN
Ref_Rek_Aset5 ON Ta_KIBAR.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIBAR.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIBAR.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIBAR.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIBAR.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5

WHERE  $where $like

GROUP BY Ta_KIBAR.Kd_Prov, Ta_KIBAR.Kd_Kab_Kota, Ta_KIBAR.Kd_Bidang, Ta_KIBAR.Kd_Unit,
Ta_KIBAR.Kd_Sub, Ta_KIBAR.Kd_UPB, Ta_KIBAR.Kd_Aset1, Ta_KIBAR.Kd_Aset2, Ta_KIBAR.Kd_Aset3, 
Ta_KIBAR.Kd_Aset4, Ta_KIBAR.Kd_Aset5, Ta_KIBAR.Kd_Pemilik, Ta_KIBAR.Kd_Pemilik,Ta_KIBAR.Tgl_Perolehan, Ta_KIBAR.Luas_M2, 
Ta_KIBAR.Alamat, Ta_KIBAR.Hak_Tanah, Ta_KIBAR.Sertifikat_Tanggal,Ta_KIBAR.Sertifikat_Nomor,
Ta_KIBAR.Penggunaan,Ta_KIBAR.Asal_usul,Ta_KIBAR.Keterangan,Ref_Rek_Aset5.Nm_Aset5 ORDER BY  Ref_Rek_Aset5.Nm_Aset5, MIN(Ta_KIBAR.No_register)";
		return $this->db->query($query);
	}
	
	
	/**
	 * Total Data Mutasi
	 */
	function total_kiba_mutasi($bidang,$unit,$sub,$upb,$like)
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
		 
		$this->db->select_SUM('Harga');
		$this->db->where("$like");
		$array_keys_values = $this->db->get($this->table);
		foreach ($array_keys_values->result() as $row)
        {
            $result = $row->Harga;
        }
        
        return $result;
	}
	
	/**
	 * Menampilkan semua data kib a rekap
	 */
	function rekap_mutasi($bidang,$unit,$sub,$upb,$like,$tahunawal,$tahunakhir)
	{	
		
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		$awal 	= $tahunawal;
		$akhir	= "AND Tgl_Perolehan < '$tahunakhir'";
		
		if ($this->session->userdata('lvl') == 01){
		$where = "(Ta_KIBAR.Kd_Bidang = $bidang) 
			  AND (Ta_KIBAR.Kd_Unit = $unit) 
			  AND (Ta_KIBAR.Kd_Sub = $sub) 
			  AND (Ta_KIBAR.Kd_UPB = $upb)";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIBAR.Kd_Bidang = $kb )
			  AND (Ta_KIBAR.Kd_Unit =$ku )
			  AND (Ta_KIBAR.Kd_Sub = $ks) 
			  AND (Ta_KIBAR.Kd_UPB = $upb)";
		}else{
			$where = "(Ta_KIBAR.Kd_Bidang = $kb) 
			  AND (Ta_KIBAR.Kd_Unit = $ku) 
			  AND (Ta_KIBAR.Kd_Sub = $ks) 
			  AND (Ta_KIBAR.Kd_UPB = $kupb)";
		}
		$query = "select Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2,
COUNT(case when Tgl_Perolehan < '$awal' then 1 else null end) as Jumlah_awal,
SUM(CASE WHEN Tgl_Perolehan < '$awal'  THEN Harga END) as Harga_awal,
COUNT(case WHEN $like then 0 else null end) as Jumlah_tambah,
SUM(CASE WHEN $like  THEN Harga END) as Harga_tambah,
COUNT(*) as Jumlah, 
SUM(Harga) as Harga
 from Ref_Rek_Aset2 LEFT JOIN (SELECT * from Ta_KIBAR WHERE $where $akhir) as Ta_KIBAR ON 
Ta_KIBAR.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND
Ta_KIBAR.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2   WHERE Ref_Rek_Aset2.Kd_Aset1=1
GROUP BY Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2  ORDER BY Ref_Rek_Aset2.Kd_Aset2";
		return $this->db->query($query);
	}
	
	/**
	 * Menampilkan Total rekap Mutasi
	 */
	function total_rekap_mutasi($bidang,$unit,$sub,$upb,$like,$tahunawal,$tahunakhir)
	{	
		
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		$awal 	= $tahunawal;
		$akhir	= "AND Tgl_Perolehan < '$tahunakhir'";
		
		if ($this->session->userdata('lvl') == 01){
		$where = "(Ta_KIBAR.Kd_Bidang = $bidang) 
			  AND (Ta_KIBAR.Kd_Unit = $unit) 
			  AND (Ta_KIBAR.Kd_Sub = $sub) 
			  AND (Ta_KIBAR.Kd_UPB = $upb)";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIBAR.Kd_Bidang = $kb )
			  AND (Ta_KIBAR.Kd_Unit =$ku )
			  AND (Ta_KIBAR.Kd_Sub = $ks) 
			  AND (Ta_KIBAR.Kd_UPB = $upb)";
		}else{
			$where = "(Ta_KIBAR.Kd_Bidang = $kb) 
			  AND (Ta_KIBAR.Kd_Unit = $ku) 
			  AND (Ta_KIBAR.Kd_Sub = $ks) 
			  AND (Ta_KIBAR.Kd_UPB = $kupb)";
		}
		$query = "SELECT COUNT(case when Tgl_Perolehan < '$awal' then 1 else null end) as Jumlah_awal,
SUM(CASE WHEN Tgl_Perolehan < '$awal'  THEN Harga END) as Harga_awal,
COUNT(case WHEN $like then 0 else null end) as Jumlah_tambah,
SUM(CASE WHEN $like  THEN Harga END) as Harga_tambah,
COUNT(*) as Jumlah, 
SUM(Harga) as Harga
 from Ref_Rek_Aset2 LEFT JOIN (SELECT * from Ta_KIBAR WHERE $where $akhir) as Ta_KIBAR ON 
Ta_KIBAR.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND
Ta_KIBAR.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2   WHERE Ref_Rek_Aset2.Kd_Aset1=1 ";
		return $this->db->query($query);
	}
	
	/**
	 * Menampilkan data kib A Buku Induk SKPD
	 */
	function kiba_buku_induk($like)
	{			
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');

		if ($this->session->userdata('lvl') == 01){
			$where = '';
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIBAR.Kd_Bidang = $kb )
			  AND (Ta_KIBAR.Kd_Unit =$ku )
			  AND (Ta_KIBAR.Kd_Sub = $ks) AND";
		}else{
			$where = "(Ta_KIBAR.Kd_Bidang = $kb) 
			  AND (Ta_KIBAR.Kd_Unit = $ku) 
			  AND (Ta_KIBAR.Kd_Sub = $ks) 
			  AND (Ta_KIBAR.Kd_UPB = $kupb) AND";
		}

		$query = "SELECT   Nm_UPB,Ref_Rek_Aset5.Nm_Aset5,COUNT(Ta_KIBAR.No_register) as jumlah_register,MIN(Ta_KIBAR.No_register) as min_register,
	MAX(Ta_KIBAR.No_register) as max_register,
	Ta_KIBAR.Kd_Prov, Ta_KIBAR.Kd_Kab_Kota, Ta_KIBAR.Kd_Bidang, Ta_KIBAR.Kd_Unit,
	Ta_KIBAR.Kd_Sub, Ta_KIBAR.Kd_UPB, Ta_KIBAR.Kd_Aset1, Ta_KIBAR.Kd_Aset2, Ta_KIBAR.Kd_Aset3, 
	Ta_KIBAR.Kd_Aset4, Ta_KIBAR.Kd_Aset5, Ta_KIBAR.Kd_Pemilik,DATENAME(yyyy,Ta_KIBAR.Tgl_Perolehan) as Tahun, Ta_KIBAR.Luas_M2, 
	Ta_KIBAR.Alamat, Ta_KIBAR.Hak_Tanah, Ta_KIBAR.Sertifikat_Tanggal,Ta_KIBAR.Sertifikat_Nomor,
	Ta_KIBAR.Penggunaan,Ta_KIBAR.Asal_usul,COUNT(*) as Jumlah,
	SUM(Ta_KIBAR.Harga) as Harga,Ta_KIBAR.Keterangan
	
	FROM  Ta_KIBAR INNER JOIN
	Ref_Rek_Aset5 ON Ta_KIBAR.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIBAR.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIBAR.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIBAR.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIBAR.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
	INNER JOIN Ref_UPB ON Ta_KIBAR.Kd_Bidang=Ref_UPB.Kd_Bidang AND Ta_KIBAR.Kd_Unit=Ref_UPB.Kd_Unit AND Ta_KIBAR.Kd_Sub=Ref_UPB.Kd_Sub AND Ta_KIBAR.Kd_UPB=Ref_UPB.Kd_UPB
	
	WHERE  $where $like
	
	GROUP BY Ta_KIBAR.Kd_Prov, Ta_KIBAR.Kd_Kab_Kota, Ta_KIBAR.Kd_Bidang, Ta_KIBAR.Kd_Unit,
	Ta_KIBAR.Kd_Sub, Ta_KIBAR.Kd_UPB, Ta_KIBAR.Kd_Aset1, Ta_KIBAR.Kd_Aset2, Ta_KIBAR.Kd_Aset3, 
	Ta_KIBAR.Kd_Aset4, Ta_KIBAR.Kd_Aset5, Ta_KIBAR.Kd_Pemilik, Ta_KIBAR.Kd_Pemilik,Ta_KIBAR.Tgl_Perolehan, Ta_KIBAR.Luas_M2, 
	Ta_KIBAR.Alamat, Ta_KIBAR.Hak_Tanah, Ta_KIBAR.Sertifikat_Tanggal,Ta_KIBAR.Sertifikat_Nomor,
	Ta_KIBAR.Penggunaan,Ta_KIBAR.Asal_usul,Ta_KIBAR.Keterangan,Ref_Rek_Aset5.Nm_Aset5,Nm_UPB ORDER BY  Nm_UPB,Ref_Rek_Aset5.Nm_Aset5, MIN(Ta_KIBAR.No_register)";
		return $this->db->query($query);
	}
	
	
	/**
	 * Total Data buku induk skpd
	 */
	function total_kiba_buku_induk($like)
	{			
		if ($this->session->userdata('lvl') == 02){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));
		}elseif ($this->session->userdata('lvl') == 03){
			$this->db->where('Kd_Bidang',$this->session->userdata('kd_bidang'));
			$this->db->where('Kd_Unit',$this->session->userdata('kd_unit'));
			$this->db->where('Kd_Sub',$this->session->userdata('kd_sub_unit'));
			$this->db->where('Kd_UPB',$this->session->userdata('kd_upb'));		
		}
		 
		$this->db->select_SUM('Harga');
		$this->db->where("$like");
		$array_keys_values = $this->db->get($this->table);
		foreach ($array_keys_values->result() as $row)
        {
            $result = $row->Harga;
        }
        
        return $result;
	}
	
	/**
	 * Menampilkan sdata kib a rekap inventaris
	 */
	function kiba_rinventaris($bidang,$unit,$sub,$upb,$like)
	{	
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "WHERE (Ta_KIBAR.Kd_Bidang = $bidang) 
			  AND (Ta_KIBAR.Kd_Unit = $unit) 
			  AND (Ta_KIBAR.Kd_Sub = $sub) 
			  AND (Ta_KIBAR.Kd_UPB = $upb) AND $like";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "WHERE (Ta_KIBAR.Kd_Bidang = $kb )
			  AND (Ta_KIBAR.Kd_Unit =$ku )
			  AND (Ta_KIBAR.Kd_Sub = $ks) 
			  AND (Ta_KIBAR.Kd_UPB = $upb) AND $like";
		}else{
			$where = "WHERE (Ta_KIBAR.Kd_Bidang = $kb) 
			  AND (Ta_KIBAR.Kd_Unit = $ku) 
			  AND (Ta_KIBAR.Kd_Sub = $ks) 
			  AND (Ta_KIBAR.Kd_UPB = $kupb) AND $like";
		}
		
$query = "select Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2,COUNT(*) as Jumlah, SUM(Harga) as Harga from Ref_Rek_Aset2 LEFT JOIN (SELECT * from Ta_KIBAR $where) as Ta_KIBAR ON 
Ta_KIBAR.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND
Ta_KIBAR.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2   WHERE Ref_Rek_Aset2.Kd_Aset1=1
GROUP BY Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2 ";
		return $this->db->query($query);
	}
	
	/**
	 * Total Data rekap inventaris
	 */
	function total_kiba_rinventaris($bidang,$unit,$sub,$upb,$like)
	{	
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "WHERE (Ta_KIBAR.Kd_Bidang = $bidang) 
			  AND (Ta_KIBAR.Kd_Unit = $unit) 
			  AND (Ta_KIBAR.Kd_Sub = $sub) 
			  AND (Ta_KIBAR.Kd_UPB = $upb) AND $like";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "WHERE (Ta_KIBAR.Kd_Bidang = $kb )
			  AND (Ta_KIBAR.Kd_Unit =$ku )
			  AND (Ta_KIBAR.Kd_Sub = $ks) 
			  AND (Ta_KIBAR.Kd_UPB = $upb) AND $like";
		}else{
			$where = "WHERE (Ta_KIBAR.Kd_Bidang = $kb) 
			  AND (Ta_KIBAR.Kd_Unit = $ku) 
			  AND (Ta_KIBAR.Kd_Sub = $ks) 
			  AND (Ta_KIBAR.Kd_UPB = $kupb) AND $like";
		}
		
		$query = "SELECT COUNT(*) as Jumlah,SUM(Harga) as Harga FROM Ta_KIBAR $where";
		
        return $this->db->query($query);
	}
	
	/**
	 * Menampilkan data kib a rekap gabungan pemda
	 */
	function kiba_rekap($kd_bidang,$kd_unit,$kd_sub_unit,$like)
	{	
		$this->db->select_SUM('Harga');
		$this->db->where('Kd_Bidang',$kd_bidang);
		$this->db->where('Kd_Unit',$kd_unit);
		$this->db->where('Kd_Sub',$kd_sub_unit);
		$this->db->where("$like");
		$array_keys_values = $this->db->get($this->table);
        
		foreach ($array_keys_values->result() as $row)
        {
            $result = $row->Harga;
        }
        
        return $result;
	}
	
	/**
	 * Menampilkan total data kib a rekap gabungan pemda
	 */
	function total_kiba_rekap($like)
	{	
		$this->db->select_SUM('Harga');
		$this->db->where("$like");
		$array_keys_values = $this->db->get($this->table);
        
		foreach ($array_keys_values->result() as $row)
        {
            $result = $row->Harga;
        }
        
        return $result;
	}
	

	/**
	 * Menampilkan semua data tanah
	 */
	 /*
	function now_data_kiba($bidang,$unit,$sub,$upb,$like)
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
		
		$this->db->select('Ta_Kib_A.*,Ref_Rek_Aset5.Nm_Aset5');
        $this->db->from('Ta_Kib_A');
		$this->db->order_by('Tgl_Perolehan', 'ASC');
        $this->db->join('Ref_Rek_Aset5', 'Ta_KIBAR.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIBAR.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIBAR.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIBAR.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIBAR.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5');
		return $this->db->get();
	}
	*/
	
	
	/**
	 * Tampilkan total harga
	 */
	/*
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
	}*/
	
	
	/**
	 * Mendapatkan data sebuah kiba
	 */
	function get_kiba_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
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
		
		
		return $this->db->get_where($this->table, array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register), 1);
	}
	
	/**
	 * Menampilkan semua data tanah
	 */
	 /*
	function data_kib_a($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)
	{		
		$this->db->select('Ta_KIBAR.*,Ref_Rek_Aset5.Nm_Aset5');
        $this->db->from('Ta_KIBAR');
        $this->db->join('Ref_Rek_Aset5', 'Ta_KIBAR.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIBAR.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIBAR.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIBAR.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIBAR.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5');
		$this->db->where('Ta_KIBAR.Kd_Bidang',$kd_bidang);
		$this->db->where('Ta_KIBAR.Kd_Unit',$kd_unit);
		$this->db->where('Ta_KIBAR.Kd_Sub',$kd_sub);
		$this->db->where('Ta_KIBAR.Kd_UPB',$kd_upb);
		$this->db->like('Tgl_Perolehan',$tahun);
		return $this->db->get();
	}*/
	
	
	/**
	 * Menghitung jumlah baris dalam sebuah tabel, ada kaitannya dengan pagination
	 */
	/*
	function count_all_num_rows()
	{
		return $this->db->count_all($this->table);
	}*/
	
	/*function getTotalRowAllData($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun){
			$this->db->where('Ta_Kib_A.Kd_Bidang',$kd_bidang);
			$this->db->where('Ta_Kib_A.Kd_Unit',$kd_unit);
			$this->db->where('Ta_Kib_A.Kd_Sub',$kd_sub);
			$this->db->where('Ta_Kib_A.Kd_UPB',$kd_upb);
			$this->db->like('Ta_Kib_A.Tgl_Perolehan',$tahun);
			return $this->db->count_all($this->table);
	} */
	
	/**
	 * pencarian semua data tanah
	 */
	 /*
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
			$like = "AND Ta_KIBAR.$q like '%$s%'";	
		}
		
		$query= $this->db->query("SELECT     urutan,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Prov) AS urutan
		FROM         Ta_KIBAR where Ta_KIBAR.Kd_Bidang=$kd_bidang AND Ta_KIBAR.Kd_Unit=$kd_unit AND Ta_KIBAR.Kd_Sub=$kd_sub AND Ta_KIBAR.Kd_UPB=$kd_upb
		$like) AS Ta_KIBAR INNER JOIN Ref_Rek_Aset5 ON Ta_KIBAR.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIBAR.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIBAR.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
		AND Ta_KIBAR.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIBAR.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 WHERE urutan BETWEEN $first AND $last");				
 		return $query;
		
 	}
	*/
	
	/**
	 * Menampilkan semua data tanah
	 */
	 /*
	function get_all()
	{		
		$this->db->limit('10');
		$this->db->select('Ta_Kib_A.*,Ref_UPB.Nm_UPB');
        $this->db->from('Ta_Kib_A');
        $this->db->join('Ref_UPB', 'Ta_KIBAR.Kd_Prov = Ref_UPB.Kd_Prov AND Ta_KIBAR.Kd_Kab_Kota = Ref_UPB.Kd_Kab_Kota AND Ta_KIBAR.Kd_Bidang = Ref_UPB.Kd_Bidang AND Ta_KIBAR.Kd_Unit = Ref_UPB.Kd_Unit AND Ta_KIBAR.Kd_Sub = Ref_UPB.Kd_Sub AND Ta_KIBAR.Kd_UPB = Ref_UPB.Kd_UPB');
		return $this->db->get();
	}/*
	
	/**
	 * Tampilkan 10 baris absen terkini, diurutkan berdasarkan tanggal (Descending)
	 */
	 /*
	function get_10_data($limit, $offset, $kd_upb)
	{			
        if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }
		
		$kd_bidang	=  $this->session->userdata('kd_bidang');
		$kd_unit	=  $this->session->userdata('kd_unit');
		$kd_sub		=  $this->session->userdata('kd_sub_unit');
		$kd_upb		=  $this->session->userdata('kd_upb');
		$tahun		=  $this->session->userdata('tahun_anggaran');
		
		if ($this->session->userdata('lvl') == 02){
		$x = "where Ta_KIBAR.Kd_Bidang=$kd_bidang AND Ta_KIBAR.Kd_Unit=$kd_unit AND Ta_KIBAR.Kd_Sub=$kd_sub AND Ta_KIBAR.Kd_UPB=$kd_upb
		AND Ta_KIBAR.Tgl_Perolehan like '%$tahun%'";
		}else{
		$x = '';
		}
		
		
		$query= $this->db->query("SELECT     urutan,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Prov) AS urutan
		FROM         Ta_KIBAR $x) AS Ta_KIBAR INNER JOIN Ref_Rek_Aset5 ON Ta_KIBAR.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIBAR.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIBAR.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
		AND Ta_KIBAR.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIBAR.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 WHERE urutan BETWEEN $first AND $last");
							
 		return $query;
		
	}*/
	

	function data_foto($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{	
		$this->db->select('*');
        $this->db->from('Ta_FotoA');
		$this->db->where('Kd_Prov',$kd_prov);
		$this->db->where('Kd_Kab_Kota',$kd_kab);
		$this->db->where('Kd_Bidang',$kd_bidang);
		$this->db->where('Kd_Unit',$kd_unit);
		$this->db->where('Kd_Sub',$kd_sub);
		$this->db->where('Kd_UPB',$kd_upb);
		$this->db->where('Kd_Aset1',$kd_aset1);
		$this->db->where('Kd_Aset2',$kd_aset2);
		$this->db->where('Kd_Aset3',$kd_aset3);
		$this->db->where('Kd_Aset4',$kd_aset4);
		$this->db->where('Kd_Aset5',$kd_aset5);
		$this->db->where('No_Register',$no_register);	
		
		return $this->db->get();
	}
	
	/**
	 * Tampilkan jumlah data
	 */
	 /*
	function get_jumlah_data($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)
	{	
		$this->db->select('*');
        $this->db->from($this->table);
		$this->db->where('Kd_Bidang',$kd_bidang);
		$this->db->where('Kd_Unit',$kd_unit);
		$this->db->where('Kd_Sub',$kd_sub);
		$this->db->where('Kd_UPB',$kd_upb);
		$this->db->like('Tgl_Perolehan',$tahun);
		return $this->db->get();		
	}
	*/
	
	
	/**
	 * Tampilkan total harga
	 */
	 /*
	function total_perupb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)
	{	
		$this->db->select_SUM('Harga');
        $this->db->from($this->table);
		$this->db->where('Kd_Bidang',$kd_bidang);
		$this->db->where('Kd_Unit',$kd_unit);
		$this->db->where('Kd_Sub',$kd_sub);
		$this->db->where('Kd_UPB',$kd_upb);
		$sql = $this->db->get();
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	}
	*/
	
	/**
	 * Tampilkan total harga pencarian
	 */
	 /*
	function total_cari($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$q,$s)
	{	
		$this->db->select_SUM('Harga');
        $this->db->from($this->table);
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
	}*/
	
	/**
	 * Tampilkan jumlah data pencarian
	 */
	 /*
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
	*/
	
	
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
	 * Menambah data tanah
	 */
	function add($kiba)
	{
		$this->db->insert($this->table, $kiba);
		return TRUE;
	}
	
	/**
	 * Menambah data tanah
	 */
	function insert($foto)
	{
		$this->db->insert('Ta_FotoA', $foto);
		return TRUE;
	}
	
	
	/**
	 * Menghapus sebuah data kiba
	 */
	function hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		$this->db->delete($this->table, array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register));
	}
	
	/**
	 * 30-05-2014 Update data KIB
	 */
	function sm_update($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$data)
	{
		$this->db->where('Kd_Prov', $kd_prov);
		$this->db->where('Kd_Kab_Kota', $kd_kab);
		
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
		$this->db->update($this->table, $data);
		
		return TRUE;
		
	}
	
	/**
	 * 08-06-2014 Insert Select
	 */
	function insert_select($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		$query = $this->db->get_where($this->table, array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register), 1)->row();
		
			$data = array(
						'No_Urut'	=> NULL,
						'Kd_Riwayat'	=> 7,
						'Kd_Id'	=> 1,
						'Kd_Prov'	=> $query->Kd_Prov,
						'Kd_Kab_Kota'	=> $query->Kd_Kab_Kota,
						'Kd_Bidang'	=> $query->Kd_Bidang,
						'Kd_Unit'	=> $query->Kd_Unit,
						'Kd_Sub'	=> $query->Kd_Sub,
						'Kd_UPB'	=> $query->Kd_UPB,
						'Kd_Aset1'	=> $query->Kd_Aset1,
						'Kd_Aset2'	=> $query->Kd_Aset2,
						'Kd_Aset3'	=> $query->Kd_Aset3,
						'Kd_Aset4'	=> $query->Kd_Aset4,
						'Kd_Aset5'	=> $query->Kd_Aset5,
						'No_Register'	=> $query->No_Register,
						'Kd_Pemilik'	=> $query->Kd_Pemilik,
						'Tgl_Dokumen'	=> date("Y-m-d"),
						'No_Dokumen'	=> 'sebagian_hapus_sanjaya',
						'Tgl_Perolehan'	=> $query->Tgl_Perolehan,
						'Tgl_Pembukuan'	=> $query->Tgl_Pembukuan,
						'Luas_M2'	=> $query->Luas_M2,
						'Alamat'	=> $query->Alamat,
						'Hak_Tanah'	=> $query->Hak_Tanah,
						'Sertifikat_Tanggal'	=> $query->Sertifikat_Tanggal,
						'Sertifikat_Nomor'	=> $query->Sertifikat_Nomor,
						'Penggunaan'	=> $query->Penggunaan,
						'Asal_usul'	=> $query->Asal_usul,
						'Harga'	=> $query->Harga,
						'Keterangan'	=> $query->Keterangan,
						'Tahun'	=> $query->Tahun,
						'No_SP2D'	=> $query->No_SP2D,
						'No_ID'	=> $query->No_ID,
						'Kd_Kecamatan'	=> $query->Kd_Kecamatan,
						'Kd_Desa'	=> $query->Kd_Desa,
						'Invent'	=> $query->Invent,
						'No_SKGuna'	=> $query->No_SKGuna,
						'Kd_Penyusutan'	=> $query->Kd_Penyusutan,
						'Kd_Data'	=> $query->Kd_Data,
						'Kd_Alasan'	=> 1,
						'Log_User'	=> $query->Log_User,
						'Log_entry'	=> $query->Log_entry,
						'Nm_Rekanan'	=> NULL,
						'Alamat_Reakanan'	=> NULL,
						'Kd_KA'	=> 1);
		
		$this->db->insert("Ta_KIBAR", $data);
		return TRUE;
		
	}
	
	
	/* 28-03-2014 tambah total kiba*/
	function total_kiba($where)
	{	
		$query= "SELECT   SUM(Harga) AS Harga FROM  Ta_KIBAR INNER JOIN Ref_Rek_Aset5 ON 
Ta_KIBAR.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIBAR.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
AND Ta_KIBAR.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIBAR.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
AND Ta_KIBAR.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5  WHERE $where AND
Ta_KIBAR.Kd_Aset1 = '1'";
			
		$sql = $this->db->query($query);
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	}
	
		
	
}

/* End of file Contoh_model.php */
/* Location: ./system/application/models/Contoh_model.php */