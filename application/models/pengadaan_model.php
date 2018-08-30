<?php
/**
 * Contoh_model Class
 */
class Pengadaan_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
	/* Inisialisasi nama tabel yang digunakan */
	var $table = 'Tb_Pengadaan';
	
	
	function get_page($limit, $offset, $where='', $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT    * FROM  (
			SELECT     Ref_Rek_Aset5.Nm_Aset5,Ref_UPB.Nm_UPB,Tb_Pengadaan.*, ROW_NUMBER() OVER (ORDER BY Tb_Pengadaan.Log_entry DESC) AS 
			urutan		FROM  Tb_Pengadaan LEFT JOIN Ref_UPB ON Tb_Pengadaan.Kd_Bidang=Ref_UPB.Kd_Bidang AND 
			Tb_Pengadaan.Kd_Unit=Ref_UPB.Kd_Unit AND Tb_Pengadaan.Kd_Sub=Ref_UPB.Kd_Sub AND Tb_Pengadaan.Kd_UPB=Ref_UPB.Kd_UPB
			LEFT JOIN Ref_Rek_Aset5 ON Tb_Pengadaan.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Tb_Pengadaan.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Tb_Pengadaan.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Tb_Pengadaan.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Tb_Pengadaan.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
			    WHERE $where $like) AS Tb_Pengadaan WHERE 1=1";
		$ret['total'] = $this->db->query($sql)->num_rows();
		$sql .= "AND urutan BETWEEN $first AND $last ORDER BY Log_entry DESC";		
		$ret['data']  = $this->db->query($sql)->result();		
 		return $ret;
 	}
	
	/**
	 * Tampilkan total harga kib b
	 */
	function total_harga($where, $like)
	{	
		$query ="SELECT SUM(CONVERT(bigint,Harga*Jumlah)) as Harga FROM Tb_Pengadaan 
				LEFT JOIN Ref_UPB ON Tb_Pengadaan.Kd_Bidang=Ref_UPB.Kd_Bidang 
				AND Tb_Pengadaan.Kd_Unit=Ref_UPB.Kd_Unit AND Tb_Pengadaan.Kd_Sub=Ref_UPB.Kd_Sub 
				AND Tb_Pengadaan.Kd_UPB=Ref_UPB.Kd_UPB 
				LEFT JOIN Ref_Rek_Aset5 ON Tb_Pengadaan.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 
				AND Tb_Pengadaan.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
				AND Tb_Pengadaan.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
				AND Tb_Pengadaan.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
				AND Tb_Pengadaan.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 
				WHERE $where $like";

		// print_r($query); exit();
			
		$result = $this->db->query($query)->row();
		
		return $result->Harga;
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
		$where = "(Tb_Pengadaan.Kd_Bidang = $bidang) 
			  AND (Tb_Pengadaan.Kd_Unit = $unit) 
			  AND (Tb_Pengadaan.Kd_Sub = $sub) 
			  AND (Tb_Pengadaan.Kd_UPB = $upb) AND";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Tb_Pengadaan.Kd_Bidang = $kb )
			  AND (Tb_Pengadaan.Kd_Unit =$ku )
			  AND (Tb_Pengadaan.Kd_Sub = $ks) 
			  AND (Tb_Pengadaan.Kd_UPB = $upb) AND";
		}else{
			$where = "(Tb_Pengadaan.Kd_Bidang = $kb) 
			  AND (Tb_Pengadaan.Kd_Unit = $ku) 
			  AND (Tb_Pengadaan.Kd_Sub = $ks) 
			  AND (Tb_Pengadaan.Kd_UPB = $kupb) AND";
		}

		$query = "select * from Tb_Pengadaan inner join Ref_Rek_Aset5 on 
				Tb_Pengadaan.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
				Tb_Pengadaan.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND 
				Tb_Pengadaan.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
				Tb_Pengadaan.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
				Tb_Pengadaan.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 WHERE $where $like";
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
		$where = "(Tb_Pengadaan.Kd_Bidang = $bidang) 
			  AND (Tb_Pengadaan.Kd_Unit = $unit) 
			  AND (Tb_Pengadaan.Kd_Sub = $sub) 
			  AND (Tb_Pengadaan.Kd_UPB = $upb) AND";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Tb_Pengadaan.Kd_Bidang = $kb )
			  AND (Tb_Pengadaan.Kd_Unit =$ku )
			  AND (Tb_Pengadaan.Kd_Sub = $ks) 
			  AND (Tb_Pengadaan.Kd_UPB = $upb) AND";
		}else{
			$where = "(Tb_Pengadaan.Kd_Bidang = $kb) 
			  AND (Tb_Pengadaan.Kd_Unit = $ku) 
			  AND (Tb_Pengadaan.Kd_Sub = $ks) 
			  AND (Tb_Pengadaan.Kd_UPB = $kupb) AND";
		}

		$query = "SELECT   Ref_Rek_Aset5.Nm_Aset5,COUNT(Tb_Pengadaan.No_register) as jumlah_register,MIN(Tb_Pengadaan.No_register) as min_register,
