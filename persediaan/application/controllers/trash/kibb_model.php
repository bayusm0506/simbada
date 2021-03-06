<?php
/**
 * Contoh_model Class
 */
class Kibb_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
	/* Inisialisasi nama tabel yang digunakan */
	var $table = 'Ta_KIB_B';
	
	
	function get_page($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

		$query= $this->db->query("SELECT    * FROM  (
SELECT     Ref_UPB.Nm_UPB,Ta_KIB_B.*,Ref_Rek_Aset5.Nm_Aset5, ROW_NUMBER() OVER (ORDER BY Ta_KIB_B.Kd_Prov) AS 
no_urut		FROM  Ta_KIB_B INNER JOIN Ref_Rek_Aset5 ON 
Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
AND Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 LEFT JOIN Ref_UPB ON Ta_KIB_B.Kd_Bidang=Ref_UPB.Kd_Bidang AND Ta_KIB_B.Kd_Unit=Ref_UPB.Kd_Unit AND
Ta_KIB_B.Kd_Sub=Ref_UPB.Kd_Sub AND Ta_KIB_B.Kd_UPB=Ref_UPB.Kd_UPB
   WHERE $where $like AND
Ta_KIB_B.Kd_Aset1 = '1') AS Ta_KIB_B WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC");				
 		return $query;
 	}
	
	/* count kib b total page */
	function count_kib($where, $like) {
 		return $this->db->query("SELECT     * FROM (SELECT     *	FROM   Ta_KIB_B WHERE $where AND
Ta_KIB_B.Kd_Aset1 = '1' ) AS 
Ta_KIB_B INNER JOIN Ref_Rek_Aset5 ON Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND 
Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND 
Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 $like");	
    }
	
	
	/**
	 * Tampilkan total harga kib b
	 */
	function total_kib($where, $like)
	{		
		$query= "SELECT   SUM(Harga) AS Harga FROM  Ta_KIB_B INNER JOIN Ref_Rek_Aset5 ON 
Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
AND Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5  WHERE $where $like AND
Ta_KIB_B.Kd_Aset1 = '1'";
			
		$sql = $this->db->query($query);
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	}
	
	/**
	 * Menampilkan data laporan Kibb
	 */
	function laporan_Kibb($bidang,$unit,$sub,$upb,$like)
	{	
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "(Ta_KIB_B.Kd_Bidang = $bidang) 
			  AND (Ta_KIB_B.Kd_Unit = $unit) 
			  AND (Ta_KIB_B.Kd_Sub = $sub) 
			  AND (Ta_KIB_B.Kd_UPB = $upb) AND";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIB_B.Kd_Bidang = $kb )
			  AND (Ta_KIB_B.Kd_Unit =$ku )
			  AND (Ta_KIB_B.Kd_Sub = $ks) 
			  AND (Ta_KIB_B.Kd_UPB = $upb) AND";
		}else{
			$where = "(Ta_KIB_B.Kd_Bidang = $kb) 
			  AND (Ta_KIB_B.Kd_Unit = $ku) 
			  AND (Ta_KIB_B.Kd_Sub = $ks) 
			  AND (Ta_KIB_B.Kd_UPB = $kupb) AND";
		}

		$query = "select * from Ta_KIB_B inner join Ref_Rek_Aset5 on 
Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
Ta_KIB_B.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND 
Ta_KIB_B.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
Ta_KIB_B.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
Ta_KIB_B.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 WHERE $where $like";
		return $this->db->query($query);
	}
	
	
	/**
	 * Tampilkan total harga laporan Kibb
	 */
	function total_laporan_Kibb($bidang,$unit,$sub,$upb,$like)
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
	function Kibb_mutasi($bidang,$unit,$sub,$upb,$like)
	{			
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "(Ta_KIB_B.Kd_Bidang = $bidang) 
			  AND (Ta_KIB_B.Kd_Unit = $unit) 
			  AND (Ta_KIB_B.Kd_Sub = $sub) 
			  AND (Ta_KIB_B.Kd_UPB = $upb) AND";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIB_B.Kd_Bidang = $kb )
			  AND (Ta_KIB_B.Kd_Unit =$ku )
			  AND (Ta_KIB_B.Kd_Sub = $ks) 
			  AND (Ta_KIB_B.Kd_UPB = $upb) AND";
		}else{
			$where = "(Ta_KIB_B.Kd_Bidang = $kb) 
			  AND (Ta_KIB_B.Kd_Unit = $ku) 
			  AND (Ta_KIB_B.Kd_Sub = $ks) 
			  AND (Ta_KIB_B.Kd_UPB = $kupb) AND";
		}

		$query = "SELECT   Ref_Rek_Aset5.Nm_Aset5,COUNT(Ta_KIB_B.No_register) as jumlah_register,MIN(Ta_KIB_B.No_register) as min_register,
MAX(Ta_KIB_B.No_register) as max_register,
Ta_KIB_B.Kd_Prov, Ta_KIB_B.Kd_Kab_Kota, Ta_KIB_B.Kd_Bidang, Ta_KIB_B.Kd_Unit,
Ta_KIB_B.Kd_Sub, Ta_KIB_B.Kd_UPB, Ta_KIB_B.Kd_Aset1, Ta_KIB_B.Kd_Aset2, Ta_KIB_B.Kd_Aset3, 
Ta_KIB_B.Kd_Aset4, Ta_KIB_B.Kd_Aset5, Ta_KIB_B.Kd_Pemilik,DATENAME(yyyy,Ta_KIB_B.Tgl_Perolehan) as Tahun, Ta_KIB_B.Luas_M2, 
Ta_KIB_B.Alamat, Ta_KIB_B.Hak_Tanah, Ta_KIB_B.Sertifikat_Tanggal,Ta_KIB_B.Sertifikat_Nomor,
Ta_KIB_B.Penggunaan,Ta_KIB_B.Asal_usul,COUNT(*) as Jumlah,
SUM(Ta_KIB_B.Harga) as Harga,Ta_KIB_B.Keterangan

FROM  Ta_KIB_B INNER JOIN
Ref_Rek_Aset5 ON Ta_KIB_B.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5

WHERE  $where $like

GROUP BY Ta_KIB_B.Kd_Prov, Ta_KIB_B.Kd_Kab_Kota, Ta_KIB_B.Kd_Bidang, Ta_KIB_B.Kd_Unit,
Ta_KIB_B.Kd_Sub, Ta_KIB_B.Kd_UPB, Ta_KIB_B.Kd_Aset1, Ta_KIB_B.Kd_Aset2, Ta_KIB_B.Kd_Aset3, 
Ta_KIB_B.Kd_Aset4, Ta_KIB_B.Kd_Aset5, Ta_KIB_B.Kd_Pemilik, Ta_KIB_B.Kd_Pemilik,Ta_KIB_B.Tgl_Perolehan, Ta_KIB_B.Luas_M2, 
Ta_KIB_B.Alamat, Ta_KIB_B.Hak_Tanah, Ta_KIB_B.Sertifikat_Tanggal,Ta_KIB_B.Sertifikat_Nomor,
Ta_KIB_B.Penggunaan,Ta_KIB_B.Asal_usul,Ta_KIB_B.Keterangan,Ref_Rek_Aset5.Nm_Aset5 ORDER BY  Ref_Rek_Aset5.Nm_Aset5, MIN(Ta_KIB_B.No_register)";
		return $this->db->query($query);
	}
	
	
	/**
	 * Total Data Mutasi
	 */
	function total_Kibb_mutasi($bidang,$unit,$sub,$upb,$like)
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
		$where = "(Ta_KIB_B.Kd_Bidang = $bidang) 
			  AND (Ta_KIB_B.Kd_Unit = $unit) 
			  AND (Ta_KIB_B.Kd_Sub = $sub) 
			  AND (Ta_KIB_B.Kd_UPB = $upb)";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIB_B.Kd_Bidang = $kb )
			  AND (Ta_KIB_B.Kd_Unit =$ku )
			  AND (Ta_KIB_B.Kd_Sub = $ks) 
			  AND (Ta_KIB_B.Kd_UPB = $upb)";
		}else{
			$where = "(Ta_KIB_B.Kd_Bidang = $kb) 
			  AND (Ta_KIB_B.Kd_Unit = $ku) 
			  AND (Ta_KIB_B.Kd_Sub = $ks) 
			  AND (Ta_KIB_B.Kd_UPB = $kupb)";
		}
		$query = "select Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2,