MAX(Tb_Pengadaan.No_register) as max_register,
Tb_Pengadaan.Kd_Prov, Tb_Pengadaan.Kd_Kab_Kota, Tb_Pengadaan.Kd_Bidang, Tb_Pengadaan.Kd_Unit,
Tb_Pengadaan.Kd_Sub, Tb_Pengadaan.Kd_UPB, Tb_Pengadaan.Kd_Aset1, Tb_Pengadaan.Kd_Aset2, Tb_Pengadaan.Kd_Aset3, 
Tb_Pengadaan.Kd_Aset4, Tb_Pengadaan.Kd_Aset5, Tb_Pengadaan.Kd_Pemilik,DATENAME(yyyy,Tb_Pengadaan.Tgl_Perolehan) as Tahun, Tb_Pengadaan.Luas_M2, 
Tb_Pengadaan.Alamat, Tb_Pengadaan.Hak_Tanah, Tb_Pengadaan.Sertifikat_Tanggal,Tb_Pengadaan.Sertifikat_Nomor,
Tb_Pengadaan.Penggunaan,Tb_Pengadaan.Asal_usul,COUNT(*) as Jumlah,
SUM(Tb_Pengadaan.Harga) as Harga,Tb_Pengadaan.Keterangan

FROM  Tb_Pengadaan INNER JOIN
Ref_Rek_Aset5 ON Tb_Pengadaan.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Tb_Pengadaan.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Tb_Pengadaan.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Tb_Pengadaan.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Tb_Pengadaan.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5

WHERE  $where $like

GROUP BY Tb_Pengadaan.Kd_Prov, Tb_Pengadaan.Kd_Kab_Kota, Tb_Pengadaan.Kd_Bidang, Tb_Pengadaan.Kd_Unit,
Tb_Pengadaan.Kd_Sub, Tb_Pengadaan.Kd_UPB, Tb_Pengadaan.Kd_Aset1, Tb_Pengadaan.Kd_Aset2, Tb_Pengadaan.Kd_Aset3, 
Tb_Pengadaan.Kd_Aset4, Tb_Pengadaan.Kd_Aset5, Tb_Pengadaan.Kd_Pemilik, Tb_Pengadaan.Kd_Pemilik,Tb_Pengadaan.Tgl_Perolehan, Tb_Pengadaan.Luas_M2, 
Tb_Pengadaan.Alamat, Tb_Pengadaan.Hak_Tanah, Tb_Pengadaan.Sertifikat_Tanggal,Tb_Pengadaan.Sertifikat_Nomor,
Tb_Pengadaan.Penggunaan,Tb_Pengadaan.Asal_usul,Tb_Pengadaan.Keterangan,Ref_Rek_Aset5.Nm_Aset5 ORDER BY  Ref_Rek_Aset5.Nm_Aset5, MIN(Tb_Pengadaan.No_register)";
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
		$where = "(Tb_Pengadaan.Kd_Bidang = $bidang) 
			  AND (Tb_Pengadaan.Kd_Unit = $unit) 
			  AND (Tb_Pengadaan.Kd_Sub = $sub) 
			  AND (Tb_Pengadaan.Kd_UPB = $upb)";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Tb_Pengadaan.Kd_Bidang = $kb )
			  AND (Tb_Pengadaan.Kd_Unit =$ku )
			  AND (Tb_Pengadaan.Kd_Sub = $ks) 
			  AND (Tb_Pengadaan.Kd_UPB = $upb)";
		}else{
			$where = "(Tb_Pengadaan.Kd_Bidang = $kb) 
			  AND (Tb_Pengadaan.Kd_Unit = $ku) 
			  AND (Tb_Pengadaan.Kd_Sub = $ks) 
			  AND (Tb_Pengadaan.Kd_UPB = $kupb)";
		}
		$query = "select Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2,