COUNT(case when Tgl_Perolehan < '$awal' then 1 else null end) as Jumlah_awal,
SUM(CASE WHEN Tgl_Perolehan < '$awal'  THEN Harga END) as Harga_awal,
COUNT(case WHEN $like then 0 else null end) as Jumlah_tambah,
SUM(CASE WHEN $like  THEN Harga END) as Harga_tambah,
COUNT(*) as Jumlah, 
SUM(Harga) as Harga
 from Ref_Rek_Aset2 LEFT JOIN (SELECT * from Ta_KIB_B WHERE $where $akhir) as Ta_KIB_B ON 
Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND
Ta_KIB_B.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2   WHERE Ref_Rek_Aset2.Kd_Aset1=1
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
		$where = "(Ta_KIB_B.Kd_Bidang = $bidang) 
			  AND (Ta_KIB_B.Kd_Unit = $unit) 
			  AND (Ta_KIB_B.Kd_Sub = $sub) 
			  AND (Ta_KIB_B.Kd_UPB = $upb)";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIB_B.Kd_Bidang = $kb )
			  AND (Ta_KIB_B.Kd_Unit =$ku )
			  AND (Ta_KIB_B.Kd_Sub = $ks) 
			  AND (Ta_KIB_B.Kd_UPB = $upb)";
		}else{
			$where = "(Ta_KIB_B.Kd_Bidang = $kb) 
			  AND (Ta_KIB_B.Kd_Unit = $ku) 
			  AND (Ta_KIB_B.Kd_Sub = $ks) 
			  AND (Ta_KIB_B.Kd_UPB = $kupb)";
		}
		$query = "SELECT COUNT(case when Tgl_Perolehan < '$awal' then 1 else null end) as Jumlah_awal,
SUM(CASE WHEN Tgl_Perolehan < '$awal'  THEN Harga END) as Harga_awal,
COUNT(case WHEN $like then 0 else null end) as Jumlah_tambah,
SUM(CASE WHEN $like  THEN Harga END) as Harga_tambah,
COUNT(*) as Jumlah, 
SUM(Harga) as Harga
 from Ref_Rek_Aset2 LEFT JOIN (SELECT * from Ta_KIB_B WHERE $where $akhir) as Ta_KIB_B ON 
Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND
Ta_KIB_B.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2   WHERE Ref_Rek_Aset2.Kd_Aset1=1 ";
		return $this->db->query($query);
	}
	
	/**
	 * Menampilkan data kib A Buku Induk SKPD
	 */
	function Kibb_buku_induk($like)
	{			
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');

		if ($this->session->userdata('lvl') == 01){
			$where = '';
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIB_B.Kd_Bidang = $kb )
			  AND (Ta_KIB_B.Kd_Unit =$ku )
			  AND (Ta_KIB_B.Kd_Sub = $ks) AND";
		}else{
			$where = "(Ta_KIB_B.Kd_Bidang = $kb) 
			  AND (Ta_KIB_B.Kd_Unit = $ku) 
			  AND (Ta_KIB_B.Kd_Sub = $ks) 
			  AND (Ta_KIB_B.Kd_UPB = $kupb) AND";
		}

		$query = "SELECT   Nm_UPB,Ref_Rek_Aset5.Nm_Aset5,COUNT(Ta_KIB_B.No_register) as jumlah_register,MIN(Ta_KIB_B.No_register) as min_register,
	MAX(Ta_KIB_B.No_register) as max_register,
	Ta_KIB_B.Kd_Prov, Ta_KIB_B.Kd_Kab_Kota, Ta_KIB_B.Kd_Bidang, Ta_KIB_B.Kd_Unit,
	Ta_KIB_B.Kd_Sub, Ta_KIB_B.Kd_UPB, Ta_KIB_B.Kd_Aset1, Ta_KIB_B.Kd_Aset2, Ta_KIB_B.Kd_Aset3, 
	Ta_KIB_B.Kd_Aset4, Ta_KIB_B.Kd_Aset5, Ta_KIB_B.Kd_Pemilik,DATENAME(yyyy,Ta_KIB_B.Tgl_Perolehan) as Tahun, Ta_KIB_B.Luas_M2, 
	Ta_KIB_B.Alamat, Ta_KIB_B.Hak_Tanah, Ta_KIB_B.Sertifikat_Tanggal,Ta_KIB_B.Sertifikat_Nomor,
	Ta_KIB_B.Penggunaan,Ta_KIB_B.Asal_usul,COUNT(*) as Jumlah,
	SUM(Ta_KIB_B.Harga) as Harga,Ta_KIB_B.Keterangan
	
	FROM  Ta_KIB_B INNER JOIN
	Ref_Rek_Aset5 ON Ta_KIB_B.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
	INNER JOIN Ref_UPB ON Ta_KIB_B.Kd_Bidang=Ref_UPB.Kd_Bidang AND Ta_KIB_B.Kd_Unit=Ref_UPB.Kd_Unit AND Ta_KIB_B.Kd_Sub=Ref_UPB.Kd_Sub AND Ta_KIB_B.Kd_UPB=Ref_UPB.Kd_UPB
	
	WHERE  $where $like
	
	GROUP BY Ta_KIB_B.Kd_Prov, Ta_KIB_B.Kd_Kab_Kota, Ta_KIB_B.Kd_Bidang, Ta_KIB_B.Kd_Unit,
	Ta_KIB_B.Kd_Sub, Ta_KIB_B.Kd_UPB, Ta_KIB_B.Kd_Aset1, Ta_KIB_B.Kd_Aset2, Ta_KIB_B.Kd_Aset3, 
	Ta_KIB_B.Kd_Aset4, Ta_KIB_B.Kd_Aset5, Ta_KIB_B.Kd_Pemilik, Ta_KIB_B.Kd_Pemilik,Ta_KIB_B.Tgl_Perolehan, Ta_KIB_B.Luas_M2, 
	Ta_KIB_B.Alamat, Ta_KIB_B.Hak_Tanah, Ta_KIB_B.Sertifikat_Tanggal,Ta_KIB_B.Sertifikat_Nomor,
	Ta_KIB_B.Penggunaan,Ta_KIB_B.Asal_usul,Ta_KIB_B.Keterangan,Ref_Rek_Aset5.Nm_Aset5,Nm_UPB ORDER BY  Nm_UPB,Ref_Rek_Aset5.Nm_Aset5, MIN(Ta_KIB_B.No_register)";
		return $this->db->query($query);
	}
	
	
	/**
	 * Total Data buku induk skpd
	 */
	function total_Kibb_buku_induk($like)
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
	function Kibb_rinventaris($bidang,$unit,$sub,$upb,$like)
	{	
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "WHERE (Ta_KIB_B.Kd_Bidang = $bidang) 
			  AND (Ta_KIB_B.Kd_Unit = $unit) 
			  AND (Ta_KIB_B.Kd_Sub = $sub) 
			  AND (Ta_KIB_B.Kd_UPB = $upb) AND $like";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "WHERE (Ta_KIB_B.Kd_Bidang = $kb )
			  AND (Ta_KIB_B.Kd_Unit =$ku )
			  AND (Ta_KIB_B.Kd_Sub = $ks) 
			  AND (Ta_KIB_B.Kd_UPB = $upb) AND $like";
		}else{
			$where = "WHERE (Ta_KIB_B.Kd_Bidang = $kb) 
			  AND (Ta_KIB_B.Kd_Unit = $ku) 
			  AND (Ta_KIB_B.Kd_Sub = $ks) 
			  AND (Ta_KIB_B.Kd_UPB = $kupb) AND $like";
		}
		