COUNT(case when Tgl_Perolehan < '$awal' then 1 else null end) as Jumlah_awal,
SUM(CASE WHEN Tgl_Perolehan < '$awal'  THEN Harga END) as Harga_awal,
COUNT(case WHEN $like then 0 else null end) as Jumlah_tambah,
SUM(CASE WHEN $like  THEN Harga END) as Harga_tambah,
COUNT(*) as Jumlah, 
SUM(Harga) as Harga
 from Ref_Rek_Aset2 LEFT JOIN (SELECT * from Tb_Pengadaan WHERE $where $akhir) as Tb_Pengadaan ON 
Tb_Pengadaan.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND
Tb_Pengadaan.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2   WHERE Ref_Rek_Aset2.Kd_Aset1=1
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
		$where = "(Tb_Pengadaan.Kd_Bidang = $bidang) 
			  AND (Tb_Pengadaan.Kd_Unit = $unit) 
			  AND (Tb_Pengadaan.Kd_Sub = $sub) 
			  AND (Tb_Pengadaan.Kd_UPB = $upb)";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Tb_Pengadaan.Kd_Bidang = $kb )
			  AND (Tb_Pengadaan.Kd_Unit =$ku )
			  AND (Tb_Pengadaan.Kd_Sub = $ks) 
			  AND (Tb_Pengadaan.Kd_UPB = $upb)";
		}else{
			$where = "(Tb_Pengadaan.Kd_Bidang = $kb) 
			  AND (Tb_Pengadaan.Kd_Unit = $ku) 
			  AND (Tb_Pengadaan.Kd_Sub = $ks) 
			  AND (Tb_Pengadaan.Kd_UPB = $kupb)";
		}
		$query = "SELECT COUNT(case when Tgl_Perolehan < '$awal' then 1 else null end) as Jumlah_awal,
SUM(CASE WHEN Tgl_Perolehan < '$awal'  THEN Harga END) as Harga_awal,
COUNT(case WHEN $like then 0 else null end) as Jumlah_tambah,
SUM(CASE WHEN $like  THEN Harga END) as Harga_tambah,
COUNT(*) as Jumlah, 
SUM(Harga) as Harga
 from Ref_Rek_Aset2 LEFT JOIN (SELECT * from Tb_Pengadaan WHERE $where $akhir) as Tb_Pengadaan ON 