$query = "select Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2,COUNT(*) as Jumlah, SUM(Harga) as Harga from Ref_Rek_Aset2 LEFT JOIN (SELECT * from Ta_KIB_B $where) as Ta_KIB_B ON 
Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND
Ta_KIB_B.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2   WHERE Ref_Rek_Aset2.Kd_Aset1=1
GROUP BY Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2 ";
		return $this->db->query($query);
	}
	
	/**
	 * Total Data rekap inventaris
	 */
	function total_Kibb_rinventaris($bidang,$unit,$sub,$upb,$like)
	{	
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "WHERE (Ta_KIB_B.Kd_Bidang = $bidang) 
			  AND (Ta_KIB_B.Kd_Unit = $unit) 
			  AND (Ta_KIB_B.Kd_Sub = $sub) 
			  AND (Ta_KIB_B.Kd_UPB = $upb) AND $like";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "WHERE (Ta_KIB_B.Kd_Bidang = $kb )
			  AND (Ta_KIB_B.Kd_Unit =$ku )
			  AND (Ta_KIB_B.Kd_Sub = $ks) 
			  AND (Ta_KIB_B.Kd_UPB = $upb) AND $like";
		}else{
			$where = "WHERE (Ta_KIB_B.Kd_Bidang = $kb) 
			  AND (Ta_KIB_B.Kd_Unit = $ku) 
			  AND (Ta_KIB_B.Kd_Sub = $ks) 
			  AND (Ta_KIB_B.Kd_UPB = $kupb) AND $like";
		}
		
		$query = "SELECT COUNT(*) as Jumlah,SUM(Harga) as Harga FROM Ta_KIB_B $where";
		
        return $this->db->query($query);
	}
	
	/**
	 * Menampilkan data kib a rekap gabungan pemda
	 */
	function Kibb_rekap($kd_bidang,$kd_unit,$kd_sub_unit,$like)
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
	function total_Kibb_rekap($like)
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
	function now_data_Kibb($bidang,$unit,$sub,$upb,$like)
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
		
		$this->db->select('Ta_KIB_B.*,Ref_Rek_Aset5.Nm_Aset5');
        $this->db->from('Ta_KIB_B');
		$this->db->order_by('Tgl_Perolehan', 'ASC');
        $this->db->join('Ref_Rek_Aset5', 'Ta_KIB_B.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5');
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
	 * Mendapatkan data sebuah Kibb
	 */
	function get_Kibb_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
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
	function daTa_KIB_B($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)
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
			$this->db->where('Ta_KIB_B.Kd_Bidang',$kd_bidang);
			$this->db->where('Ta_KIB_B.Kd_Unit',$kd_unit);
			$this->db->where('Ta_KIB_B.Kd_Sub',$kd_sub);
			$this->db->where('Ta_KIB_B.Kd_UPB',$kd_upb);
			$this->db->like('Ta_KIB_B.Tgl_Perolehan',$tahun);
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
			$like = "AND Ta_KIB_B.$q like '%$s%'";	
		}
		
		$query= $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Prov) AS no_urut
		FROM         Ta_KIB_B where Ta_KIB_B.Kd_Bidang=$kd_bidang AND Ta_KIB_B.Kd_Unit=$kd_unit AND Ta_KIB_B.Kd_Sub=$kd_sub AND Ta_KIB_B.Kd_UPB=$kd_upb
		$like) AS Ta_KIB_B INNER JOIN Ref_Rek_Aset5 ON Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
		AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 WHERE no_urut BETWEEN $first AND $last");				
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
		$this->db->select('Ta_KIB_B.*,Ref_UPB.Nm_UPB');
        $this->db->from('Ta_KIB_B');
        $this->db->join('Ref_UPB', 'Ta_KIB_B.Kd_Prov = Ref_UPB.Kd_Prov AND Ta_KIB_B.Kd_Kab_Kota = Ref_UPB.Kd_Kab_Kota AND Ta_KIB_B.Kd_Bidang = Ref_UPB.Kd_Bidang AND Ta_KIB_B.Kd_Unit = Ref_UPB.Kd_Unit AND Ta_KIB_B.Kd_Sub = Ref_UPB.Kd_Sub AND Ta_KIB_B.Kd_UPB = Ref_UPB.Kd_UPB');
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
		$x = "where Ta_KIB_B.Kd_Bidang=$kd_bidang AND Ta_KIB_B.Kd_Unit=$kd_unit AND Ta_KIB_B.Kd_Sub=$kd_sub AND Ta_KIB_B.Kd_UPB=$kd_upb
		AND Ta_KIB_B.Tgl_Perolehan like '%$tahun%'";
		}else{
		$x = '';
		}
		
		
		$query= $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Prov) AS no_urut
		FROM         Ta_KIB_B $x) AS Ta_KIB_B INNER JOIN Ref_Rek_Aset5 ON Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
		AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 WHERE no_urut BETWEEN $first AND $last");
							
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
	function add($Kibb)
	{
		$this->db->insert($this->table, $Kibb);
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
	 * Menghapus sebuah data Kibb
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
	
	
	/* 28-03-2014 tambah total Kibb*/
	function total_Kibb($where)
	{	
		$query= "SELECT   SUM(Harga) AS Harga FROM  Ta_KIB_B INNER JOIN Ref_Rek_Aset5 ON 
Ta_KIB_B.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
AND Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5  WHERE $where AND
Ta_KIB_B.Kd_Aset1 = '1'";
			
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