Tb_Pengadaan.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND
Tb_Pengadaan.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2   WHERE Ref_Rek_Aset2.Kd_Aset1=1 ";
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
			$where = "(Tb_Pengadaan.Kd_Bidang = $kb )
			  AND (Tb_Pengadaan.Kd_Unit =$ku )
			  AND (Tb_Pengadaan.Kd_Sub = $ks) AND";
		}else{
			$where = "(Tb_Pengadaan.Kd_Bidang = $kb) 
			  AND (Tb_Pengadaan.Kd_Unit = $ku) 
			  AND (Tb_Pengadaan.Kd_Sub = $ks) 
			  AND (Tb_Pengadaan.Kd_UPB = $kupb) AND";
		}

		$query = "SELECT   Nm_UPB,Ref_Rek_Aset5.Nm_Aset5,COUNT(Tb_Pengadaan.No_register) as jumlah_register,MIN(Tb_Pengadaan.No_register) as min_register,
	MAX(Tb_Pengadaan.No_register) as max_register,
	Tb_Pengadaan.Kd_Prov, Tb_Pengadaan.Kd_Kab_Kota, Tb_Pengadaan.Kd_Bidang, Tb_Pengadaan.Kd_Unit,
	Tb_Pengadaan.Kd_Sub, Tb_Pengadaan.Kd_UPB, Tb_Pengadaan.Kd_Aset1, Tb_Pengadaan.Kd_Aset2, Tb_Pengadaan.Kd_Aset3, 
	Tb_Pengadaan.Kd_Aset4, Tb_Pengadaan.Kd_Aset5, Tb_Pengadaan.Kd_Pemilik,DATENAME(yyyy,Tb_Pengadaan.Tgl_Perolehan) as Tahun, Tb_Pengadaan.Luas_M2, 
	Tb_Pengadaan.Alamat, Tb_Pengadaan.Hak_Tanah, Tb_Pengadaan.Sertifikat_Tanggal,Tb_Pengadaan.Sertifikat_Nomor,
	Tb_Pengadaan.Penggunaan,Tb_Pengadaan.Asal_usul,COUNT(*) as Jumlah,
	SUM(Tb_Pengadaan.Harga) as Harga,Tb_Pengadaan.Keterangan
	
	FROM  Tb_Pengadaan INNER JOIN
	Ref_Rek_Aset5 ON Tb_Pengadaan.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Tb_Pengadaan.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Tb_Pengadaan.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Tb_Pengadaan.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Tb_Pengadaan.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
	INNER JOIN Ref_UPB ON Tb_Pengadaan.Kd_Bidang=Ref_UPB.Kd_Bidang AND Tb_Pengadaan.Kd_Unit=Ref_UPB.Kd_Unit AND Tb_Pengadaan.Kd_Sub=Ref_UPB.Kd_Sub AND Tb_Pengadaan.Kd_UPB=Ref_UPB.Kd_UPB
	
	WHERE  $where $like
	
	GROUP BY Tb_Pengadaan.Kd_Prov, Tb_Pengadaan.Kd_Kab_Kota, Tb_Pengadaan.Kd_Bidang, Tb_Pengadaan.Kd_Unit,
	Tb_Pengadaan.Kd_Sub, Tb_Pengadaan.Kd_UPB, Tb_Pengadaan.Kd_Aset1, Tb_Pengadaan.Kd_Aset2, Tb_Pengadaan.Kd_Aset3, 
	Tb_Pengadaan.Kd_Aset4, Tb_Pengadaan.Kd_Aset5, Tb_Pengadaan.Kd_Pemilik, Tb_Pengadaan.Kd_Pemilik,Tb_Pengadaan.Tgl_Perolehan, Tb_Pengadaan.Luas_M2, 
	Tb_Pengadaan.Alamat, Tb_Pengadaan.Hak_Tanah, Tb_Pengadaan.Sertifikat_Tanggal,Tb_Pengadaan.Sertifikat_Nomor,
	Tb_Pengadaan.Penggunaan,Tb_Pengadaan.Asal_usul,Tb_Pengadaan.Keterangan,Ref_Rek_Aset5.Nm_Aset5,Nm_UPB ORDER BY  Nm_UPB,Ref_Rek_Aset5.Nm_Aset5, MIN(Tb_Pengadaan.No_register)";
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
		$where = "WHERE (Tb_Pengadaan.Kd_Bidang = $bidang) 
			  AND (Tb_Pengadaan.Kd_Unit = $unit) 
			  AND (Tb_Pengadaan.Kd_Sub = $sub) 
			  AND (Tb_Pengadaan.Kd_UPB = $upb) AND $like";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "WHERE (Tb_Pengadaan.Kd_Bidang = $kb )
			  AND (Tb_Pengadaan.Kd_Unit =$ku )
			  AND (Tb_Pengadaan.Kd_Sub = $ks) 
			  AND (Tb_Pengadaan.Kd_UPB = $upb) AND $like";
		}else{
			$where = "WHERE (Tb_Pengadaan.Kd_Bidang = $kb) 
			  AND (Tb_Pengadaan.Kd_Unit = $ku) 
			  AND (Tb_Pengadaan.Kd_Sub = $ks) 
			  AND (Tb_Pengadaan.Kd_UPB = $kupb) AND $like";
		}
		
$query = "select Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2,COUNT(*) as Jumlah, SUM(Harga) as Harga from Ref_Rek_Aset2 LEFT JOIN (SELECT * from Tb_Pengadaan $where) as Tb_Pengadaan ON 
Tb_Pengadaan.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND
Tb_Pengadaan.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2   WHERE Ref_Rek_Aset2.Kd_Aset1=1
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
		$where = "WHERE (Tb_Pengadaan.Kd_Bidang = $bidang) 
			  AND (Tb_Pengadaan.Kd_Unit = $unit) 
			  AND (Tb_Pengadaan.Kd_Sub = $sub) 
			  AND (Tb_Pengadaan.Kd_UPB = $upb) AND $like";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "WHERE (Tb_Pengadaan.Kd_Bidang = $kb )
			  AND (Tb_Pengadaan.Kd_Unit =$ku )
			  AND (Tb_Pengadaan.Kd_Sub = $ks) 
			  AND (Tb_Pengadaan.Kd_UPB = $upb) AND $like";
		}else{
			$where = "WHERE (Tb_Pengadaan.Kd_Bidang = $kb) 
			  AND (Tb_Pengadaan.Kd_Unit = $ku) 
			  AND (Tb_Pengadaan.Kd_Sub = $ks) 
			  AND (Tb_Pengadaan.Kd_UPB = $kupb) AND $like";
		}
		
		$query = "SELECT COUNT(*) as Jumlah,SUM(Harga) as Harga FROM Tb_Pengadaan $where";
		
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
		
		$this->db->select('Tb_Pengadaan.*,Ref_Rek_Aset5.Nm_Aset5');
        $this->db->from('Tb_Pengadaan');
		$this->db->order_by('Tgl_Perolehan', 'ASC');
        $this->db->join('Ref_Rek_Aset5', 'Tb_Pengadaan.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Tb_Pengadaan.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Tb_Pengadaan.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Tb_Pengadaan.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Tb_Pengadaan.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5');
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
	 * Mendapatkan data sebuah pengadaan
	 */
	function get_pengadaan_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_id)
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
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_ID' => $no_id), 1);
	}
	
	/**
	 * Mendapatkan data sebuah kiba
	 */
	function get_rincian($n_kontrak)
	{		
		return $this->db->get_where('Tb_Pengadaan_Rinc  INNER JOIN
	Ref_Rek_Aset5 ON Tb_Pengadaan_Rinc.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Tb_Pengadaan_Rinc.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Tb_Pengadaan_Rinc.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Tb_Pengadaan_Rinc.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Tb_Pengadaan_Rinc.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5', array('No_Kontrak' => $n_kontrak));
	}
	
	/**
	 * Menampilkan semua data tanah
	 */
	 /*
	function daTb_Pengadaan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)
	{		
		$this->db->select('Tb_Pengadaan.*,Ref_Rek_Aset5.Nm_Aset5');
        $this->db->from('Tb_Pengadaan');
        $this->db->join('Ref_Rek_Aset5', 'Tb_Pengadaan.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Tb_Pengadaan.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Tb_Pengadaan.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Tb_Pengadaan.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Tb_Pengadaan.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5');
		$this->db->where('Tb_Pengadaan.Kd_Bidang',$kd_bidang);
		$this->db->where('Tb_Pengadaan.Kd_Unit',$kd_unit);
		$this->db->where('Tb_Pengadaan.Kd_Sub',$kd_sub);
		$this->db->where('Tb_Pengadaan.Kd_UPB',$kd_upb);
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
			$this->db->where('Tb_Pengadaan.Kd_Bidang',$kd_bidang);
			$this->db->where('Tb_Pengadaan.Kd_Unit',$kd_unit);
			$this->db->where('Tb_Pengadaan.Kd_Sub',$kd_sub);
			$this->db->where('Tb_Pengadaan.Kd_UPB',$kd_upb);
			$this->db->like('Tb_Pengadaan.Tgl_Perolehan',$tahun);
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
			$like = "AND Tb_Pengadaan.$q like '%$s%'";	
		}
		
		$query= $this->db->query("SELECT     urutan,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Prov) AS urutan
		FROM         Tb_Pengadaan where Tb_Pengadaan.Kd_Bidang=$kd_bidang AND Tb_Pengadaan.Kd_Unit=$kd_unit AND Tb_Pengadaan.Kd_Sub=$kd_sub AND Tb_Pengadaan.Kd_UPB=$kd_upb
		$like) AS Tb_Pengadaan INNER JOIN Ref_Rek_Aset5 ON Tb_Pengadaan.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Tb_Pengadaan.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Tb_Pengadaan.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
		AND Tb_Pengadaan.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Tb_Pengadaan.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 WHERE urutan BETWEEN $first AND $last");				
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
		$this->db->select('Tb_Pengadaan.*,Ref_UPB.Nm_UPB');
        $this->db->from('Tb_Pengadaan');
        $this->db->join('Ref_UPB', 'Tb_Pengadaan.Kd_Prov = Ref_UPB.Kd_Prov AND Tb_Pengadaan.Kd_Kab_Kota = Ref_UPB.Kd_Kab_Kota AND Tb_Pengadaan.Kd_Bidang = Ref_UPB.Kd_Bidang AND Tb_Pengadaan.Kd_Unit = Ref_UPB.Kd_Unit AND Tb_Pengadaan.Kd_Sub = Ref_UPB.Kd_Sub AND Tb_Pengadaan.Kd_UPB = Ref_UPB.Kd_UPB');
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
		$x = "where Tb_Pengadaan.Kd_Bidang=$kd_bidang AND Tb_Pengadaan.Kd_Unit=$kd_unit AND Tb_Pengadaan.Kd_Sub=$kd_sub AND Tb_Pengadaan.Kd_UPB=$kd_upb
		AND Tb_Pengadaan.Tgl_Perolehan like '%$tahun%'";
		}else{
		$x = '';
		}
		
		
		$query= $this->db->query("SELECT     urutan,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Prov) AS urutan
		FROM         Tb_Pengadaan $x) AS Tb_Pengadaan INNER JOIN Ref_Rek_Aset5 ON Tb_Pengadaan.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Tb_Pengadaan.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Tb_Pengadaan.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
		AND Tb_Pengadaan.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Tb_Pengadaan.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 WHERE urutan BETWEEN $first AND $last");
							
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
	function get_last_noid($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5)
	{	
	
		$this->db->select_MAX('No_ID');
		$array_keys_values = $this->db->get_where($this->table,array('Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,
		'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5));
        
		foreach ($array_keys_values->result() as $row)
        {
            $result = $row->No_ID;
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
	function add($data)
	{
		$this->db->insert($this->table, $data);
		return TRUE;
	}
	
	/**
	 * Menambah data rincian
	 */
	function add_rincian($data)
	{
		$this->db->insert('Tb_Pengadaan_Rinc', $data);
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
	 * Menghapus sebuah data
	 */
	function hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_id)
	{
		$this->db->delete($this->table, array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_ID' => $no_id));
	}
	
	/**
	 * Menghapus rincian
	 */
	function hapus_rincian($No_Kontrak,$No_ID)
	{
		$this->db->delete('Tb_Pengadaan_Rinc', array('No_Kontrak' => $No_Kontrak,'No_ID' => $No_ID));
	}
	
	/**
	 * 30-05-2014 Update data KIB
	 */
	function sm_update($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_id,$data)
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
		$this->db->where('No_ID', $no_id);
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
		
		$this->db->insert("Tb_Pengadaan", $data);
		return TRUE;
		
	}
	
	
	/* 28-03-2014 tambah total kiba*/
	function total_kiba($where)
	{	
		$query= "SELECT   SUM(Harga) AS Harga FROM  Tb_Pengadaan INNER JOIN Ref_Rek_Aset5 ON 
Tb_Pengadaan.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Tb_Pengadaan.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
AND Tb_Pengadaan.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Tb_Pengadaan.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
AND Tb_Pengadaan.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5  WHERE $where AND
Tb_Pengadaan.Kd_Aset1 = '1'";
			
		$sql = $this->db->query($query);
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	}
	
	
	/**
	 * mendapatkan no id terakhir
	 */
	function last_id($kontrak)
	{	
	
		$this->db->select_MAX('No_ID');
		$array_keys_values = $this->db->get_where('Tb_Pengadaan_Rinc',array('No_Kontrak' => $kontrak));
        
		foreach ($array_keys_values->result() as $row)
        {
            $result = $row->No_ID;
        }
        
        return $result+1;
		
	}
	
	
	/* 02-07-2014 */
	/**
	 * Menampilkan data laporan kiba
	 */
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

		$query = "SELECT a.*,n.Nm_Aset5,Ref_UPB.Nm_UPB FROM
		Tb_Pengadaan a inner join Ref_Rek_Aset5 n on 
		a.Kd_Aset1=n.Kd_Aset1 AND
		a.Kd_Aset2=n.Kd_Aset2 AND 
		a.Kd_Aset3=n.Kd_Aset3 AND
		a.Kd_Aset4=n.Kd_Aset4 AND
		a.Kd_Aset5=n.Kd_Aset5 INNER JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang 
	AND a.Kd_Unit=Ref_UPB.Kd_Unit 
	AND a.Kd_Sub=Ref_UPB.Kd_Sub
 	AND a.Kd_UPB=Ref_UPB.Kd_UPB
 	WHERE 1=1 $where $tgl_perolehan ORDER BY Log_entry";

 		print_r($query); exit();
		return $this->db->query($query);
	}

	
function laporan_all_pengadaan($like){
	$query = "select * from Tb_Pengadaan inner join Ref_Rek_Aset5 on 
Tb_Pengadaan.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
Tb_Pengadaan.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND 
Tb_Pengadaan.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
Tb_Pengadaan.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
Tb_Pengadaan.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 LEFT JOIN Ref_UPB ON Tb_Pengadaan.Kd_Bidang=Ref_UPB.Kd_Bidang 
	AND Tb_Pengadaan.Kd_Unit=Ref_UPB.Kd_Unit 
	AND Tb_Pengadaan.Kd_Sub=Ref_UPB.Kd_Sub
 	AND Tb_Pengadaan.Kd_UPB=Ref_UPB.Kd_UPB WHERE $like";
	return $this->db->query($query);
}

function set_unpos($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_id){
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
		$this->db->where('No_ID', $no_id);
		$this->db->update($this->table, array("Kd_Posting" => 1));
		
		return TRUE;
		
	}


	function posting_D($data)
	{
		$this->db->insert('Ta_KIB_D', $data);
	}
		
	
}

/* End of file Contoh_model.php */
/* Location: ./system/application/models/Contoh_model.php */