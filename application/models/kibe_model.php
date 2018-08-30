<?php
/**
 * Contoh_model Class
 */
class Kibe_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
	/* Inisialisasi nama tabel yang digunakan */
	var $table = 'Ta_KIB_E';
	var $neraca_awal = NERACA_AWAL;
	
	function getInfoKIB($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register){
		$query = $this->db->get_where($this->table, array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
			'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
			'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register), 1)->row();

		return $query;
	}

	function get_page($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT  * FROM  (
			SELECT     Ref_UPB.Nm_UPB,a.*,Ref_Rek_Aset5.Nm_Aset5,

				(SELECT TOP 1 Status FROM Ta_KIBEHapus h
							WHERE	a.Kd_Prov = h.Kd_Prov
							AND a.Kd_Kab_Kota = h.Kd_Kab_Kota
							AND a.Kd_Bidang = h.Kd_Bidang
							AND a.Kd_Unit = h.Kd_Unit
							AND a.Kd_Sub = h.Kd_Sub
							AND a.Kd_UPB = h.Kd_UPB
							AND a.Kd_Aset1 = h.Kd_Aset1
							AND a.Kd_Aset2 = h.Kd_Aset2
							AND a.Kd_Aset3 = h.Kd_Aset3
							AND a.Kd_Aset4 = h.Kd_Aset4
							AND a.Kd_Aset5 = h.Kd_Aset5
							AND a.No_Register = h.No_Register ORDER BY Tgl_UP DESC
							) AS Penghapusan,

		ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS 
		no_urut		FROM  Ta_KIB_E a INNER JOIN Ref_Rek_Aset5 ON 
		a.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND a.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
		AND a.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND a.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
		AND a.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 LEFT JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang AND a.Kd_Unit=Ref_UPB.Kd_Unit AND
		a.Kd_Sub=Ref_UPB.Kd_Sub AND a.Kd_UPB=Ref_UPB.Kd_UPB
		   WHERE $where $like ) AS a WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC";

		// print_r($sql); exit();
 		return $this->db->query($sql);
 	}
	
	/* count kib b total page */
	function count_kib($where, $like) {
		$sql = "SELECT COUNT(*) as Jumlah, SUM(Harga) as Total FROM Ta_KIB_E a
				INNER JOIN Ref_Rek_Aset5 ON a.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND 
				a.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND a.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
				AND a.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND 
				a.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 WHERE 1=1 ";
		$sql .= "AND $where ";

		$sql .= "$like ";

		// print_r($sql); exit();

		return  $this->db->query($sql)->row();
    }
	
	
	/**
	 * Tampilkan total harga kib b
	 */
	function total_kib($where, $like)
	{		
		$query= "SELECT   SUM(Harga) AS Harga FROM  Ta_KIB_E INNER JOIN Ref_Rek_Aset5 ON 
Ta_KIB_E.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_E.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
AND Ta_KIB_E.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIB_E.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
AND Ta_KIB_E.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5  WHERE $where $like AND
Ta_KIB_E.Kd_Aset1 = '5'";
			
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
	function laporan_kib($bidang='',$unit='',$sub='',$upb='', $tahunawal, $tahunakhir,$like)
	{			
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan 	= " AND Tgl_Pembukuan ".$like;
	$tgl_dokumen 	= " AND Tgl_Dokumen <= '".$tahunakhir."'";

	$query = "SELECT a.Nm_Aset5,COUNT(a.No_register) as jumlah_register,
	MIN(a.No_register) as min_register, MAX(a.No_register) as max_register,
	a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
	a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
	a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik,DATENAME(yyyy,a.Tgl_Perolehan) as Tahun,
	a.Asal_usul,a.Judul,a.Pencipta,a.LastKondisi,COUNT(*) as Jumlah,
	SUM(LastHarga) as  Harga,
			a.Keterangan FROM
			(
				SELECT *,((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
					FROM (	
						SELECT n.Nm_Aset5, a1.*,
						(CASE WHEN NewKondisi is null THEN Kondisi ELSE NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,

/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1

					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 AND YEAR(b.Tgl_Dokumen) <= '{$thn}'
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM   Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
					                   ON d.No_SK=e.No_SK
					                   WHERE a1.Kd_Bidang=d.Kd_Bidang
										AND a1.Kd_Unit=d.Kd_Unit
										AND a1.Kd_Sub=d.Kd_Sub
										AND a1.Kd_UPB=d.Kd_UPB
										AND a1.Kd_Aset1=d.Kd_Aset1
										AND a1.Kd_Aset2=d.Kd_Aset2
										AND a1.Kd_Aset3=d.Kd_Aset3
										AND a1.Kd_Aset4=d.Kd_Aset4
										AND a1.Kd_Aset5=d.Kd_Aset5
										AND a1.No_Register=d.No_Register AND e.Tgl_SK <= '{$tahunakhir}')
							AND NOT EXISTS (SELECT 1 FROM   Ta_KIBER e 
					                   WHERE a1.Kd_Bidang=e.Kd_Bidang
										AND a1.Kd_Unit=e.Kd_Unit
										AND a1.Kd_Sub=e.Kd_Sub
										AND a1.Kd_UPB=e.Kd_UPB
										AND a1.Kd_Aset1=e.Kd_Aset1
										AND a1.Kd_Aset2=e.Kd_Aset2
										AND a1.Kd_Aset3=e.Kd_Aset3
										AND a1.Kd_Aset4=e.Kd_Aset4
										AND a1.Kd_Aset5=e.Kd_Aset5
										AND a1.No_Register=e.No_Register
										AND Kd_Riwayat IN (3,19) AND e.Tgl_Dokumen <= '{$tahunakhir}')			
						) as x

			) as a 
WHERE 1=1 $where $tgl_pembukuan

GROUP BY a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik, a.Kd_Pemilik,a.Tgl_Perolehan,
a.Asal_usul,a.Judul,a.Pencipta,a.lastKondisi,a.Keterangan,a.Nm_Aset5,a.LastKondisi
ORDER BY  a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3,a.Kd_Aset4, a.Kd_Aset5";

			// print_r($query); exit();
			return $this->db->query($query);
		}

	
	/**
	 * Menampilkan data kib E Buku Induk
	 */
	function kibe_buku_induk($like)
	{			
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');

		if ($this->session->userdata('lvl') == 01){
			$where = '';
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIB_E.Kd_Bidang = $kb )
			  AND (Ta_KIB_E.Kd_Unit =$ku )
			  AND (Ta_KIB_E.Kd_Sub = $ks) AND";
		}else{
			$where = "(Ta_KIB_E.Kd_Bidang = $kb) 
			  AND (Ta_KIB_E.Kd_Unit = $ku) 
			  AND (Ta_KIB_E.Kd_Sub = $ks) 
			  AND (Ta_KIB_E.Kd_UPB = $kupb) AND";
		}

		$query = "SELECT  Nm_UPB,Ref_Rek_Aset5.Nm_Aset5,COUNT(Ta_KIB_E.No_register) as jumlah_register,MIN(Ta_KIB_E.No_register) as min_register,
MAX(Ta_KIB_E.No_register) as max_register,
Ta_KIB_E.Kd_Prov, Ta_KIB_E.Kd_Kab_Kota, Ta_KIB_E.Kd_Bidang, Ta_KIB_E.Kd_Unit,
Ta_KIB_E.Kd_Sub, Ta_KIB_E.Kd_UPB, Ta_KIB_E.Kd_Aset1, Ta_KIB_E.Kd_Aset2, Ta_KIB_E.Kd_Aset3, 
Ta_KIB_E.Kd_Aset4, Ta_KIB_E.Kd_Aset5, Ta_KIB_E.Kd_Pemilik,DATENAME(yyyy,Ta_KIB_E.Tgl_Perolehan) as Tahun,
Ta_KIB_E.Asal_usul,Ta_KIB_E.Kondisi,COUNT(*) as Jumlah,
SUM(Ta_KIB_E.Harga) as Harga,Ta_KIB_E.Keterangan

FROM  Ta_KIB_E INNER JOIN
Ref_Rek_Aset5 ON Ta_KIB_E.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_E.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIB_E.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIB_E.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIB_E.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
INNER JOIN Ref_UPB ON Ta_KIB_E.Kd_Bidang=Ref_UPB.Kd_Bidang AND Ta_KIB_E.Kd_Unit=Ref_UPB.Kd_Unit AND Ta_KIB_E.Kd_Sub=Ref_UPB.Kd_Sub AND Ta_KIB_E.Kd_UPB=Ref_UPB.Kd_UPB

WHERE   $where $like

GROUP BY Ta_KIB_E.Kd_Prov, Ta_KIB_E.Kd_Kab_Kota, Ta_KIB_E.Kd_Bidang, Ta_KIB_E.Kd_Unit,
Ta_KIB_E.Kd_Sub, Ta_KIB_E.Kd_UPB, Ta_KIB_E.Kd_Aset1, Ta_KIB_E.Kd_Aset2, Ta_KIB_E.Kd_Aset3, 
Ta_KIB_E.Kd_Aset4, Ta_KIB_E.Kd_Aset5, Ta_KIB_E.Kd_Pemilik, Ta_KIB_E.Kd_Pemilik,Ta_KIB_E.Tgl_Perolehan,Ta_KIB_E.Asal_usul,
Ta_KIB_E.Kondisi,Ta_KIB_E.Keterangan,Ref_Rek_Aset5.Nm_Aset5,Nm_UPB ORDER BY  Nm_UPB,Ref_Rek_Aset5.Nm_Aset5, MIN(Ta_KIB_E.No_register)";
		return $this->db->query($query);
	}
	
	/**
	 * Total Data buku induk skpd
	 */
	function total_kibe_buku_induk($like) {
		 $query = "SELECT COUNT(*) as Jumlah, SUM(Harga) as Harga FROM Ta_KIB_E where Kd_Aset1=5 AND $like"; 
		 return $this->db->query($query); 
 	} 
	
	
	/**
	 * Menampilkan data kib E Mutasi
	 */
	function mutasi($bidang,$unit,$sub,$upb,$tahunawal,$tahunakhir,$like)
	{	
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

		$tgl_pembukuan 	= " AND Tgl_Pembukuan ".$like;
		$tgl_dokumen 	= " AND Tgl_Dokumen <= ".$tahunakhir;

		$query = "SELECT n.Nm_Aset5, COUNT(a.No_register) as jumlah_register,MIN(a.No_register) as min_register, MAX(a.No_register) as max_register, a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit, a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik,DATENAME(yyyy,a.Tgl_Perolehan) as Tahun,
		COUNT (CASE WHEN a.Kd_Riwayat IN (3, 7, 23) THEN a.Harga END ) AS Jumlah_Kurang,
		COUNT (CASE WHEN Kd_Riwayat is null OR Kd_Riwayat IN (2, 21) THEN a.Harga END ) AS Jumlah_Tambah,
		SUM (CASE WHEN a.Kd_Riwayat IN (3, 7, 23) THEN a.Harga END ) AS Mutasi_Kurang,
		SUM (CASE WHEN Kd_Riwayat is null OR Kd_Riwayat IN (2, 21) THEN a.Harga END ) AS Mutasi_Tambah, a.Keterangan
			FROM (
				SELECT null as Kd_Riwayat,Kd_Prov, Kd_Kab_Kota, Kd_Bidang, Kd_Unit, Kd_Sub, Kd_UPB, Kd_Aset1, Kd_Aset2, Kd_Aset3, Kd_Aset4,Kd_Aset5, No_Register, Kd_Pemilik,Tgl_Perolehan,Tgl_Pembukuan, Asal_usul,Kondisi,Harga,Keterangan FROM Ta_KIB_E
				
				UNION ALL
				
				SELECT Kd_Riwayat,Kd_Prov, Kd_Kab_Kota, Kd_Bidang, Kd_Unit, Kd_Sub, Kd_UPB, Kd_Aset1, Kd_Aset2, Kd_Aset3, Kd_Aset4, Kd_Aset5, No_Register,Kd_Pemilik,Tgl_Perolehan,Tgl_Dokumen as Tgl_Pembukuan, Asal_usul,Kondisi,Harga,Keterangan FROM Ta_KIBER
				WHERE Kd_Riwayat IN (2,7,21,23)

				UNION ALL
				/* start */
				SELECT 3 as Kd_Riwayat,Kd_Prov, Kd_Kab_Kota, Kd_Bidang, Kd_Unit, Kd_Sub, Kd_UPB, Kd_Aset1, Kd_Aset2, Kd_Aset3, Kd_Aset4,Kd_Aset5, No_Register, Kd_Pemilik,Tgl_Perolehan,Tgl_Pembukuan, Asal_usul,Kondisi, ((Harga + ISNULL(Koreksi_Tambah, 0)) - ISNULL(Koreksi_Kurang, 0) ) as Harga, Keterangan
					FROM (
						SELECT a1.Kd_Prov, a1.Kd_Kab_Kota, a1.Kd_Bidang, a1.Kd_Unit, a1.Kd_Sub, a1.Kd_UPB, a1.Kd_Aset1, a1.Kd_Aset2, a1.Kd_Aset3, a1.Kd_Aset4,a1.Kd_Aset5, a1.No_Register, a1.Kd_Pemilik,a1.Tgl_Perolehan,a1.Tgl_Pembukuan, a1.Asal_usul,a1.Kondisi, a1.Harga, a1.Keterangan,
					/* ----------- jumlah Penambahan ------------*/
										(SELECT SUM(Harga) as Rharga
										FROM Ta_KIBER a2
										WHERE a2.Kd_Riwayat IN (2, 21)
										AND a1.Kd_Bidang = a2.Kd_Bidang
										AND a1.Kd_Unit = a2.Kd_Unit
										AND a1.Kd_Sub = a2.Kd_Sub
										AND a1.Kd_UPB = a2.Kd_UPB
										AND a1.Kd_Aset1 = a2.Kd_Aset1
										AND a1.Kd_Aset2 = a2.Kd_Aset2
										AND a1.Kd_Aset3 = a2.Kd_Aset3
										AND a1.Kd_Aset4 = a2.Kd_Aset4
										AND a1.Kd_Aset5 = a2.Kd_Aset5
										AND a1.No_Register = a2.No_Register $tgl_dokumen
										) AS Koreksi_Tambah,

					/* ----------- jumlah Berkurang ------------*/
										(SELECT SUM(Harga) as Rharga
										FROM Ta_KIBER a2
										WHERE a2.Kd_Riwayat IN (7, 23)
										AND a1.Kd_Bidang = a2.Kd_Bidang
										AND a1.Kd_Unit = a2.Kd_Unit
										AND a1.Kd_Sub = a2.Kd_Sub
										AND a1.Kd_UPB = a2.Kd_UPB
										AND a1.Kd_Aset1 = a2.Kd_Aset1
										AND a1.Kd_Aset2 = a2.Kd_Aset2
										AND a1.Kd_Aset3 = a2.Kd_Aset3
										AND a1.Kd_Aset4 = a2.Kd_Aset4
										AND a1.Kd_Aset5 = a2.Kd_Aset5
										AND a1.No_Register = a2.No_Register $tgl_dokumen
										) AS Koreksi_Kurang

										FROM Ta_KIB_E a1
					WHERE 1=1 AND EXISTS (SELECT 1 FROM   Ta_KIBER e 
										          WHERE a1.Kd_Bidang=e.Kd_Bidang
															AND a1.Kd_Unit=e.Kd_Unit
															AND a1.Kd_Sub=e.Kd_Sub
															AND a1.Kd_UPB=e.Kd_UPB
															AND a1.Kd_Aset1=e.Kd_Aset1
															AND a1.Kd_Aset2=e.Kd_Aset2
															AND a1.Kd_Aset3=e.Kd_Aset3
															AND a1.Kd_Aset4=e.Kd_Aset4
															AND a1.Kd_Aset5=e.Kd_Aset5
															AND a1.No_Register=e.No_Register
															AND Kd_Riwayat IN (3,19)
															AND e.Tgl_Dokumen < '{$tahunakhir}')
						UNION ALL

						SELECT a1.Kd_Prov, a1.Kd_Kab_Kota, a1.Kd_Bidang, a1.Kd_Unit, a1.Kd_Sub, a1.Kd_UPB, a1.Kd_Aset1, a1.Kd_Aset2, a1.Kd_Aset3, a1.Kd_Aset4,a1.Kd_Aset5, a1.No_Register, a1.Kd_Pemilik,a1.Tgl_Perolehan,e.Tgl_SK as Tgl_Pembukuan, a1.Asal_usul,a1.Kondisi, a1.Harga, a1.Keterangan,
					/* ----------- jumlah Penambahan ------------*/
										(SELECT SUM(Harga) as Rharga
										FROM Ta_KIBER a2
										WHERE a2.Kd_Riwayat IN (2, 21)
										AND a1.Kd_Bidang = a2.Kd_Bidang
										AND a1.Kd_Unit = a2.Kd_Unit
										AND a1.Kd_Sub = a2.Kd_Sub
										AND a1.Kd_UPB = a2.Kd_UPB
										AND a1.Kd_Aset1 = a2.Kd_Aset1
										AND a1.Kd_Aset2 = a2.Kd_Aset2
										AND a1.Kd_Aset3 = a2.Kd_Aset3
										AND a1.Kd_Aset4 = a2.Kd_Aset4
										AND a1.Kd_Aset5 = a2.Kd_Aset5
										AND a1.No_Register = a2.No_Register $tgl_dokumen										) AS Koreksi_Tambah,

					/* ----------- jumlah Berkurang ------------*/
										(SELECT SUM(Harga) as Rharga
										FROM Ta_KIBER a2
										WHERE a2.Kd_Riwayat IN (7, 23)
										AND a1.Kd_Bidang = a2.Kd_Bidang
										AND a1.Kd_Unit = a2.Kd_Unit
										AND a1.Kd_Sub = a2.Kd_Sub
										AND a1.Kd_UPB = a2.Kd_UPB
										AND a1.Kd_Aset1 = a2.Kd_Aset1
										AND a1.Kd_Aset2 = a2.Kd_Aset2
										AND a1.Kd_Aset3 = a2.Kd_Aset3
										AND a1.Kd_Aset4 = a2.Kd_Aset4
										AND a1.Kd_Aset5 = a2.Kd_Aset5
										AND a1.No_Register = a2.No_Register $tgl_dokumen
										) AS Koreksi_Kurang

										FROM Ta_KIB_E a1

					INNER JOIN Ta_KIBEHapus d ON
					a1.Kd_Bidang = d.Kd_Bidang
							AND a1.Kd_Unit = d.Kd_Unit
							AND a1.Kd_Sub = d.Kd_Sub
							AND a1.Kd_UPB = d.Kd_UPB
							AND a1.Kd_Aset1 = d.Kd_Aset1
							AND a1.Kd_Aset2 = d.Kd_Aset2
							AND a1.Kd_Aset3 = d.Kd_Aset3
							AND a1.Kd_Aset4 = d.Kd_Aset4
							AND a1.Kd_Aset5 = d.Kd_Aset5
							AND a1.No_Register = d.No_Register
					INNER JOIN Ta_Penghapusan e ON d.No_SK = e.No_SK
					
					WHERE 1=1 AND EXISTS (SELECT 1 FROM   Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
										                   ON d.No_SK=e.No_SK
										                   WHERE a1.Kd_Bidang=d.Kd_Bidang
															AND a1.Kd_Unit=d.Kd_Unit
															AND a1.Kd_Sub=d.Kd_Sub
															AND a1.Kd_UPB=d.Kd_UPB
															AND a1.Kd_Aset1=d.Kd_Aset1
															AND a1.Kd_Aset2=d.Kd_Aset2
															AND a1.Kd_Aset3=d.Kd_Aset3
															AND a1.Kd_Aset4=d.Kd_Aset4
															AND a1.Kd_Aset5=d.Kd_Aset5
															AND a1.No_Register=d.No_Register
															AND e.Tgl_SK < '{$tahunakhir}')
					) as x
					/* end */
			) as a
			LEFT JOIN Ref_Rek_Aset5 n ON a.Kd_Aset1 = n.Kd_Aset1
			AND a.Kd_Aset2 = n.Kd_Aset2
			AND a.Kd_Aset3 = n.Kd_Aset3
			AND a.Kd_Aset4 = n.Kd_Aset4
			AND a.Kd_Aset5 = n.Kd_Aset5
			 WHERE 1=1 $where $tgl_pembukuan

			GROUP BY a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit, a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik, a.Kd_Pemilik,a.Tgl_Perolehan,a.Kondisi,a.Keterangan,n.Nm_Aset5 ORDER BY  n.Nm_Aset5, MIN(a.No_register)";
		
		// print_r($query); exit();
		
		return $this->db->query($query);
	}
	
	/**
	 * Total Data Mutasi
	 */
	function total_kibe_mutasi($bidang,$unit,$sub,$upb,$like)
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
	
	function rekap_mutasi($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir)
	{			
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan 	= " AND Tgl_Pembukuan <= '$tahunakhir'";
	$tgl_dokumen 	= " AND Tgl_Dokumen < '$tahunawal'";

	$query = "SELECT Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2,
	COUNT(CASE WHEN Tgl_Pembukuan < '$tahunawal' THEN 1 else null END) as Jumlah_awal,
	SUM(CASE WHEN Tgl_Pembukuan < '$tahunawal'  THEN Harga END) as Harga_awal,	

	COUNT(CASE WHEN Tgl_Pembukuan BETWEEN '$tahunawal' AND '$tahunakhir' THEN
			CASE WHEN Kd_Riwayat IN (3, 7, 23) THEN Harga END
		END ) AS Jumlah_Kurang,
	SUM (CASE WHEN Tgl_Pembukuan BETWEEN '$tahunawal' AND '$tahunakhir' THEN
			CASE WHEN Kd_Riwayat IN (3, 7, 23) THEN Harga END
		END) AS Mutasi_Kurang,

	COUNT(CASE WHEN Tgl_Pembukuan BETWEEN '$tahunawal' AND '$tahunakhir' THEN
			CASE WHEN Kd_Riwayat is null OR Kd_Riwayat IN (2, 21) THEN Harga END
		END ) AS Jumlah_Tambah,
	SUM (CASE WHEN Tgl_Pembukuan BETWEEN '$tahunawal' AND '$tahunakhir' THEN
			CASE WHEN Kd_Riwayat is null OR Kd_Riwayat IN (2, 21) THEN Harga END
		END) AS Mutasi_Tambah
	FROM Ref_Rek_Aset2 LEFT JOIN (
		SELECT * FROM (

	SELECT Kd_Riwayat,Kd_Prov, Kd_Kab_Kota, Kd_Bidang, Kd_Unit, Kd_Sub, Kd_UPB, Kd_Aset1, Kd_Aset2, Kd_Aset3, Kd_Aset4,Kd_Aset5, No_Register, Kd_Pemilik,Tgl_Perolehan,Tgl_Dokumen as Tgl_Pembukuan, Asal_usul,Kondisi,Harga,Keterangan FROM Ta_KIBER
		WHERE Kd_Riwayat IN (2,7,21,23) AND Tgl_Dokumen BETWEEN '$tahunawal' AND '$tahunakhir'
		
		UNION ALL

	/* mutasi kurang pindah skpd */
		SELECT 3 as Kd_Riwayat,Kd_Prov, Kd_Kab_Kota, Kd_Bidang, Kd_Unit, Kd_Sub, Kd_UPB, Kd_Aset1, Kd_Aset2, Kd_Aset3, Kd_Aset4,Kd_Aset5, No_Register, Kd_Pemilik,Tgl_Perolehan,Tgl_Pembukuan, Asal_usul,Kondisi, ((Harga + ISNULL(Koreksi_Tambah, 0)) - ISNULL(Koreksi_Kurang, 0) ) as Harga, Keterangan
			FROM (
						SELECT a1.*,a2.Tgl_Dokumen as NewDokumen,
					/* ----------- jumlah Penambahan ------------*/
										(SELECT SUM(Harga) as Rharga
										FROM Ta_KIBER a2
										WHERE a2.Kd_Riwayat IN (2, 21)
										AND a1.Kd_Bidang = a2.Kd_Bidang
										AND a1.Kd_Unit = a2.Kd_Unit
										AND a1.Kd_Sub = a2.Kd_Sub
										AND a1.Kd_UPB = a2.Kd_UPB
										AND a1.Kd_Aset1 = a2.Kd_Aset1
										AND a1.Kd_Aset2 = a2.Kd_Aset2
										AND a1.Kd_Aset3 = a2.Kd_Aset3
										AND a1.Kd_Aset4 = a2.Kd_Aset4
										AND a1.Kd_Aset5 = a2.Kd_Aset5
										AND a1.No_Register = a2.No_Register $tgl_dokumen
										) AS Koreksi_Tambah,

					/* ----------- jumlah Berkurang ------------*/
										(SELECT SUM(Harga) as Rharga
										FROM Ta_KIBER a2
										WHERE a2.Kd_Riwayat IN (7, 23)
										AND a1.Kd_Bidang = a2.Kd_Bidang
										AND a1.Kd_Unit = a2.Kd_Unit
										AND a1.Kd_Sub = a2.Kd_Sub
										AND a1.Kd_UPB = a2.Kd_UPB
										AND a1.Kd_Aset1 = a2.Kd_Aset1
										AND a1.Kd_Aset2 = a2.Kd_Aset2
										AND a1.Kd_Aset3 = a2.Kd_Aset3
										AND a1.Kd_Aset4 = a2.Kd_Aset4
										AND a1.Kd_Aset5 = a2.Kd_Aset5
										AND a1.No_Register = a2.No_Register $tgl_dokumen
										) AS Koreksi_Kurang

										FROM Ta_KIB_E a1
					RIGHT JOIN Ta_KIBER a2 ON a1.Kd_Bidang = a2.Kd_Bidang
										AND a1.Kd_Unit = a2.Kd_Unit
										AND a1.Kd_Sub = a2.Kd_Sub
										AND a1.Kd_UPB = a2.Kd_UPB
										AND a1.Kd_Aset1 = a2.Kd_Aset1
										AND a1.Kd_Aset2 = a2.Kd_Aset2
										AND a1.Kd_Aset3 = a2.Kd_Aset3
										AND a1.Kd_Aset4 = a2.Kd_Aset4
										AND a1.Kd_Aset5 = a2.Kd_Aset5
										AND a1.No_Register = a2.No_Register
					WHERE Kd_Riwayat IN (3,19) AND Tgl_Dokumen BETWEEN '$tahunawal' AND '$tahunakhir'
	 ) as mk_pindah_skpd

	 UNION ALL

	/* start mutasi kurang penghapusan */
	SELECT 3 as Kd_Riwayat,Kd_Prov, Kd_Kab_Kota, Kd_Bidang, Kd_Unit, Kd_Sub, Kd_UPB, Kd_Aset1, Kd_Aset2, Kd_Aset3, Kd_Aset4,Kd_Aset5, No_Register, Kd_Pemilik,Tgl_Perolehan,NewDokumen as Tgl_Pembukuan, Asal_usul,Kondisi, ((Harga + ISNULL(Koreksi_Tambah, 0)) - ISNULL(Koreksi_Kurang, 0) ) as Harga, Keterangan
			FROM (
						SELECT a1.*,a3.Tgl_SK as NewDokumen,
					/* ----------- jumlah Penambahan ------------*/
										(SELECT SUM(Harga) as Rharga
										FROM Ta_KIBER a2
										WHERE a2.Kd_Riwayat IN (2, 21)
										AND a1.Kd_Bidang = a2.Kd_Bidang
										AND a1.Kd_Unit = a2.Kd_Unit
										AND a1.Kd_Sub = a2.Kd_Sub
										AND a1.Kd_UPB = a2.Kd_UPB
										AND a1.Kd_Aset1 = a2.Kd_Aset1
										AND a1.Kd_Aset2 = a2.Kd_Aset2
										AND a1.Kd_Aset3 = a2.Kd_Aset3
										AND a1.Kd_Aset4 = a2.Kd_Aset4
										AND a1.Kd_Aset5 = a2.Kd_Aset5
										AND a1.No_Register = a2.No_Register $tgl_dokumen
										) AS Koreksi_Tambah,

					/* ----------- jumlah Berkurang ------------*/
										(SELECT SUM(Harga) as Rharga
										FROM Ta_KIBER a2
										WHERE a2.Kd_Riwayat IN (7, 23)
										AND a1.Kd_Bidang = a2.Kd_Bidang
										AND a1.Kd_Unit = a2.Kd_Unit
										AND a1.Kd_Sub = a2.Kd_Sub
										AND a1.Kd_UPB = a2.Kd_UPB
										AND a1.Kd_Aset1 = a2.Kd_Aset1
										AND a1.Kd_Aset2 = a2.Kd_Aset2
										AND a1.Kd_Aset3 = a2.Kd_Aset3
										AND a1.Kd_Aset4 = a2.Kd_Aset4
										AND a1.Kd_Aset5 = a2.Kd_Aset5
										AND a1.No_Register = a2.No_Register $tgl_dokumen
										) AS Koreksi_Kurang

										FROM Ta_KIB_E a1
					RIGHT JOIN Ta_KIBEHapus a2 ON a1.Kd_Bidang = a2.Kd_Bidang
										AND a1.Kd_Unit = a2.Kd_Unit
										AND a1.Kd_Sub = a2.Kd_Sub
										AND a1.Kd_UPB = a2.Kd_UPB
										AND a1.Kd_Aset1 = a2.Kd_Aset1
										AND a1.Kd_Aset2 = a2.Kd_Aset2
										AND a1.Kd_Aset3 = a2.Kd_Aset3
										AND a1.Kd_Aset4 = a2.Kd_Aset4
										AND a1.Kd_Aset5 = a2.Kd_Aset5
										AND a1.No_Register = a2.No_Register
					INNER JOIN Ta_Penghapusan a3  ON a2.No_SK=a3.No_SK
					WHERE a3.Tgl_SK BETWEEN '$tahunawal' AND '$tahunakhir'

	 ) as mk_penghapusan
	/* end mutasi kurang penghapusan */

	UNION ALL

	SELECT null as Kd_Riwayat, Kd_Prov, Kd_Kab_Kota, Kd_Bidang, Kd_Unit, Kd_Sub, Kd_UPB, Kd_Aset1, Kd_Aset2, Kd_Aset3, Kd_Aset4,Kd_Aset5, No_Register, Kd_Pemilik,Tgl_Perolehan,Tgl_Pembukuan, Asal_usul,Kondisi, ((Harga + ISNULL(Koreksi_Tambah, 0)) - ISNULL(Koreksi_Kurang, 0) ) as Harga, Keterangan
		FROM (	
						SELECT n.Nm_Aset5, a1.*,
						(CASE WHEN NewKondisi is null THEN Kondisi ELSE NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,

/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1

					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 AND YEAR(b.Tgl_Dokumen) <= '{$thn}'
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM   Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
					                   ON d.No_SK=e.No_SK
					                   WHERE a1.Kd_Bidang=d.Kd_Bidang
										AND a1.Kd_Unit=d.Kd_Unit
										AND a1.Kd_Sub=d.Kd_Sub
										AND a1.Kd_UPB=d.Kd_UPB
										AND a1.Kd_Aset1=d.Kd_Aset1
										AND a1.Kd_Aset2=d.Kd_Aset2
										AND a1.Kd_Aset3=d.Kd_Aset3
										AND a1.Kd_Aset4=d.Kd_Aset4
										AND a1.Kd_Aset5=d.Kd_Aset5
										AND a1.No_Register=d.No_Register AND e.Tgl_SK < '$tahunawal')
							AND NOT EXISTS (SELECT 1 FROM  Ta_KIBER e 
					                   WHERE a1.Kd_Bidang=e.Kd_Bidang
										AND a1.Kd_Unit=e.Kd_Unit
										AND a1.Kd_Sub=e.Kd_Sub
										AND a1.Kd_UPB=e.Kd_UPB
										AND a1.Kd_Aset1=e.Kd_Aset1
										AND a1.Kd_Aset2=e.Kd_Aset2
										AND a1.Kd_Aset3=e.Kd_Aset3
										AND a1.Kd_Aset4=e.Kd_Aset4
										AND a1.Kd_Aset5=e.Kd_Aset5
										AND a1.No_Register=e.No_Register 
							AND Kd_Riwayat IN (3,19) AND e.Tgl_Dokumen < '$tahunawal')			

		) as x


	) as a WHERE 1=1 $where $tgl_pembukuan) as a3 ON
a3.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND
a3.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2 
WHERE Ref_Rek_Aset2.Kd_Aset1=5
GROUP BY Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2
ORDER BY Kd_Aset2";

			// print_r($query); exit();
			return $this->db->query($query);
		}
	
	/**
	 * Menampilkan Total rekap Mutasi
	 */
	function total_rekap_mutasi($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir)
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

	$tgl_pembukuan 	= " AND Tgl_Pembukuan BETWEEN '$tahunawal' AND '$tahunakhir'";
	$tgl_dokumen 	= " AND Tgl_Dokumen < '$tahunakhir'";
	$awal 	= $tahunawal;
	$akhir	= "AND Tgl_Pembukuan < '$tahunakhir'";

	$query = "SELECT COUNT(CASE WHEN Tgl_Perolehan < '$awal' THEN 1 else null END) as Jumlah_awal,
	SUM(CASE WHEN Tgl_Perolehan < '$awal'  THEN LastHarga END) as Harga_awal,
	COUNT(CASE WHEN Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir' THEN 0 else null END) as Jumlah_tambah,
	SUM(CASE WHEN Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir' THEN LastHarga END) as Harga_tambah,
	COUNT(*) as Jumlah,
	SUM(LastHarga) as Harga
	FROM Ref_Rek_Aset2 LEFT JOIN (
	SELECT *,(a.Harga + (CASE WHEN Kapitalisasi is null THEN 0 ELSE Kapitalisasi END) + (CASE WHEN Koreksi_Tambah is null THEN 0 ELSE Koreksi_Tambah END)) - ((CASE WHEN Koreksi_Kurang is null THEN 0 ELSE Koreksi_Kurang END) + (CASE WHEN Penghapusan is null THEN 0 ELSE Penghapusan END) ) as LastHarga
	 FROM (SELECT *,
/* ----------- jumlah kapitalisasi ------------*/
				(SELECT SUM(Harga) as Rharga
				FROM Ta_KIBER a2
				WHERE a2.Kd_Riwayat = '2'
				AND a1.Kd_Bidang = a2.Kd_Bidang
				AND a1.Kd_Unit = a2.Kd_Unit
				AND a1.Kd_Sub = a2.Kd_Sub
				AND a1.Kd_UPB = a2.Kd_UPB
				AND a1.Kd_Aset1 = a2.Kd_Aset1
				AND a1.Kd_Aset2 = a2.Kd_Aset2
				AND a1.Kd_Aset3 = a2.Kd_Aset3
				AND a1.Kd_Aset4 = a2.Kd_Aset4
				AND a1.Kd_Aset5 = a2.Kd_Aset5
				AND a1.No_Register = a2.No_Register $tgl_dokumen
		) AS Kapitalisasi,
/* ----------- jumlah pengurangan PINDAH SKPD------------*/
				(SELECT SUM(Harga) as Rharga
				FROM Ta_KIBER a2
				WHERE a2.Kd_Riwayat = '3'
				AND a1.Kd_Bidang = a2.Kd_Bidang
				AND a1.Kd_Unit = a2.Kd_Unit
				AND a1.Kd_Sub = a2.Kd_Sub
				AND a1.Kd_UPB = a2.Kd_UPB
				AND a1.Kd_Aset1 = a2.Kd_Aset1
				AND a1.Kd_Aset2 = a2.Kd_Aset2
				AND a1.Kd_Aset3 = a2.Kd_Aset3
				AND a1.Kd_Aset4 = a2.Kd_Aset4
				AND a1.Kd_Aset5 = a2.Kd_Aset5
				AND a1.No_Register = a2.No_Register $tgl_dokumen
		) AS Pindah_SKPD,
/* ----------- jumlah pengurangan PENGHAPUSAN------------*/
				(SELECT SUM(Harga) as Rharga
				FROM Ta_KIBER a2
				WHERE a2.Kd_Riwayat = '7'
				AND a1.Kd_Bidang = a2.Kd_Bidang
				AND a1.Kd_Unit = a2.Kd_Unit
				AND a1.Kd_Sub = a2.Kd_Sub
				AND a1.Kd_UPB = a2.Kd_UPB
				AND a1.Kd_Aset1 = a2.Kd_Aset1
				AND a1.Kd_Aset2 = a2.Kd_Aset2
				AND a1.Kd_Aset3 = a2.Kd_Aset3
				AND a1.Kd_Aset4 = a2.Kd_Aset4
				AND a1.Kd_Aset5 = a2.Kd_Aset5
				AND a1.No_Register = a2.No_Register $tgl_dokumen
		) AS Penghapusan,
/* ----------- jumlah pengurangan Ubah data------------*/
				(SELECT SUM(Harga) as Rharga
				FROM Ta_KIBER a2
				WHERE a2.Kd_Riwayat = '18'
				AND a1.Kd_Bidang = a2.Kd_Bidang
				AND a1.Kd_Unit = a2.Kd_Unit
				AND a1.Kd_Sub = a2.Kd_Sub
				AND a1.Kd_UPB = a2.Kd_UPB
				AND a1.Kd_Aset1 = a2.Kd_Aset1
				AND a1.Kd_Aset2 = a2.Kd_Aset2
				AND a1.Kd_Aset3 = a2.Kd_Aset3
				AND a1.Kd_Aset4 = a2.Kd_Aset4
				AND a1.Kd_Aset5 = a2.Kd_Aset5
				AND a1.No_Register = a2.No_Register $tgl_dokumen
		) AS Ubah_data,
/* ----------- jumlah penambahan Koreksi Nilai------------*/
				(SELECT SUM(Harga) as Rharga
				FROM Ta_KIBER a2
				WHERE a2.Kd_Riwayat = '21'
				AND a1.Kd_Bidang = a2.Kd_Bidang
				AND a1.Kd_Unit = a2.Kd_Unit
				AND a1.Kd_Sub = a2.Kd_Sub
				AND a1.Kd_UPB = a2.Kd_UPB
				AND a1.Kd_Aset1 = a2.Kd_Aset1
				AND a1.Kd_Aset2 = a2.Kd_Aset2
				AND a1.Kd_Aset3 = a2.Kd_Aset3
				AND a1.Kd_Aset4 = a2.Kd_Aset4
				AND a1.Kd_Aset5 = a2.Kd_Aset5
				AND a1.No_Register = a2.No_Register $tgl_dokumen
		) AS Koreksi_Tambah,
/* ----------- jumlah penambahan Koreksi Nilai------------*/
				(SELECT SUM(Harga) as Rharga
				FROM Ta_KIBER a2
				WHERE a2.Kd_Riwayat = '23'
				AND a1.Kd_Bidang = a2.Kd_Bidang
				AND a1.Kd_Unit = a2.Kd_Unit
				AND a1.Kd_Sub = a2.Kd_Sub
				AND a1.Kd_UPB = a2.Kd_UPB
				AND a1.Kd_Aset1 = a2.Kd_Aset1
				AND a1.Kd_Aset2 = a2.Kd_Aset2
				AND a1.Kd_Aset3 = a2.Kd_Aset3
				AND a1.Kd_Aset4 = a2.Kd_Aset4
				AND a1.Kd_Aset5 = a2.Kd_Aset5
				AND a1.No_Register = a2.No_Register $tgl_dokumen
		) AS Koreksi_Kurang

 FROM Ta_KIB_E a1)
 as a WHERE 1=1 $where $akhir ) as a3 ON 
a3.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND
a3.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2   WHERE Ref_Rek_Aset2.Kd_Aset1=5 ";

			/*print_r($query); exit();*/
			return $this->db->query($query);
	}

	/**
	 * Menampilkan semua data kibe
	 */
	function total_kibe_inventaris($bidang='',$unit='',$sub='',$upb='',$like)
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
		$query = "SELECT COUNT(*) as Jumlah,SUM(Harga) as Harga FROM Ta_KIB_E a WHERE 1=1 $where $like";
		return $this->db->query($query);
		}
	
	/**
	 * Menampilkan sdata kib a rekap inventaris
	 */
	function kibe_rinventaris($bidang,$unit,$sub,$upb,$like)
	{	
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "WHERE (Ta_KIB_E.Kd_Bidang = $bidang) 
			  AND (Ta_KIB_E.Kd_Unit = $unit) 
			  AND (Ta_KIB_E.Kd_Sub = $sub) 
			  AND (Ta_KIB_E.Kd_UPB = $upb) AND $like";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "WHERE (Ta_KIB_E.Kd_Bidang = $kb )
			  AND (Ta_KIB_E.Kd_Unit =$ku )
			  AND (Ta_KIB_E.Kd_Sub = $ks) 
			  AND (Ta_KIB_E.Kd_UPB = $upb) AND $like";
		}else{
			$where = "WHERE (Ta_KIB_E.Kd_Bidang = $kb) 
			  AND (Ta_KIB_E.Kd_Unit = $ku) 
			  AND (Ta_KIB_E.Kd_Sub = $ks) 
			  AND (Ta_KIB_E.Kd_UPB = $kupb) AND $like";
		}
		
$query = "SELECT Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2,COUNT(*) as Jumlah, SUM(Harga) as Harga from Ref_Rek_Aset2 LEFT JOIN (SELECT * from Ta_KIB_E $where) as Ta_KIB_E ON 
Ta_KIB_E.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND
Ta_KIB_E.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2   WHERE Ref_Rek_Aset2.Kd_Aset1=5
GROUP BY Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2 ";
		return $this->db->query($query);
	}
	
	/**
	 * Total Data Mutasi
	 */
	function total_kibe_rinventaris($bidang,$unit,$sub,$upb,$like)
	{	
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "WHERE (Ta_KIB_E.Kd_Bidang = $bidang) 
			  AND (Ta_KIB_E.Kd_Unit = $unit) 
			  AND (Ta_KIB_E.Kd_Sub = $sub) 
			  AND (Ta_KIB_E.Kd_UPB = $upb) AND $like";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "WHERE (Ta_KIB_E.Kd_Bidang = $kb )
			  AND (Ta_KIB_E.Kd_Unit =$ku )
			  AND (Ta_KIB_E.Kd_Sub = $ks) 
			  AND (Ta_KIB_E.Kd_UPB = $upb) AND $like";
		}else{
			$where = "WHERE (Ta_KIB_E.Kd_Bidang = $kb) 
			  AND (Ta_KIB_E.Kd_Unit = $ku) 
			  AND (Ta_KIB_E.Kd_Sub = $ks) 
			  AND (Ta_KIB_E.Kd_UPB = $kupb) AND $like";
		}
		
		$query = "SELECT COUNT(*) as Jumlah,SUM(Harga) as Harga FROM Ta_KIB_E $where";
		
        return $this->db->query($query);
	}


	/**
	 * Menampilkan semua data kibe
	 */
	function kibe_inventaris($bidang='',$unit='',$sub='',$upb='',$like)
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
		$query = "SELECT   Ref_Rek_Aset5.Nm_Aset5,COUNT(a.No_register) as jumlah_register,MIN(a.No_register) as min_register, MAX(a.No_register) as max_register,
		a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
		a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3,a.Kd_Aset4, a.Kd_Aset5,a.Judul, 
		 a.Pencipta, a.Kd_Pemilik,DATENAME(yyyy,a.Tgl_Perolehan) as Tahun,
		a.Asal_usul,a.Kondisi,COUNT(*) as Jumlah,
		SUM(a.Harga) as Harga,a.Keterangan

		FROM  Ta_KIB_E a INNER JOIN
		Ref_Rek_Aset5 ON a.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND a.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND a.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND a.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND a.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
		 
		WHERE 1=1 $where $like
		GROUP BY a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
		a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
		a.Kd_Aset4, a.Kd_Aset5, a.Judul,  a.Pencipta, a.Kd_Pemilik, a.Kd_Pemilik,a.Tgl_Perolehan,a.Asal_usul,
		a.Kondisi,a.Keterangan,Ref_Rek_Aset5.Nm_Aset5 ORDER BY  Ref_Rek_Aset5.Nm_Aset5, MIN(a.No_register)";

		return $this->db->query($query);
		}
	
	function rekap_skpd($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like,$kondisi)
	{			
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan 	= " AND Tgl_Pembukuan ".$like;
	$tgl_dokumen 	= " AND Tgl_Dokumen <= '".$tahunakhir."'";

	$query = "SELECT COUNT(*) as Jumlah,
	SUM(LastHarga) as  Harga FROM
			(
				SELECT *,((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
					FROM (	
						SELECT n.Nm_Aset5, a1.*,
						(CASE WHEN NewKondisi is null THEN Kondisi ELSE NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,

/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1

					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 AND YEAR(b.Tgl_Dokumen) <= '{$thn}'
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM   Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
					                   ON d.No_SK=e.No_SK
					                   WHERE a1.Kd_Bidang=d.Kd_Bidang
										AND a1.Kd_Unit=d.Kd_Unit
										AND a1.Kd_Sub=d.Kd_Sub
										AND a1.Kd_UPB=d.Kd_UPB
										AND a1.Kd_Aset1=d.Kd_Aset1
										AND a1.Kd_Aset2=d.Kd_Aset2
										AND a1.Kd_Aset3=d.Kd_Aset3
										AND a1.Kd_Aset4=d.Kd_Aset4
										AND a1.Kd_Aset5=d.Kd_Aset5
										AND a1.No_Register=d.No_Register AND YEAR(e.Tgl_SK) <= '{$thn}')
							AND NOT EXISTS (SELECT 1 FROM   Ta_KIBER e 
					                   WHERE a1.Kd_Bidang=e.Kd_Bidang
										AND a1.Kd_Unit=e.Kd_Unit
										AND a1.Kd_Sub=e.Kd_Sub
										AND a1.Kd_UPB=e.Kd_UPB
										AND a1.Kd_Aset1=e.Kd_Aset1
										AND a1.Kd_Aset2=e.Kd_Aset2
										AND a1.Kd_Aset3=e.Kd_Aset3
										AND a1.Kd_Aset4=e.Kd_Aset4
										AND a1.Kd_Aset5=e.Kd_Aset5
										AND a1.No_Register=e.No_Register  AND Kd_Riwayat IN (3,19) AND YEAR(e.Tgl_Dokumen) <= '{$thn}')			
						) as x

			) as a 
WHERE 1=1 $where $tgl_pembukuan $kondisi";

			// print_r($query); exit();
			return $this->db->query($query);
		}
	
	
	/**
	 * Mendapatkan data sebuah Kibe
	 */
	function get_kibe_by_id($kd_prov,$kd_kab,$bidang,$unit,$sub,$upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$where = "";

		$where .= "AND (a.Kd_Prov = $kd_prov)";
		$where .= "AND (a.Kd_Kab_Kota = $kd_kab)";

		if ($this->session->userdata('lvl') == 01){
		$where = "AND (a.Kd_Bidang = $bidang) 
			  AND (a.Kd_Unit = $unit) 
			  AND (a.Kd_Sub = $sub) 
			  AND (a.Kd_UPB = $upb)";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND (a.Kd_Bidang = $kb )
			  AND (a.Kd_Unit =$ku )
			  AND (a.Kd_Sub = $ks) 
			  AND (a.Kd_UPB = $upb)";
		}else{
			$where = "AND (a.Kd_Bidang = $kb) 
			  AND (a.Kd_Unit = $ku) 
			  AND (a.Kd_Sub = $ks) 
			  AND (a.Kd_UPB = $kupb)";
		}

	$where .= "AND a.Kd_Aset1 = {$kd_aset1} AND a.Kd_Aset2 = {$kd_aset2} AND a.Kd_Aset3 = {$kd_aset3} AND a.Kd_Aset4 = {$kd_aset4} AND a.Kd_Aset5 = {$kd_aset5} AND a.No_Register = {$no_register} ";
	$tgl_dokumen 	= " AND YEAR(Tgl_Dokumen) <= '{$this->session->userdata('tahun_anggaran')}'";

	$query = "SELECT *,
	((Harga + ISNULL(Total_Koreksi, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as HargaKoreksi,
	((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
	
	FROM (
		SELECT n.Nm_Aset5,a1.*,
		(CASE WHEN NewKondisi is null THEN Kondisi ELSE NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 21
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Total_Koreksi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 2
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Kapitalisasi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,

/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1
					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 $tgl_dokumen
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

)as a WHERE 1=1 $where";

			/*print_r($query); exit();*/
			return $this->db->query($query);
	}
	

	function data_foto($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{	
		$this->db->select('*');
        $this->db->from('Ta_FotoE');
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
	
	function get_last_kapitalisasi($kd_prov,$kd_kab,$bidang,$unit,$sub,$upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$where = "";

		$where .= "AND (a.Kd_Prov = $kd_prov)";
		$where .= "AND (a.Kd_Kab_Kota = $kd_kab)";

		if ($this->session->userdata('lvl') == 01){
			$where = "AND (a.Kd_Bidang = $bidang) AND (a.Kd_Unit = $unit) AND (a.Kd_Sub = $sub) AND (a.Kd_UPB = $upb)";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "AND (a.Kd_Bidang = $kb ) AND (a.Kd_Unit =$ku ) AND (a.Kd_Sub = $ks) AND (a.Kd_UPB = $upb)";
		}else{
			$where = "AND (a.Kd_Bidang = $kb) AND (a.Kd_Unit = $ku) AND (a.Kd_Sub = $ks) AND (a.Kd_UPB = $kupb)";
		}
			$where .= "AND a.Kd_Riwayat = 2";
			$where .= "AND a.Kd_Aset1 = {$kd_aset1} AND a.Kd_Aset2 = {$kd_aset2} AND a.Kd_Aset3 = {$kd_aset3} AND a.Kd_Aset4 = {$kd_aset4} AND a.Kd_Aset5 = {$kd_aset5} AND a.No_Register = {$no_register} ";

		$query = "SELECT MAX(YEAR(Tgl_Dokumen)) as last_kapitalisasi FROM Ta_KILER a WHERE 1=1 $where";

		return $this->db->query($query);
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
	 * Menambah data tanah
	 */
	function add($kibe)
	{
		$this->db->insert($this->table, $kibe);
		return TRUE;
	}

	function add_riwayat($data)
	{
		$sql = $this->db->insert('Ta_KIBER', $data);
		if($sql)
			return TRUE;
	}
	
	/**
	 * Menambah data tanah
	 */
	function insert($foto)
	{
		$this->db->insert('Ta_FotoE', $foto);
		return TRUE;
	}
	
	
	/**
	 * Menghapus sebuah data Kibe
	 */
	function hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		$this->db->delete($this->table, array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register));
	}

	function hapus_riwayat($kd_riwayat,$kd_id,$kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{

		$this->db->delete("Ta_KIBER", array('Kd_Riwayat' => $kd_riwayat,'Kd_Id' => $kd_id,'Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register));
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
	
	
	/* 28-03-2014 tambah total Kibe*/
	function total_kibe()
	{	
		$arr = array();
		$where = "";
		if ($this->session->userdata('lvl') == 01){
				$where .= "";
		}elseif ($this->session->userdata('lvl') == 02){
				$where .= "AND (a.Kd_Bidang = ".$this->session->userdata('kd_bidang').")";
				$where .= "AND (a.Kd_Unit = ".$this->session->userdata('kd_unit').")";
				$where .= "AND (a.Kd_Sub = ".$this->session->userdata('kd_sub_unit').")";
		}else{
				$where .= "AND (a.Kd_Bidang = ".$this->session->userdata('kd_bidang').")";
				$where .= "AND (a.Kd_Unit = ".$this->session->userdata('kd_unit').")";
				$where .= "AND (a.Kd_Sub = ".$this->session->userdata('kd_sub_unit').")";
				$where .= "AND (a.Kd_UPB = ".$this->session->userdata('kd_upb').")";
		}

		$tgl_pembukuan 	= " AND YEAR(Tgl_Perolehan) <=".$this->session->userdata('tahun_anggaran');
		$tgl_dokumen 	= " AND YEAR(Tgl_Dokumen) <=".$this->session->userdata('tahun_anggaran');

		$query = "SELECT Thn_Perolehan,
				COUNT(*) as Jumlah,
				SUM(LastHarga) as Harga
				 FROM (
				 	SELECT *,
				 	(CASE WHEN DATENAME(yyyy, Tgl_Perolehan) < 2010 THEN 2009 ELSE DATENAME(yyyy, Tgl_Perolehan) END) as Thn_Perolehan,
				 	(Harga + (CASE WHEN Kapitalisasi is null THEN 0 ELSE Kapitalisasi END) + (CASE WHEN Koreksi_Tambah is null THEN 0 ELSE Koreksi_Tambah END)) - (CASE WHEN Koreksi_Kurang is null THEN 0 ELSE Koreksi_Kurang END) as LastHarga
				 FROM 
(SELECT a1.*,
	/* ----------- jumlah kapitalisasi ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '2'
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Kapitalisasi,
	/* ----------- jumlah pengurangan PINDAH SKPD------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '3'
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Pindah_SKPD,
	/* ----------- jumlah pengurangan PENGHAPUSAN------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '7'
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Penghapusan,
	/* ----------- jumlah pengurangan Ubah data------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '18'
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Ubah_data,
	/* ----------- jumlah penambahan Koreksi Nilai------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '21'
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,
	/* ----------- jumlah penambahan Koreksi Nilai------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '23'
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang
FROM Ta_KIB_E a1
) as a WHERE 1=1 $where $tgl_pembukuan) as b GROUP BY Thn_Perolehan ORDER BY Thn_Perolehan";
	
		$sql = $this->db->query($query);

		foreach ($sql->result() as $row)
		{
			$arr[] = $row->Harga;
		}
		return $arr;
	}	
	
	
function laporan_all_kibe($like){
	$query = "SELECT   Ref_Rek_Aset5.Nm_Aset5,COUNT(Ta_KIB_E.No_register) as jumlah_register,MIN(Ta_KIB_E.No_register) as min_register,
MAX(Ta_KIB_E.No_register) as max_register,Ta_KIB_E.Judul, Ta_KIB_E.Pencipta,
Ta_KIB_E.Kd_Prov, Ta_KIB_E.Kd_Kab_Kota, Ta_KIB_E.Kd_Bidang, Ta_KIB_E.Kd_Unit,
Ta_KIB_E.Kd_Sub, Ta_KIB_E.Kd_UPB, Ta_KIB_E.Kd_Aset1, Ta_KIB_E.Kd_Aset2, Ta_KIB_E.Kd_Aset3, 
Ta_KIB_E.Kd_Aset4, Ta_KIB_E.Kd_Aset5, Ta_KIB_E.Kd_Pemilik,DATENAME(yyyy,Ta_KIB_E.Tgl_Perolehan) as Tahun,
Ta_KIB_E.Asal_usul,Ta_KIB_E.Kondisi,COUNT(*) as Jumlah,
SUM(Ta_KIB_E.Harga) as Harga,Nm_UPB

FROM  Ta_KIB_E INNER JOIN
Ref_Rek_Aset5 ON Ta_KIB_E.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_E.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIB_E.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIB_E.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIB_E.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 LEFT JOIN Ref_UPB ON Ta_KIB_E.Kd_Bidang=Ref_UPB.Kd_Bidang 
	AND Ta_KIB_E.Kd_Unit=Ref_UPB.Kd_Unit 
	AND Ta_KIB_E.Kd_Sub=Ref_UPB.Kd_Sub
 	AND Ta_KIB_E.Kd_UPB=Ref_UPB.Kd_UPB
 
WHERE $like

GROUP BY Ta_KIB_E.Kd_Prov, Ta_KIB_E.Kd_Kab_Kota, Ta_KIB_E.Kd_Bidang, Ta_KIB_E.Kd_Unit,
Ta_KIB_E.Kd_Sub, Ta_KIB_E.Kd_UPB, Ta_KIB_E.Kd_Aset1, Ta_KIB_E.Kd_Aset2, Ta_KIB_E.Kd_Aset3, 
Ta_KIB_E.Kd_Aset4, Ta_KIB_E.Kd_Aset5, Ta_KIB_E.Kd_Pemilik, Ta_KIB_E.Kd_Pemilik,Ta_KIB_E.Tgl_Perolehan,Ta_KIB_E.Asal_usul,
Ta_KIB_E.Kondisi,Nm_UPB,Ref_Rek_Aset5.Nm_Aset5,Ta_KIB_E.Judul, Ta_KIB_E.Pencipta ORDER BY  Ref_Rek_Aset5.Nm_Aset5, MIN(Ta_KIB_E.No_register)";  
	return $this->db->query($query);  
}


function total_laporan_all_kibe($like){
		$query = "SELECT COUNT(*) as Jumlah,SUM(Harga) as Harga FROM Ta_KIB_E where $like";    
   	    return $this->db->query($query);  
}      	
	
/* total kib e */
/* 02-07-2014 */
	function laporan_kodebarang($bidang,$unit,$sub,$upb,$like)
	{	
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "(Ta_KIB_E.Kd_Bidang = $bidang) 
			  AND (Ta_KIB_E.Kd_Unit = $unit) 
			  AND (Ta_KIB_E.Kd_Sub = $sub) 
			  AND (Ta_KIB_E.Kd_UPB = $upb) AND";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIB_E.Kd_Bidang = $kb )
			  AND (Ta_KIB_E.Kd_Unit =$ku )
			  AND (Ta_KIB_E.Kd_Sub = $ks) 
			  AND (Ta_KIB_E.Kd_UPB = $upb) AND";
		}else{
			$where = "(Ta_KIB_E.Kd_Bidang = $kb) 
			  AND (Ta_KIB_E.Kd_Unit = $ku) 
			  AND (Ta_KIB_E.Kd_Sub = $ks) 
			  AND (Ta_KIB_E.Kd_UPB = $kupb) AND";
		}

		$query = "select Ref_Rek_Aset5.Kd_Aset1,Ref_Rek_Aset5.Kd_Aset2,Ref_Rek_Aset5.Kd_Aset3,Ref_Rek_Aset5.Kd_Aset4,Ref_Rek_Aset5.Kd_Aset5,
Nm_Aset5,COUNT(*) as Jumlah, SUM(Harga) as Harga
from Ref_Rek_Aset5 RIGHT JOIN Ta_KIB_E ON 
Ta_KIB_E.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
Ta_KIB_E.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND
Ta_KIB_E.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
Ta_KIB_E.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
Ta_KIB_E.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5  WHERE $where $like
GROUP BY Ref_Rek_Aset5.Kd_Aset1,Ref_Rek_Aset5.Kd_Aset2,Ref_Rek_Aset5.Kd_Aset3,
Ref_Rek_Aset5.Kd_Aset4,Ref_Rek_Aset5.Kd_Aset5,Nm_Aset5 ORDER BY Ref_Rek_Aset5.Kd_Aset1,Ref_Rek_Aset5.Kd_Aset2,Ref_Rek_Aset5.Kd_Aset3,
Ref_Rek_Aset5.Kd_Aset4,Ref_Rek_Aset5.Kd_Aset5";
		return $this->db->query($query);
	}
	
	
	/**
	 * Tampilkan total_pengadaan
	 */
	function total_kodebarang($bidang,$unit,$sub,$upb,$like)
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
	
	/**  * Menampilkan data kib E Mutasi  */ 
 function kibe_mutasi_barang($like)
 {  
 $query = "SELECT Nm_UPB,Ref_Rek_Aset5.Nm_Aset5,COUNT(Ta_KIB_E.No_register) as jumlah_register,MIN(Ta_KIB_E.No_register) as min_register, MAX(Ta_KIB_E.No_register) as max_register, Ta_KIB_E.Kd_Prov, Ta_KIB_E.Kd_Kab_Kota, Ta_KIB_E.Kd_Bidang, Ta_KIB_E.Kd_Unit, Ta_KIB_E.Kd_Sub, Ta_KIB_E.Kd_UPB, Ta_KIB_E.Kd_Aset1, Ta_KIB_E.Kd_Aset2, Ta_KIB_E.Kd_Aset3,  Ta_KIB_E.Kd_Aset4, Ta_KIB_E.Kd_Aset5, Ta_KIB_E.Kd_Pemilik,DATENAME(yyyy,Ta_KIB_E.Tgl_Perolehan) as Tahun, Ta_KIB_E.Asal_usul,Ta_KIB_E.Kondisi,COUNT(*) as Jumlah, SUM(Ta_KIB_E.Harga) as Harga,Ta_KIB_E.Keterangan  FROM Ta_KIB_E INNER JOIN Ref_Rek_Aset5 ON Ta_KIB_E.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_E.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIB_E.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIB_E.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIB_E.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5  
INNER JOIN 
 					Ref_UPB ON Ta_KIB_E.Kd_Bidang=Ref_UPB.Kd_Bidang AND Ta_KIB_E.Kd_Unit=Ref_UPB.Kd_Unit AND Ta_KIB_E.Kd_Sub=Ref_UPB.Kd_Sub
 					 AND Ta_KIB_E.Kd_UPB=Ref_UPB.Kd_UPB  
 WHERE  $like  GROUP BY Ta_KIB_E.Kd_Prov, Ta_KIB_E.Kd_Kab_Kota, Ta_KIB_E.Kd_Bidang, Ta_KIB_E.Kd_Unit, Ta_KIB_E.Kd_Sub, Ta_KIB_E.Kd_UPB, Ta_KIB_E.Kd_Aset1, Ta_KIB_E.Kd_Aset2, Ta_KIB_E.Kd_Aset3,  Ta_KIB_E.Kd_Aset4, Ta_KIB_E.Kd_Aset5, Ta_KIB_E.Kd_Pemilik, Ta_KIB_E.Kd_Pemilik,Ta_KIB_E.Tgl_Perolehan,Ta_KIB_E.Asal_usul, Ta_KIB_E.Kondisi,Ta_KIB_E.Keterangan,Ref_Rek_Aset5.Nm_Aset5,Nm_UPB ORDER BY Ref_Rek_Aset5.Nm_Aset5, MIN(Ta_KIB_E.No_register)";  return $this->db->query($query);  }  

 /**  * Total Data Mutasi  */ 
function total_kibe_mutasi_barang($like){
	  $query = "SELECT COUNT(*) as Jumlah, SUM(Harga) as Harga FROM Ta_KIB_E where Kd_Aset1=5 AND $like"; 
		 return $this->db->query($query); 
 	}	

function rekap_mutasi_induk($like,$tahunawal,$tahunakhir)  {
	 	 $query = "SELECT
	Ref_Rek_Aset2.Kd_Aset1,
	Ref_Rek_Aset2.Kd_Aset2,
	Nm_Aset2,
	COUNT(case when Tgl_Perolehan < '$tahunawal' then 1 else NULL end) as Jumlah_awal,
	SUM(CASE WHEN Tgl_Perolehan < '$tahunawal' THEN Harga else 0 END) as Harga_awal,
	COUNT(case WHEN $like then 0 else null end) as Jumlah_tambah,
	SUM(CASE WHEN $like THEN Harga END) as Harga_tambah
FROM
	Ref_Rek_Aset2
LEFT JOIN (
	SELECT
		*
	FROM
		Ta_KIB_E
) AS Ta_KIB_E ON Ta_KIB_E.Kd_Aset1 = Ref_Rek_Aset2.Kd_Aset1
AND Ta_KIB_E.Kd_Aset2 = Ref_Rek_Aset2.Kd_Aset2
WHERE
	Ref_Rek_Aset2.Kd_Aset1 = 5
GROUP BY
	Ref_Rek_Aset2.Kd_Aset1,
	Ref_Rek_Aset2.Kd_Aset2,
	Nm_Aset2
ORDER BY
	Ref_Rek_Aset2.Kd_Aset2"; 
		
		return $this->db->query($query);  
}

function total_rekap_mutasi_induk($like,$tahunawal,$tahunakhir) {
 		
	$query = "SELECT
	COUNT(case when Tgl_Perolehan < '$tahunawal' then 1 else NULL end) as Jumlah_awal,
	SUM(CASE WHEN Tgl_Perolehan < '$tahunawal' THEN Harga else 0 END) as Harga_awal,
	COUNT(case WHEN $like then 0 else null end) as Jumlah_tambah,
	SUM(CASE WHEN $like THEN Harga END) as Harga_tambah,
	COUNT(case when Tgl_Perolehan <= '$tahunakhir' then 1 else NULL end) as Jumlah, 
	SUM(CASE WHEN Tgl_Perolehan <= '$tahunakhir' THEN Harga else 0 END) as Harga
FROM
	Ref_Rek_Aset2
LEFT JOIN (
	SELECT
		*
	FROM
		Ta_KIB_E
) AS Ta_KIB_E ON Ta_KIB_E.Kd_Aset1 = Ref_Rek_Aset2.Kd_Aset1
AND Ta_KIB_E.Kd_Aset2 = Ref_Rek_Aset2.Kd_Aset2
WHERE
	Ref_Rek_Aset2.Kd_Aset1 = 5";  
 	
	 	return $this->db->query($query);  
 	}  
		 
	 function kibe_rinventaris_rekap($like)  {
  	   	$where = "$like";
  	   $query = "select Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2,COUNT(*) as Jumlah, SUM(Harga)
	    as Harga from Ref_Rek_Aset2 LEFT JOIN (SELECT * from Ta_KIB_E where $where) as Ta_KIB_E ON  
	    Ta_KIB_E.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND Ta_KIB_E.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2 
	    WHERE Ref_Rek_Aset2.Kd_Aset1=5 GROUP BY Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2 ";  
  	   return $this->db->query($query);  
} 

function total_kibe_rinventaris_rekap($like)  {
   	$where = "$like";
   	    $query = "SELECT COUNT(*) as Jumlah,SUM(Harga) as Harga FROM Ta_KIB_E where $where";    
   	    return $this->db->query($query);  
   } 
   
	/* 06-07-2014 */
function laporan_all_rkodebarang($like){
	$query = "select Ref_Rek_Aset5.Kd_Aset1,Ref_Rek_Aset5.Kd_Aset2,Ref_Rek_Aset5.Kd_Aset3,Ref_Rek_Aset5.Kd_Aset4,Ref_Rek_Aset5.Kd_Aset5,
Nm_Aset5,COUNT(*) as Jumlah, SUM(Harga) as Harga,Nm_UPB
from Ref_Rek_Aset5 RIGHT JOIN Ta_KIB_E ON 
Ta_KIB_E.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND
Ta_KIB_E.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND
Ta_KIB_E.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND
Ta_KIB_E.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND
Ta_KIB_E.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 LEFT JOIN Ref_UPB ON Ta_KIB_E.Kd_Bidang=Ref_UPB.Kd_Bidang 
	AND Ta_KIB_E.Kd_Unit=Ref_UPB.Kd_Unit 
	AND Ta_KIB_E.Kd_Sub=Ref_UPB.Kd_Sub
 	AND Ta_KIB_E.Kd_UPB=Ref_UPB.Kd_UPB WHERE $like
GROUP BY Ref_Rek_Aset5.Kd_Aset1,Ref_Rek_Aset5.Kd_Aset2,Ref_Rek_Aset5.Kd_Aset3,
Ref_Rek_Aset5.Kd_Aset4,Ref_Rek_Aset5.Kd_Aset5,Nm_Aset5,Nm_UPB ORDER BY Ref_Rek_Aset5.Kd_Aset1,Ref_Rek_Aset5.Kd_Aset2,Ref_Rek_Aset5.Kd_Aset3,
Ref_Rek_Aset5.Kd_Aset4,Ref_Rek_Aset5.Kd_Aset5";  
	return $this->db->query($query);  
} 

/* 12-08-2014*/
function total_kib_skpd($bidang,$unit,$sub,$upb,$like){	
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		

		if ($this->session->userdata('lvl') == 01){
		$where = "WHERE (Ta_KIB_E.Kd_Bidang = $bidang) 
			  AND (Ta_KIB_E.Kd_Unit = $unit) 
			  AND (Ta_KIB_E.Kd_Sub = $sub) 
			  AND (Ta_KIB_E.Kd_UPB = $upb) AND $like";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "WHERE (Ta_KIB_E.Kd_Bidang = $kb )
			  AND (Ta_KIB_E.Kd_Unit =$ku )
			  AND (Ta_KIB_E.Kd_Sub = $ks) 
			  AND (Ta_KIB_E.Kd_UPB = $upb) AND $like";
		}else{
			$where = "WHERE (Ta_KIB_E.Kd_Bidang = $kb) 
			  AND (Ta_KIB_E.Kd_Unit = $ku) 
			  AND (Ta_KIB_E.Kd_Sub = $ks) 
			  AND (Ta_KIB_E.Kd_UPB = $kupb) AND $like";
		}
		$query = "SELECT COUNT(*) as Jumlah,SUM(Harga) as Harga FROM Ta_KIB_E $where";
		
        return $this->db->query($query);
	}

function laporan_skguna($like){	
		$query = "SELECT
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIBER.Kd_Prov,
				Ta_KIBER.Kd_Kab_Kota,
				Ta_KIBER.Kd_Bidang,
				Ta_KIBER.Kd_Unit,
				Ta_KIBER.Kd_Sub,
				Ta_KIBER.Kd_UPB,
				Ta_KIBER.Kd_Aset1,
				Ta_KIBER.Kd_Aset2,
				Ta_KIBER.Kd_Aset3,
				Ta_KIBER.Kd_Aset4,
				Ta_KIBER.Kd_Aset5,
				Ta_KIBER.Kd_Pemilik,
				Ta_KIBER.Judul,
				Ta_KIBER.Pencipta,
				DATENAME(
					yyyy,
					Ta_KIBER.Tgl_Perolehan
				) AS Tahun,
				COUNT (*) AS Jumlah_Data,
				SUM (Ta_KIBER.Harga) AS Harga,
				Nm_UPB
			FROM
				Ta_KIBER
			INNER JOIN Ref_Rek_Aset5 ON Ta_KIBER.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1
			AND Ta_KIBER.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2
			AND Ta_KIBER.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3
			AND Ta_KIBER.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4
			AND Ta_KIBER.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
			INNER JOIN Ref_UPB ON Ta_KIBER.Kd_Bidang=Ref_UPB.Kd_Bidang 
	AND Ta_KIBER.Kd_Unit=Ref_UPB.Kd_Unit 
	AND Ta_KIBER.Kd_Sub=Ref_UPB.Kd_Sub
 	AND Ta_KIBER.Kd_UPB=Ref_UPB.Kd_UPB
			WHERE Kd_Riwayat = 12 AND $like
			GROUP BY
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIBER.Kd_Prov,
				Ta_KIBER.Kd_Kab_Kota,
				Ta_KIBER.Kd_Bidang,
				Ta_KIBER.Kd_Unit,
				Ta_KIBER.Kd_Sub,
				Ta_KIBER.Kd_UPB,
				Ta_KIBER.Kd_Aset1,
				Ta_KIBER.Kd_Aset2,
				Ta_KIBER.Kd_Aset3,
				Ta_KIBER.Kd_Aset4,
				Ta_KIBER.Kd_Aset5,
				Ta_KIBER.Kd_Pemilik,
				Ta_KIBER.Judul,
				Ta_KIBER.Pencipta, DATENAME(
					yyyy,
					Ta_KIBER.Tgl_Perolehan
				),Nm_UPB";
		return $this->db->query($query);
	} 
	
function total_skguna($like){
	$this->db->select_SUM('Harga');  
	$this->db->where("$like"); 
	$this->db->where('Kd_Riwayat = 12');	  
	$this->db->from("Ta_KIBER");  
	$sql = $this->db->get();  
	foreach ($sql->result() as $row)
	{
		$result = $row->Harga;  
	}
	return $result;  
}

/**
	 * 08-06-2014 usul hapus
	 */
	function usul_hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
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
		  'Judul'	=> $query->Judul,
		  'Pencipta'	=> $query->Pencipta,
		  'Bahan'	=> $query->Bahan,
		  'Ukuran'	=> $query->Ukuran,
		  'Asal_usul'	=> $query->Asal_usul,
		  'Kondisi'	=> $query->Kondisi,
		  'Harga'	=> $query->Harga,
		  'Masa_Manfaat'	=> $query->Masa_Manfaat,
		  'Nilai_Sisa'	=> $query->Nilai_Sisa,
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
		  'Log_User'	=> $query->Log_User,
		  'Log_entry'	=> $query->Log_entry,
			'Kd_KA'		=> 1);
		
		$this->db->insert("Ta_KIBER", $data);
		return TRUE;
		
	}

	/**
	 * 08-06-2014 usul guna
	 */
	function usul_guna($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		$query = $this->db->get_where($this->table, array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register), 1)->row();

     		$data = array(
			'No_Urut'	=> NULL,
			'Kd_Riwayat'	=> 12,
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
		  'Tgl_Perolehan'	=> $query->Tgl_Perolehan,
		  'Tgl_Pembukuan'	=> $query->Tgl_Pembukuan,
		  'Judul'	=> $query->Judul,
		  'Pencipta'	=> $query->Pencipta,
		  'Bahan'	=> $query->Bahan,
		  'Ukuran'	=> $query->Ukuran,
		  'Asal_usul'	=> $query->Asal_usul,
		  'Kondisi'	=> $query->Kondisi,
		  'Harga'	=> $query->Harga,
		  'Masa_Manfaat'	=> $query->Masa_Manfaat,
		  'Nilai_Sisa'	=> $query->Nilai_Sisa,
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
		  'Log_User'	=> $query->Log_User,
		  'Log_entry'	=> $query->Log_entry,
			'Kd_KA'		=> 1);
		
		$this->db->insert("Ta_KIBER", $data);
		return TRUE;
		
	}


function laporan_skhapus($like){	
		$query = "SELECT
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIBER.Kd_Prov,
				Ta_KIBER.Kd_Kab_Kota,
				Ta_KIBER.Kd_Bidang,
				Ta_KIBER.Kd_Unit,
				Ta_KIBER.Kd_Sub,
				Ta_KIBER.Kd_UPB,
				Ta_KIBER.Kd_Aset1,
				Ta_KIBER.Kd_Aset2,
				Ta_KIBER.Kd_Aset3,
				Ta_KIBER.Kd_Aset4,
				Ta_KIBER.Kd_Aset5,
				Ta_KIBER.Kd_Pemilik,
				Ta_KIBER.Judul,
				Ta_KIBER.Pencipta,
				DATENAME(
					yyyy,
					Ta_KIBER.Tgl_Perolehan
				) AS Tahun,
				COUNT (*) AS Jumlah_Data,
				SUM (Ta_KIBER.Harga) AS Harga,
				Nm_UPB
			FROM
				Ta_KIBER
			INNER JOIN Ref_Rek_Aset5 ON Ta_KIBER.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1
			AND Ta_KIBER.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2
			AND Ta_KIBER.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3
			AND Ta_KIBER.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4
			AND Ta_KIBER.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
			INNER JOIN Ref_UPB ON Ta_KIBER.Kd_Bidang=Ref_UPB.Kd_Bidang 
	AND Ta_KIBER.Kd_Unit=Ref_UPB.Kd_Unit 
	AND Ta_KIBER.Kd_Sub=Ref_UPB.Kd_Sub
 	AND Ta_KIBER.Kd_UPB=Ref_UPB.Kd_UPB
			WHERE Kd_Riwayat = 7 AND $like
			GROUP BY
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIBER.Kd_Prov,
				Ta_KIBER.Kd_Kab_Kota,
				Ta_KIBER.Kd_Bidang,
				Ta_KIBER.Kd_Unit,
				Ta_KIBER.Kd_Sub,
				Ta_KIBER.Kd_UPB,
				Ta_KIBER.Kd_Aset1,
				Ta_KIBER.Kd_Aset2,
				Ta_KIBER.Kd_Aset3,
				Ta_KIBER.Kd_Aset4,
				Ta_KIBER.Kd_Aset5,
				Ta_KIBER.Kd_Pemilik,
				Ta_KIBER.Judul,
				Ta_KIBER.Pencipta, DATENAME(
					yyyy,
					Ta_KIBER.Tgl_Perolehan
				),Nm_UPB";
		return $this->db->query($query);
	} 
	
function total_skhapus($like){
	$this->db->select_SUM('Harga');  
	$this->db->where("$like"); 
	$this->db->where('Kd_Riwayat = 7');	  
	$this->db->from("Ta_KIBER");  
	$sql = $this->db->get();  
	foreach ($sql->result() as $row)
	{
		$result = $row->Harga;  
	}
	return $result;  
}

function laporan_usulhapus($bidang,$unit,$sub,$upb,$like)
	{	
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "(Ta_KIBER.Kd_Bidang = $bidang) 
			  AND (Ta_KIBER.Kd_Unit = $unit) 
			  AND (Ta_KIBER.Kd_Sub = $sub) 
			  AND (Ta_KIBER.Kd_UPB = $upb) AND";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIBER.Kd_Bidang = $kb )
			  AND (Ta_KIBER.Kd_Unit =$ku )
			  AND (Ta_KIBER.Kd_Sub = $ks) 
			  AND (Ta_KIBER.Kd_UPB = $upb) AND";
		}else{
			$where = "(Ta_KIBER.Kd_Bidang = $kb) 
			  AND (Ta_KIBER.Kd_Unit = $ku) 
			  AND (Ta_KIBER.Kd_Sub = $ks) 
			  AND (Ta_KIBER.Kd_UPB = $kupb) AND";
		}

		$query = "SELECT
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIBER.Kd_Prov,
				Ta_KIBER.Kd_Kab_Kota,
				Ta_KIBER.Kd_Bidang,
				Ta_KIBER.Kd_Unit,
				Ta_KIBER.Kd_Sub,
				Ta_KIBER.Kd_UPB,
				Ta_KIBER.Kd_Aset1,
				Ta_KIBER.Kd_Aset2,
				Ta_KIBER.Kd_Aset3,
				Ta_KIBER.Kd_Aset4,
				Ta_KIBER.Kd_Aset5,
				Ta_KIBER.Kd_Pemilik,
				Ta_KIBER.Judul,
				Ta_KIBER.Pencipta,
				DATENAME(
					yyyy,
					Ta_KIBER.Tgl_Perolehan
				) AS Tahun,
				COUNT (*) AS Jumlah_Data,
				SUM (Ta_KIBER.Harga) AS Harga,
				Nm_UPB
			FROM
				Ta_KIBER
			INNER JOIN Ref_Rek_Aset5 ON Ta_KIBER.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1
			AND Ta_KIBER.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2
			AND Ta_KIBER.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3
			AND Ta_KIBER.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4
			AND Ta_KIBER.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
			INNER JOIN Ref_UPB ON Ta_KIBER.Kd_Bidang=Ref_UPB.Kd_Bidang 
	AND Ta_KIBER.Kd_Unit=Ref_UPB.Kd_Unit 
	AND Ta_KIBER.Kd_Sub=Ref_UPB.Kd_Sub
 	AND Ta_KIBER.Kd_UPB=Ref_UPB.Kd_UPB
			WHERE $where Kd_Riwayat = 7 AND $like
			GROUP BY
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIBER.Kd_Prov,
				Ta_KIBER.Kd_Kab_Kota,
				Ta_KIBER.Kd_Bidang,
				Ta_KIBER.Kd_Unit,
				Ta_KIBER.Kd_Sub,
				Ta_KIBER.Kd_UPB,
				Ta_KIBER.Kd_Aset1,
				Ta_KIBER.Kd_Aset2,
				Ta_KIBER.Kd_Aset3,
				Ta_KIBER.Kd_Aset4,
				Ta_KIBER.Kd_Aset5,
				Ta_KIBER.Kd_Pemilik,
				Ta_KIBER.Judul,
				Ta_KIBER.Pencipta, DATENAME(
					yyyy,
					Ta_KIBER.Tgl_Perolehan
				),Nm_UPB";
		return $this->db->query($query);
	}
	
	
	/**
	 * Tampilkan total_pengadaan
	 */
	function total_usulhapus($bidang,$unit,$sub,$upb,$like)
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
		$this->db->where('Kd_Riwayat = 7');	
        $this->db->from('Ta_KIBER');
		$sql = $this->db->get();
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	}

	/* 02-07-2014 */
	/**
	 * Menampilkan laporan usul guna
	 */
	function laporan_usulguna($bidang,$unit,$sub,$upb,$like)
	{	
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "(Ta_KIBER.Kd_Bidang = $bidang) 
			  AND (Ta_KIBER.Kd_Unit = $unit) 
			  AND (Ta_KIBER.Kd_Sub = $sub) 
			  AND (Ta_KIBER.Kd_UPB = $upb) AND";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIBER.Kd_Bidang = $kb )
			  AND (Ta_KIBER.Kd_Unit =$ku )
			  AND (Ta_KIBER.Kd_Sub = $ks) 
			  AND (Ta_KIBER.Kd_UPB = $upb) AND";
		}else{
			$where = "(Ta_KIBER.Kd_Bidang = $kb) 
			  AND (Ta_KIBER.Kd_Unit = $ku) 
			  AND (Ta_KIBER.Kd_Sub = $ks) 
			  AND (Ta_KIBER.Kd_UPB = $kupb) AND";
		}

		$query = "SELECT
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIBER.Kd_Prov,
				Ta_KIBER.Kd_Kab_Kota,
				Ta_KIBER.Kd_Bidang,
				Ta_KIBER.Kd_Unit,
				Ta_KIBER.Kd_Sub,
				Ta_KIBER.Kd_UPB,
				Ta_KIBER.Kd_Aset1,
				Ta_KIBER.Kd_Aset2,
				Ta_KIBER.Kd_Aset3,
				Ta_KIBER.Kd_Aset4,
				Ta_KIBER.Kd_Aset5,
				Ta_KIBER.Kd_Pemilik,
				Ta_KIBER.Judul,
				Ta_KIBER.Pencipta,
				DATENAME(
					yyyy,
					Ta_KIBER.Tgl_Perolehan
				) AS Tahun,
				COUNT (*) AS Jumlah_Data,
				SUM (Ta_KIBER.Harga) AS Harga,
				Nm_UPB
			FROM
				Ta_KIBER
			INNER JOIN Ref_Rek_Aset5 ON Ta_KIBER.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1
			AND Ta_KIBER.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2
			AND Ta_KIBER.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3
			AND Ta_KIBER.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4
			AND Ta_KIBER.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
			INNER JOIN Ref_UPB ON Ta_KIBER.Kd_Bidang=Ref_UPB.Kd_Bidang 
	AND Ta_KIBER.Kd_Unit=Ref_UPB.Kd_Unit 
	AND Ta_KIBER.Kd_Sub=Ref_UPB.Kd_Sub
 	AND Ta_KIBER.Kd_UPB=Ref_UPB.Kd_UPB
			WHERE $where Kd_Riwayat = 12 AND $like
			GROUP BY
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIBER.Kd_Prov,
				Ta_KIBER.Kd_Kab_Kota,
				Ta_KIBER.Kd_Bidang,
				Ta_KIBER.Kd_Unit,
				Ta_KIBER.Kd_Sub,
				Ta_KIBER.Kd_UPB,
				Ta_KIBER.Kd_Aset1,
				Ta_KIBER.Kd_Aset2,
				Ta_KIBER.Kd_Aset3,
				Ta_KIBER.Kd_Aset4,
				Ta_KIBER.Kd_Aset5,
				Ta_KIBER.Kd_Pemilik,
				Ta_KIBER.Judul,
				Ta_KIBER.Pencipta, DATENAME(
					yyyy,
					Ta_KIBER.Tgl_Perolehan
				),Nm_UPB";
		return $this->db->query($query);
	}
	
	
	/**
	 * Tampilkan total_pengadaan
	 */
	function total_usulguna($bidang,$unit,$sub,$upb,$like)
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
		$this->db->where('Kd_Riwayat = 12');	
        $this->db->from('Ta_KIBER');
		$sql = $this->db->get();
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	} 

function laporan_ekstrakomptabel($bidang,$unit,$sub,$upb,$like){	
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "(Ta_KIB_E.Kd_Bidang = $bidang) 
			  AND (Ta_KIB_E.Kd_Unit = $unit) 
			  AND (Ta_KIB_E.Kd_Sub = $sub) 
			  AND (Ta_KIB_E.Kd_UPB = $upb) AND";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIB_E.Kd_Bidang = $kb )
			  AND (Ta_KIB_E.Kd_Unit =$ku )
			  AND (Ta_KIB_E.Kd_Sub = $ks) 
			  AND (Ta_KIB_E.Kd_UPB = $upb) AND";
		}else{
			$where = "(Ta_KIB_E.Kd_Bidang = $kb) 
			  AND (Ta_KIB_E.Kd_Unit = $ku) 
			  AND (Ta_KIB_E.Kd_Sub = $ks) 
			  AND (Ta_KIB_E.Kd_UPB = $kupb) AND";
		}

		$query = "SELECT
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIB_E.Kd_Prov,
				Ta_KIB_E.Kd_Kab_Kota,
				Ta_KIB_E.Kd_Bidang,
				Ta_KIB_E.Kd_Unit,
				Ta_KIB_E.Kd_Sub,
				Ta_KIB_E.Kd_UPB,
				Ta_KIB_E.Kd_Aset1,
				Ta_KIB_E.Kd_Aset2,
				Ta_KIB_E.Kd_Aset3,
				Ta_KIB_E.Kd_Aset4,
				Ta_KIB_E.Kd_Aset5,
				Ta_KIB_E.Kd_Pemilik,
				Ta_KIB_E.Judul,
				Ta_KIB_E.Pencipta,
				DATENAME(
					yyyy,
					Ta_KIB_E.Tgl_Perolehan
				) AS Tahun,
				COUNT (*) AS Jumlah_Data,
				SUM (Ta_KIB_E.Harga) AS Harga,
				Nm_UPB
			FROM
				Ta_KIB_E
			INNER JOIN Ref_Rek_Aset5 ON Ta_KIB_E.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1
			AND Ta_KIB_E.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2
			AND Ta_KIB_E.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3
			AND Ta_KIB_E.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4
			AND Ta_KIB_E.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
			INNER JOIN Ref_UPB ON Ta_KIB_E.Kd_Bidang=Ref_UPB.Kd_Bidang 
	AND Ta_KIB_E.Kd_Unit=Ref_UPB.Kd_Unit 
	AND Ta_KIB_E.Kd_Sub=Ref_UPB.Kd_Sub
 	AND Ta_KIB_E.Kd_UPB=Ref_UPB.Kd_UPB
			WHERE $where $like AND ekstrakomtabel = 'Y'
			GROUP BY
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIB_E.Kd_Prov,
				Ta_KIB_E.Kd_Kab_Kota,
				Ta_KIB_E.Kd_Bidang,
				Ta_KIB_E.Kd_Unit,
				Ta_KIB_E.Kd_Sub,
				Ta_KIB_E.Kd_UPB,
				Ta_KIB_E.Kd_Aset1,
				Ta_KIB_E.Kd_Aset2,
				Ta_KIB_E.Kd_Aset3,
				Ta_KIB_E.Kd_Aset4,
				Ta_KIB_E.Kd_Aset5,
				Ta_KIB_E.Kd_Pemilik,
				Ta_KIB_E.Judul,
				Ta_KIB_E.Pencipta, DATENAME(
					yyyy,
					Ta_KIB_E.Tgl_Perolehan
				),Nm_UPB";
		return $this->db->query($query);
	}

function total_ekstrakomptabel($bidang,$unit,$sub,$upb,$like){	
	
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
		$this->db->where("ekstrakomtabel = 'Y'");	
        $this->db->from($this->table);
		$sql = $this->db->get();
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	} 

/* start 2015 */
function laporan_intrakomptabel($bidang,$unit,$sub,$upb,$like){	
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		
		if ($this->session->userdata('lvl') == 01){
		$where = "(Ta_KIB_E.Kd_Bidang = $bidang) 
			  AND (Ta_KIB_E.Kd_Unit = $unit) 
			  AND (Ta_KIB_E.Kd_Sub = $sub) 
			  AND (Ta_KIB_E.Kd_UPB = $upb) AND";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = "(Ta_KIB_E.Kd_Bidang = $kb )
			  AND (Ta_KIB_E.Kd_Unit =$ku )
			  AND (Ta_KIB_E.Kd_Sub = $ks) 
			  AND (Ta_KIB_E.Kd_UPB = $upb) AND";
		}else{
			$where = "(Ta_KIB_E.Kd_Bidang = $kb) 
			  AND (Ta_KIB_E.Kd_Unit = $ku) 
			  AND (Ta_KIB_E.Kd_Sub = $ks) 
			  AND (Ta_KIB_E.Kd_UPB = $kupb) AND";
		}

		$query = "SELECT
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIB_E.Kd_Prov,
				Ta_KIB_E.Kd_Kab_Kota,
				Ta_KIB_E.Kd_Bidang,
				Ta_KIB_E.Kd_Unit,
				Ta_KIB_E.Kd_Sub,
				Ta_KIB_E.Kd_UPB,
				Ta_KIB_E.Kd_Aset1,
				Ta_KIB_E.Kd_Aset2,
				Ta_KIB_E.Kd_Aset3,
				Ta_KIB_E.Kd_Aset4,
				Ta_KIB_E.Kd_Aset5,
				Ta_KIB_E.Kd_Pemilik,
				Ta_KIB_E.Judul,
				Ta_KIB_E.Pencipta,
				DATENAME(
					yyyy,
					Ta_KIB_E.Tgl_Perolehan
				) AS Tahun,
				COUNT (*) AS Jumlah_Data,
				SUM (Ta_KIB_E.Harga) AS Harga,
				Nm_UPB
			FROM
				Ta_KIB_E
			INNER JOIN Ref_Rek_Aset5 ON Ta_KIB_E.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1
			AND Ta_KIB_E.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2
			AND Ta_KIB_E.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3
			AND Ta_KIB_E.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4
			AND Ta_KIB_E.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
			INNER JOIN Ref_UPB ON Ta_KIB_E.Kd_Bidang=Ref_UPB.Kd_Bidang 
	AND Ta_KIB_E.Kd_Unit=Ref_UPB.Kd_Unit 
	AND Ta_KIB_E.Kd_Sub=Ref_UPB.Kd_Sub
 	AND Ta_KIB_E.Kd_UPB=Ref_UPB.Kd_UPB
			WHERE $where $like AND ekstrakomtabel = ''
			GROUP BY
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIB_E.Kd_Prov,
				Ta_KIB_E.Kd_Kab_Kota,
				Ta_KIB_E.Kd_Bidang,
				Ta_KIB_E.Kd_Unit,
				Ta_KIB_E.Kd_Sub,
				Ta_KIB_E.Kd_UPB,
				Ta_KIB_E.Kd_Aset1,
				Ta_KIB_E.Kd_Aset2,
				Ta_KIB_E.Kd_Aset3,
				Ta_KIB_E.Kd_Aset4,
				Ta_KIB_E.Kd_Aset5,
				Ta_KIB_E.Kd_Pemilik,
				Ta_KIB_E.Judul,
				Ta_KIB_E.Pencipta, DATENAME(
					yyyy,
					Ta_KIB_E.Tgl_Perolehan
				),Nm_UPB";
		return $this->db->query($query);
	}

function total_intrakomptabel($bidang,$unit,$sub,$upb,$like){	
	
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
		$this->db->where("ekstrakomtabel = ''");	
        $this->db->from($this->table);
		$sql = $this->db->get();
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	} 
/* end 2015 */


function laporan_ekstrakomptabel_skpd($like){	
	
		$query = "SELECT
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIB_E.Kd_Prov,
				Ta_KIB_E.Kd_Kab_Kota,
				Ta_KIB_E.Kd_Bidang,
				Ta_KIB_E.Kd_Unit,
				Ta_KIB_E.Kd_Sub,
				Ta_KIB_E.Kd_UPB,
				Ta_KIB_E.Kd_Aset1,
				Ta_KIB_E.Kd_Aset2,
				Ta_KIB_E.Kd_Aset3,
				Ta_KIB_E.Kd_Aset4,
				Ta_KIB_E.Kd_Aset5,
				Ta_KIB_E.Kd_Pemilik,
				Ta_KIB_E.Judul,
				Ta_KIB_E.Pencipta,
				DATENAME(
					yyyy,
					Ta_KIB_E.Tgl_Perolehan
				) AS Tahun,
				COUNT (*) AS Jumlah_Data,
				SUM (Ta_KIB_E.Harga) AS Harga,
				Nm_UPB
			FROM
				Ta_KIB_E
			INNER JOIN Ref_Rek_Aset5 ON Ta_KIB_E.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1
			AND Ta_KIB_E.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2
			AND Ta_KIB_E.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3
			AND Ta_KIB_E.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4
			AND Ta_KIB_E.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
			INNER JOIN Ref_UPB ON Ta_KIB_E.Kd_Bidang=Ref_UPB.Kd_Bidang 
	AND Ta_KIB_E.Kd_Unit=Ref_UPB.Kd_Unit 
	AND Ta_KIB_E.Kd_Sub=Ref_UPB.Kd_Sub
 	AND Ta_KIB_E.Kd_UPB=Ref_UPB.Kd_UPB
			WHERE $like AND ekstrakomtabel = 'Y'
			GROUP BY
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIB_E.Kd_Prov,
				Ta_KIB_E.Kd_Kab_Kota,
				Ta_KIB_E.Kd_Bidang,
				Ta_KIB_E.Kd_Unit,
				Ta_KIB_E.Kd_Sub,
				Ta_KIB_E.Kd_UPB,
				Ta_KIB_E.Kd_Aset1,
				Ta_KIB_E.Kd_Aset2,
				Ta_KIB_E.Kd_Aset3,
				Ta_KIB_E.Kd_Aset4,
				Ta_KIB_E.Kd_Aset5,
				Ta_KIB_E.Kd_Pemilik,
				Ta_KIB_E.Judul,
				Ta_KIB_E.Pencipta, DATENAME(
					yyyy,
					Ta_KIB_E.Tgl_Perolehan
				),Nm_UPB";
		return $this->db->query($query);
	}

function total_ekstrakomptabel_skpd($like){
		$this->db->select_SUM('Harga');
		$this->db->where("$like");
		$this->db->where("ekstrakomtabel = 'Y'");	
        $this->db->from($this->table);
		$sql = $this->db->get();
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	}

/* start 2015 */
function laporan_intrakomptabel_induk($like){

       $query = "SELECT
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIB_E.Kd_Prov,
				Ta_KIB_E.Kd_Kab_Kota,
				Ta_KIB_E.Kd_Bidang,
				Ta_KIB_E.Kd_Unit,
				Ta_KIB_E.Kd_Sub,
				Ta_KIB_E.Kd_UPB,
				Ta_KIB_E.Kd_Aset1,
				Ta_KIB_E.Kd_Aset2,
				Ta_KIB_E.Kd_Aset3,
				Ta_KIB_E.Kd_Aset4,
				Ta_KIB_E.Kd_Aset5,
				Ta_KIB_E.Kd_Pemilik,
				Ta_KIB_E.Judul,
				Ta_KIB_E.Pencipta,
				DATENAME(
					yyyy,
					Ta_KIB_E.Tgl_Perolehan
				) AS Tahun,
				COUNT (*) AS Jumlah_Data,
				SUM (Ta_KIB_E.Harga) AS Harga,
				Nm_UPB
			FROM
				Ta_KIB_E
			INNER JOIN Ref_Rek_Aset5 ON Ta_KIB_E.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1
			AND Ta_KIB_E.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2
			AND Ta_KIB_E.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3
			AND Ta_KIB_E.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4
			AND Ta_KIB_E.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
			INNER JOIN Ref_UPB ON Ta_KIB_E.Kd_Bidang=Ref_UPB.Kd_Bidang 
	AND Ta_KIB_E.Kd_Unit=Ref_UPB.Kd_Unit 
	AND Ta_KIB_E.Kd_Sub=Ref_UPB.Kd_Sub
 	AND Ta_KIB_E.Kd_UPB=Ref_UPB.Kd_UPB
			WHERE $like AND ekstrakomtabel = ''
			GROUP BY
				Ref_Rek_Aset5.Nm_Aset5,
				Ta_KIB_E.Kd_Prov,
				Ta_KIB_E.Kd_Kab_Kota,
				Ta_KIB_E.Kd_Bidang,
				Ta_KIB_E.Kd_Unit,
				Ta_KIB_E.Kd_Sub,
				Ta_KIB_E.Kd_UPB,
				Ta_KIB_E.Kd_Aset1,
				Ta_KIB_E.Kd_Aset2,
				Ta_KIB_E.Kd_Aset3,
				Ta_KIB_E.Kd_Aset4,
				Ta_KIB_E.Kd_Aset5,
				Ta_KIB_E.Kd_Pemilik,
				Ta_KIB_E.Judul,
				Ta_KIB_E.Pencipta, DATENAME(
					yyyy,
					Ta_KIB_E.Tgl_Perolehan
				),Nm_UPB";
        return $this->db->query($query);
    }

	function total_intrakomptabel_induk($like)
	{	

		$this->db->select_SUM('Harga');
		$this->db->where("$like");
		$this->db->where("ekstrakomtabel = ''");	
        $this->db->from($this->table);
		$sql = $this->db->get();
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}
		return $result;
	} 
	/* end 2015 */

	/* 13-05-2015 */
	function neraca_kibe($bidang='',$unit='',$sub='',$upb='',$like)
	{			
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$where = "";
		if ($this->session->userdata('lvl') == 01){
			if($bidang){
				$where = "AND (Ta_KIB_E.Kd_Bidang = $bidang)";
			}
			if($unit){
				$where .= "AND (Ta_KIB_E.Kd_Unit = $unit)";
			}
			if($sub){
				$where .= "AND (Ta_KIB_E.Kd_Sub = $sub)";
			}
			if($upb){
				$where .= "AND (Ta_KIB_E.Kd_UPB = $upb)";
			}
		}elseif ($this->session->userdata('lvl') == 02){
			if($bidang){
				$where = "AND (Ta_KIB_E.Kd_Bidang = $kb)";
			}
			if($unit){
				$where .= "AND (Ta_KIB_E.Kd_Unit = $ku)";
			}
			if($sub){
				$where .= "AND (Ta_KIB_E.Kd_Sub = $ks)";
			}
			if($upb){
				$where .= "AND (Ta_KIB_E.Kd_UPB = $upb)";
			}
		}else{
			if($bidang){
				$where = "AND (Ta_KIB_E.Kd_Bidang = $kb)";
			}
			if($unit){
				$where .= "AND (Ta_KIB_E.Kd_Unit = $ku)";
			}
			if($sub){
				$where .= "AND (Ta_KIB_E.Kd_Sub = $ks)";
			}
			if($upb){
				$where .= "AND (Ta_KIB_E.Kd_UPB = $kupb)";
			}
		}

		$query = "SELECT Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2,
					SUM(Harga) as intra,
					NULL AS ekstra,
					NULL AS RB,
					NULL AS Susut
					
					FROM Ref_Rek_Aset2 
					LEFT JOIN (SELECT * FROM Ta_KIB_E WHERE 1=1 $where AND $like) as Ta_KIB_E ON 
					Ta_KIB_E.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND Ta_KIB_E.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2
					WHERE Ref_Rek_Aset2.Kd_Aset1=5
					GROUP BY Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2";

		return $this->db->query($query);
	}

	/**
	 * Total Rekap Kendaraan
	 */
	function total_neraca_kibe($bidang='',$unit='',$sub='',$upb='',$like)
	{			
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$where = "";

		if ($this->session->userdata('lvl') == 01){
			if($bidang){
				$where = "AND (Ta_KIB_E.Kd_Bidang = $bidang)";
			}
			if($unit){
				$where .= "AND (Ta_KIB_E.Kd_Unit = $unit)";
			}
			if($sub){
				$where .= "AND (Ta_KIB_E.Kd_Sub = $sub)";
			}
			if($upb){
				$where .= "AND (Ta_KIB_E.Kd_UPB = $upb)";
			}
		}elseif ($this->session->userdata('lvl') == 02){
			if($bidang){
				$where = "AND (Ta_KIB_E.Kd_Bidang = $kb)";
			}
			if($unit){
				$where .= "AND (Ta_KIB_E.Kd_Unit = $ku)";
			}
			if($sub){
				$where .= "AND (Ta_KIB_E.Kd_Sub = $ks)";
			}
			if($upb){
				$where .= "AND (Ta_KIB_E.Kd_UPB = $upb)";
			}
		}else{
			if($bidang){
				$where = "AND (Ta_KIB_E.Kd_Bidang = $kb)";
			}
			if($unit){
				$where .= "AND (Ta_KIB_E.Kd_Unit = $ku)";
			}
			if($sub){
				$where .= "AND (Ta_KIB_E.Kd_Sub = $ks)";
			}
			if($upb){
				$where .= "AND (Ta_KIB_E.Kd_UPB = $kupb)";
			}
		}

		$query = "SELECT SUM(Harga) as intra,
					NULL AS ekstra,
					NULL AS RB,
					NULL AS Susut
					
					FROM Ref_Rek_Aset2 
					LEFT JOIN (SELECT * FROM Ta_KIB_E WHERE 1=1 $where AND $like) as Ta_KIB_E ON 
					Ta_KIB_E.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND Ta_KIB_E.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2
					WHERE Ref_Rek_Aset2.Kd_Aset1=5";
        return $this->db->query($query);
	}

	/* 13-05-2015 */
	function rekap_neraca($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
	{			
	
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan = " AND Tgl_Pembukuan ".$like;
	$tgl_dokumen   = " AND Tgl_Dokumen <= '".$tahunakhir."'";
	$minSatuan     = $this->auth->getMinSatuan(5);
	$set_intra     = " LastHarga >= '$minSatuan'";
	$kondisi       = "  AND lastKondisi <> 3";
	$thn           = $this->session->userdata('tahun_anggaran');

	$query = "SELECT n.Kd_Aset1,n.Kd_Aset2,n.Nm_Aset2,
	SUM(CASE WHEN LastKondisi <> 3 AND Kd_KA = 0 THEN LastHarga END) as KIB_E_Non_Operasional,
	SUM(CASE WHEN $set_intra AND LastKondisi = 3 AND Kd_KA <> 0 THEN LastHarga END) as RB,
	SUM(CASE WHEN $set_intra AND LastKondisi <> 3 AND Kd_KA <> 0 THEN (CASE WHEN n.Kd_Aset1=5 AND n.Kd_Aset2=18 THEN Akm_Susut ELSE 0 END) END) as Akm_Susut,
	SUM(CASE WHEN $set_intra AND LastKondisi <> 3 AND Kd_KA <> 0 THEN LastHarga END) as NB_Akhir

	FROM Ref_Rek_Aset2 n LEFT JOIN (
	
	SELECT *,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,4) as NB_Akhir,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,2) as Akm_Susut
	 		 FROM (
	SELECT *,
	floor((Harga + ISNULL(Total_Koreksi, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as HargaKoreksi,
	floor((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
	 FROM  (
			SELECT n.Nm_Aset5, a1.*,
						(CASE WHEN NewKondisi is null THEN Kondisi ELSE NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 21
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Total_Koreksi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 2
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Kapitalisasi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,

/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1

					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 AND b.Tgl_Dokumen <= '{$tahunakhir}'
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM   Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
					                   ON d.No_SK=e.No_SK
					                   WHERE a1.Kd_Bidang=d.Kd_Bidang
										AND a1.Kd_Unit=d.Kd_Unit
										AND a1.Kd_Sub=d.Kd_Sub
										AND a1.Kd_UPB=d.Kd_UPB
										AND a1.Kd_Aset1=d.Kd_Aset1
										AND a1.Kd_Aset2=d.Kd_Aset2
										AND a1.Kd_Aset3=d.Kd_Aset3
										AND a1.Kd_Aset4=d.Kd_Aset4
										AND a1.Kd_Aset5=d.Kd_Aset5
										AND a1.No_Register=d.No_Register AND e.Tgl_SK <= '{$tahunakhir}')
							AND NOT EXISTS (SELECT 1 FROM  Ta_KIBER e 
					                   WHERE a1.Kd_Bidang=e.Kd_Bidang
										AND a1.Kd_Unit=e.Kd_Unit
										AND a1.Kd_Sub=e.Kd_Sub
										AND a1.Kd_UPB=e.Kd_UPB
										AND a1.Kd_Aset1=e.Kd_Aset1
										AND a1.Kd_Aset2=e.Kd_Aset2
										AND a1.Kd_Aset3=e.Kd_Aset3
										AND a1.Kd_Aset4=e.Kd_Aset4
										AND a1.Kd_Aset5=e.Kd_Aset5
										AND a1.No_Register=e.No_Register  AND Kd_Riwayat IN (3,19) AND e.Tgl_Dokumen <= '{$tahunakhir}')	

)as a2 ) as a WHERE 1=1 $where $tgl_pembukuan

) as a3 ON a3.Kd_Aset1=n.Kd_Aset1 AND a3.Kd_Aset2=n.Kd_Aset2
WHERE n.Kd_Aset1=5
GROUP BY n.Kd_Aset1,n.Kd_Aset2,n.Nm_Aset2 ORDER BY Kd_Aset2";

			// print_r($query); exit();
			return $this->db->query($query);
		}

	function rincian_neraca($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
	{			
	
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan = " AND Tgl_Pembukuan ".$like;
	$tgl_dokumen   = " AND Tgl_Dokumen <= '".$tahunakhir."'";
	$minSatuan     = $this->auth->getMinSatuan(5);
	$set_intra     = " LastHarga >= '$minSatuan'";
	$kondisi       = "  AND lastKondisi <> 3";
	$thn           = $this->session->userdata('tahun_anggaran');

	$query = "SELECT d5.Kd_Bidang,d5.Kd_Unit,d5.Kd_Sub,d5.Kd_UPB,
		d5.Kd_Aset1,d5.Kd_Aset2,d5.Kd_Aset3,d5.Kd_Aset4,d5.Kd_Aset5,
		d2.Nm_Aset2,d3.Nm_Aset3,d4.Nm_Aset4,d5.Nm_Aset5,
		SUM(CASE WHEN $set_intra AND d5.LastKondisi = 3 THEN d5.LastHarga END) as RB,
		SUM(CASE WHEN Kd_KA = 0 THEN d5.LastHarga END) as NB_Non_Operasional,
		SUM(CASE WHEN $set_intra AND d5.LastKondisi <> 3 THEN d5.LastHarga END) as Harga
		FROM  (
				SELECT *,
			((Harga + ISNULL(Total_Koreksi, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as HargaKoreksi,
			((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
			 FROM  (
					SELECT n.Nm_Aset5, a1.*,
						(CASE WHEN NewKondisi is null THEN Kondisi ELSE NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 21
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Total_Koreksi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 2
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Kapitalisasi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,

/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1

					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 AND YEAR(b.Tgl_Dokumen) <= '{$thn}'
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM   Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
					                   ON d.No_SK=e.No_SK
					                   WHERE a1.Kd_Bidang=d.Kd_Bidang
										AND a1.Kd_Unit=d.Kd_Unit
										AND a1.Kd_Sub=d.Kd_Sub
										AND a1.Kd_UPB=d.Kd_UPB
										AND a1.Kd_Aset1=d.Kd_Aset1
										AND a1.Kd_Aset2=d.Kd_Aset2
										AND a1.Kd_Aset3=d.Kd_Aset3
										AND a1.Kd_Aset4=d.Kd_Aset4
										AND a1.Kd_Aset5=d.Kd_Aset5
										AND a1.No_Register=d.No_Register AND YEAR(e.Tgl_SK) <= '{$thn}')
							AND NOT EXISTS (SELECT 1 FROM   Ta_KIBER e 
					                   WHERE a1.Kd_Bidang=e.Kd_Bidang
										AND a1.Kd_Unit=e.Kd_Unit
										AND a1.Kd_Sub=e.Kd_Sub
										AND a1.Kd_UPB=e.Kd_UPB
										AND a1.Kd_Aset1=e.Kd_Aset1
										AND a1.Kd_Aset2=e.Kd_Aset2
										AND a1.Kd_Aset3=e.Kd_Aset3
										AND a1.Kd_Aset4=e.Kd_Aset4
										AND a1.Kd_Aset5=e.Kd_Aset5
										AND a1.No_Register=e.No_Register  AND Kd_Riwayat IN (3,19) AND YEAR(e.Tgl_Dokumen) <= '{$thn}')	

) as a WHERE 1=1 $where $tgl_pembukuan )
as d5 LEFT JOIN Ref_Rek_Aset4 d4 ON d5.Kd_Aset1=d4.Kd_Aset1 AND d5.Kd_Aset2=d4.Kd_Aset2 AND d5.Kd_Aset3=d4.Kd_Aset3 AND d5.Kd_Aset4=d4.Kd_Aset4
LEFT JOIN Ref_Rek_Aset3 d3 ON d5.Kd_Aset1=d3.Kd_Aset1 AND d5.Kd_Aset2=d3.Kd_Aset2 AND d5.Kd_Aset3=d3.Kd_Aset3
LEFT JOIN Ref_Rek_Aset2 d2 ON d5.Kd_Aset1=d2.Kd_Aset1 AND d5.Kd_Aset2=d2.Kd_Aset2
GROUP BY d5.Kd_Bidang,d5.Kd_Unit,d5.Kd_Sub,d5.Kd_UPB,
d5.Kd_Aset1,d5.Kd_Aset2,d5.Kd_Aset3,d5.Kd_Aset4,d5.Kd_Aset5,
d2.Nm_Aset2,d3.Nm_Aset3,d4.Nm_Aset4,d5.Nm_Aset5";

			// print_r($query); exit();
			return $this->db->query($query);
		}


	/**
	 * Menampilkan semua data per Kondisi
	 */
	function kib_kondisi($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like,$kondisi)
	{			
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan 	= " AND Tgl_Pembukuan ".$like;
	$tgl_dokumen 	= " AND Tgl_Dokumen <= '".$tahunakhir."'";

	$query = "SELECT a.Nm_Aset5,COUNT(a.No_register) as jumlah_register,
	MIN(a.No_register) as min_register, MAX(a.No_register) as max_register,
	a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
	a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
	a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik,DATENAME(yyyy,a.Tgl_Perolehan) as Tahun,
	a.Asal_usul,a.Judul,a.Pencipta,a.LastKondisi,COUNT(*) as Jumlah,
	SUM(LastHarga) as  Harga,
			a.Keterangan FROM
			(
				SELECT *,((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
					FROM (	
						SELECT n.Nm_Aset5, a1.*,
						(CASE WHEN NewKondisi is null THEN Kondisi ELSE NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,

/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1

					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 AND YEAR(b.Tgl_Dokumen) <= '{$thn}'
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM   Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
					                   ON d.No_SK=e.No_SK
					                   WHERE a1.Kd_Bidang=d.Kd_Bidang
										AND a1.Kd_Unit=d.Kd_Unit
										AND a1.Kd_Sub=d.Kd_Sub
										AND a1.Kd_UPB=d.Kd_UPB
										AND a1.Kd_Aset1=d.Kd_Aset1
										AND a1.Kd_Aset2=d.Kd_Aset2
										AND a1.Kd_Aset3=d.Kd_Aset3
										AND a1.Kd_Aset4=d.Kd_Aset4
										AND a1.Kd_Aset5=d.Kd_Aset5
										AND a1.No_Register=d.No_Register AND YEAR(e.Tgl_SK) <= '{$thn}')
							AND NOT EXISTS (SELECT 1 FROM   Ta_KIBER e 
					                   WHERE a1.Kd_Bidang=e.Kd_Bidang
										AND a1.Kd_Unit=e.Kd_Unit
										AND a1.Kd_Sub=e.Kd_Sub
										AND a1.Kd_UPB=e.Kd_UPB
										AND a1.Kd_Aset1=e.Kd_Aset1
										AND a1.Kd_Aset2=e.Kd_Aset2
										AND a1.Kd_Aset3=e.Kd_Aset3
										AND a1.Kd_Aset4=e.Kd_Aset4
										AND a1.Kd_Aset5=e.Kd_Aset5
										AND a1.No_Register=e.No_Register  AND Kd_Riwayat IN (3,19) AND YEAR(e.Tgl_Dokumen) <= '{$thn}')			
						) as x

			) as a 
WHERE 1=1 $where $tgl_pembukuan $kondisi

GROUP BY a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik, a.Kd_Pemilik,a.Tgl_Perolehan,
a.Asal_usul,a.Judul,a.Pencipta,a.lastKondisi,a.Keterangan,a.Nm_Aset5,a.LastKondisi
ORDER BY  a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3,a.Kd_Aset4, a.Kd_Aset5";

			// print_r($query); exit();
			return $this->db->query($query);
		}

	function get_riwayat($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

    $sql = "SELECT  * FROM  (
			SELECT  Ref_Rek_Aset5.Nm_Aset5, Ref_Riwayat.Nm_Riwayat,a.*,
			ROW_NUMBER() OVER (ORDER BY a.Tgl_Pembukuan DESC) AS 
			halaman		FROM  Ta_KIBER a INNER JOIN Ref_Rek_Aset5 ON 
			a.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND a.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
			AND a.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND a.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
			AND a.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 LEFT JOIN Ref_Riwayat ON a.Kd_Riwayat=Ref_Riwayat.Kd_Riwayat 
			WHERE 1=1 $where $like) AS Ta_KIBER
			WHERE halaman BETWEEN $first AND $last  ORDER BY halaman ASC";

		// print_r($sql); exit();
			
 		return $this->db->query($sql);
 	}
	
	/* count kib b total page */
	function total_riwayat($where, $like) {
		$sql= "SELECT     SUM(Harga) as Harga, COUNT(1) as Jumlah FROM Ta_KIBER a 
		INNER JOIN Ref_Rek_Aset5 ON a.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND 
		a.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND a.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
		AND a.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND a.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5
		WHERE 1=1 $where $like";

		// print_r($sql); exit();

 		return $this->db->query($sql)->row();
    }


	function get_riwayat_barang($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
								$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register){

    	$where = "";
		$where .= " AND Ta_KIBER.Kd_Prov=".$kd_prov;
		$where .= " AND Ta_KIBER.Kd_Kab_Kota=".$kd_kab; 
		$where .= " AND Ta_KIBER.Kd_Bidang=".$kd_bidang; 
		$where .= " AND Ta_KIBER.Kd_Unit=".$kd_unit; 
		$where .= " AND Ta_KIBER.Kd_Sub=".$kd_sub; 
		$where .= " AND Ta_KIBER.Kd_UPB=".$kd_upb; 
		$where .= " AND Ta_KIBER.Kd_Aset1=".$kd_aset1; 
		$where .= " AND Ta_KIBER.Kd_Aset2=".$kd_aset2; 
		$where .= " AND Ta_KIBER.Kd_Aset3=".$kd_aset3; 
		$where .= " AND Ta_KIBER.Kd_Aset4=".$kd_aset4; 
		$where .= " AND Ta_KIBER.Kd_Aset5=".$kd_aset5;
		$where .= " AND Ta_KIBER.No_register=".$no_register;
		// $where .= " AND YEAR(Ta_KIBER.Tgl_Dokumen) <= '{$this->session->userdata('tahun_anggaran')}'";

		$sql = "SELECT  * FROM  (
			SELECT  Ref_Rek_Aset5.Nm_Aset5, Ref_Riwayat.Nm_Riwayat,Ta_KIBER.*, ROW_NUMBER() OVER (ORDER BY Ta_KIBER.Log_entry DESC) AS 
			halaman		FROM  Ta_KIBER INNER JOIN Ref_Rek_Aset5 ON 
			Ta_KIBER.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIBER.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
			AND Ta_KIBER.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIBER.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
			AND Ta_KIBER.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 LEFT JOIN Ref_Riwayat ON Ta_KIBER.Kd_Riwayat=Ref_Riwayat.Kd_Riwayat 
			WHERE 1=1 $where) AS Ta_KIBER";

 		return $this->db->query($sql);
 	}

	/**
	 * Menampilkan semua data kib b rekap
	 */
	function rekap_inventaris($bidang='',$unit='',$sub='',$upb='',$like)
	{			
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan 	= " AND Tgl_Pembukuan ".$like;
	$tgl_dokumen 	= " AND Tgl_Dokumen ".$like;

			$query = "SELECT Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2,COUNT(Harga) as Jumlah,
	SUM ((Harga+ (CASE WHEN Koreksi_Tambah is null THEN 0 ELSE Koreksi_Tambah END) ) - (CASE WHEN Koreksi_Kurang is null THEN 0 ELSE Koreksi_Kurang END) ) AS Harga
	FROM Ref_Rek_Aset2 LEFT JOIN (
					SELECT * FROM (SELECT n.Nm_Aset5,a1.Kd_Prov,a1.Kd_Kab_Kota,
(CASE WHEN Kd_Bidang1 IS NULL THEN a1.Kd_Bidang ELSE Kd_Bidang1 END) as Kd_Bidang,
(CASE WHEN Kd_Unit1 IS NULL THEN a1.Kd_Unit ELSE Kd_Unit1 END) as Kd_Unit,
(CASE WHEN Kd_Sub1 IS NULL THEN a1.Kd_Sub ELSE Kd_Sub1 END) as Kd_Sub,
(CASE WHEN Kd_UPB1 IS NULL THEN a1.Kd_UPB ELSE Kd_UPB1 END) as Kd_UPB,
a1.Kd_Aset1,a1.Kd_Aset2,a1.Kd_Aset3,a1.Kd_Aset4,a1.Kd_Aset5,a1.No_Register,
a1.Kd_Ruang,a1.Kd_Pemilik,a1.Tgl_Perolehan,a1.Judul,a1.Pencipta,a1.Bahan,
a1.Ukuran,a1.Asal_usul,(CASE WHEN b.NewKondisi is null THEN a1.Kondisi ELSE b.NewKondisi END) as lastKondisi,
a1.Harga,a1.Keterangan,a1.Tgl_Pembukuan,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,
/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1

					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 AND YEAR(b.Tgl_Dokumen) <= '{$thn}'
					             ORDER BY b.Tgl_Dokumen DESC) as b

					OUTER APPLY (SELECT TOP 1 Kd_Bidang1,Kd_Unit1,Kd_Sub1,Kd_UPB1
					             FROM Ta_KIBER c
					             WHERE a1.Kd_Bidang=c.Kd_Bidang
											AND a1.Kd_Unit=c.Kd_Unit
											AND a1.Kd_Sub=c.Kd_Sub
											AND a1.Kd_UPB=c.Kd_UPB
											AND a1.Kd_Aset1=c.Kd_Aset1
											AND a1.Kd_Aset2=c.Kd_Aset2
											AND a1.Kd_Aset3=c.Kd_Aset3
											AND a1.Kd_Aset4=c.Kd_Aset4
											AND a1.Kd_Aset5=c.Kd_Aset5
											AND a1.No_Register=c.No_Register AND Kd_Riwayat IN (3,19)  AND YEAR(c.Tgl_Dokumen) <= '{$thn}'
					             ORDER BY c.Tgl_Dokumen DESC) as c
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM   Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
					                   ON d.No_SK=e.No_SK
					                   WHERE a1.Kd_Bidang=d.Kd_Bidang
										AND a1.Kd_Unit=d.Kd_Unit
										AND a1.Kd_Sub=d.Kd_Sub
										AND a1.Kd_UPB=d.Kd_UPB
										AND a1.Kd_Aset1=d.Kd_Aset1
										AND a1.Kd_Aset2=d.Kd_Aset2
										AND a1.Kd_Aset3=d.Kd_Aset3
										AND a1.Kd_Aset4=d.Kd_Aset4
										AND a1.Kd_Aset5=d.Kd_Aset5
										AND a1.No_Register=d.No_Register AND YEAR(e.Tgl_SK) <= '{$thn}')			

)as a WHERE 1=1 $where $tgl_pembukuan) as a ON 
			a.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND a.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2

			WHERE Ref_Rek_Aset2.Kd_Aset1=5
			GROUP BY Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2";	
			// print_r($query); exit();
			return $this->db->query($query);
		}

	/**
	 * Menampilkan semua data kib e rekap
	 */
	function total_rekap_inventaris($bidang='',$unit='',$sub='',$upb='',$like)
	{			
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$where = "";

		if ($this->session->userdata('lvl') == 01){
			if($bidang){
				$where = " AND (a.Kd_Bidang = $bidang)";
			}
			if($unit){
				$where .= " AND (a.Kd_Unit = $unit)";
			}
			if($sub){
				$where .= " AND (a.Kd_Sub = $sub)";
			}
			if($upb){
				$where .= " AND (a.Kd_UPB = $upb)";
			}
		}elseif ($this->session->userdata('lvl') == 02){
			if($bidang){
				$where = " AND (a.Kd_Bidang = $kb)";
			}
			if($unit){
				$where .= " AND (a.Kd_Unit = $ku)";
			}
			if($sub){
				$where .= " AND (a.Kd_Sub = $ks)";
			}
			if($upb){
				$where .= " AND (a.Kd_UPB = $upb)";
			}
		}else{
			if($bidang){
				$where = " AND (a.Kd_Bidang = $kb)";
			}
			if($unit){
				$where .= " AND (a.Kd_Unit = $ku)";
			}
			if($sub){
				$where .= " AND (a.Kd_Sub = $ks)";
			}
			if($upb){
				$where .= " AND (a.Kd_UPB = $kupb)";
			}
		}

		$tgl_pembukuan 	= " AND Tgl_Pembukuan ".$like;
		$tgl_dokumen 	= " AND Tgl_Dokumen ".$like;

		$query = "SELECT SUM (Harga) AS Harga, SUM (Kondisi_RB) AS Kondisi_RB, SUM (Kapitalisasi) AS Kapitalisasi,
		SUM (Pindah_SKPD) AS Pindah_SKPD, SUM (Penghapusan) AS Penghapusan, SUM (Ubah_data) AS Ubah_data,
		SUM (Koreksi_Tambah) AS Koreksi_Tambah,SUM (Koreksi_Kurang) AS Koreksi_Kurang,
		COUNT(*) as Jumlah FROM (
	SELECT *,
	/* ----------- jumlah Kondisi_RB ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '1' AND a2.Kondisi='3'
					AND a.Kd_Bidang = a2.Kd_Bidang
					AND a.Kd_Unit = a2.Kd_Unit
					AND a.Kd_Sub = a2.Kd_Sub
					AND a.Kd_UPB = a2.Kd_UPB
					AND a.Kd_Aset1 = a2.Kd_Aset1
					AND a.Kd_Aset2 = a2.Kd_Aset2
					AND a.Kd_Aset3 = a2.Kd_Aset3
					AND a.Kd_Aset4 = a2.Kd_Aset4
					AND a.Kd_Aset5 = a2.Kd_Aset5
					AND a.No_Register = a2.No_Register $tgl_dokumen
					) AS Kondisi_RB,
	/* ----------- jumlah kapitalisasi ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '2'
					AND a.Kd_Bidang = a2.Kd_Bidang
					AND a.Kd_Unit = a2.Kd_Unit
					AND a.Kd_Sub = a2.Kd_Sub
					AND a.Kd_UPB = a2.Kd_UPB
					AND a.Kd_Aset1 = a2.Kd_Aset1
					AND a.Kd_Aset2 = a2.Kd_Aset2
					AND a.Kd_Aset3 = a2.Kd_Aset3
					AND a.Kd_Aset4 = a2.Kd_Aset4
					AND a.Kd_Aset5 = a2.Kd_Aset5
					AND a.No_Register = a2.No_Register $tgl_dokumen
					) AS Kapitalisasi,
	/* ----------- jumlah pengurangan PINDAH SKPD------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '3'
					AND a.Kd_Bidang = a2.Kd_Bidang
					AND a.Kd_Unit = a2.Kd_Unit
					AND a.Kd_Sub = a2.Kd_Sub
					AND a.Kd_UPB = a2.Kd_UPB
					AND a.Kd_Aset1 = a2.Kd_Aset1
					AND a.Kd_Aset2 = a2.Kd_Aset2
					AND a.Kd_Aset3 = a2.Kd_Aset3
					AND a.Kd_Aset4 = a2.Kd_Aset4
					AND a.Kd_Aset5 = a2.Kd_Aset5
					AND a.No_Register = a2.No_Register $tgl_dokumen
					) AS Pindah_SKPD,
	/* ----------- jumlah pengurangan PENGHAPUSAN------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '7'
					AND a.Kd_Bidang = a2.Kd_Bidang
					AND a.Kd_Unit = a2.Kd_Unit
					AND a.Kd_Sub = a2.Kd_Sub
					AND a.Kd_UPB = a2.Kd_UPB
					AND a.Kd_Aset1 = a2.Kd_Aset1
					AND a.Kd_Aset2 = a2.Kd_Aset2
					AND a.Kd_Aset3 = a2.Kd_Aset3
					AND a.Kd_Aset4 = a2.Kd_Aset4
					AND a.Kd_Aset5 = a2.Kd_Aset5
					AND a.No_Register = a2.No_Register $tgl_dokumen
					) AS Penghapusan,
	/* ----------- jumlah pengurangan Ubah data------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '18'
					AND a.Kd_Bidang = a2.Kd_Bidang
					AND a.Kd_Unit = a2.Kd_Unit
					AND a.Kd_Sub = a2.Kd_Sub
					AND a.Kd_UPB = a2.Kd_UPB
					AND a.Kd_Aset1 = a2.Kd_Aset1
					AND a.Kd_Aset2 = a2.Kd_Aset2
					AND a.Kd_Aset3 = a2.Kd_Aset3
					AND a.Kd_Aset4 = a2.Kd_Aset4
					AND a.Kd_Aset5 = a2.Kd_Aset5
					AND a.No_Register = a2.No_Register $tgl_dokumen
					) AS Ubah_data,
	/* ----------- jumlah penambahan Koreksi Nilai Penambahan------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '21'
					AND a.Kd_Bidang = a2.Kd_Bidang
					AND a.Kd_Unit = a2.Kd_Unit
					AND a.Kd_Sub = a2.Kd_Sub
					AND a.Kd_UPB = a2.Kd_UPB
					AND a.Kd_Aset1 = a2.Kd_Aset1
					AND a.Kd_Aset2 = a2.Kd_Aset2
					AND a.Kd_Aset3 = a2.Kd_Aset3
					AND a.Kd_Aset4 = a2.Kd_Aset4
					AND a.Kd_Aset5 = a2.Kd_Aset5
					AND a.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,
		/* ----------- jumlah penambahan Koreksi Nilai Pengurangan------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '23'
					AND a.Kd_Bidang = a2.Kd_Bidang
					AND a.Kd_Unit = a2.Kd_Unit
					AND a.Kd_Sub = a2.Kd_Sub
					AND a.Kd_UPB = a2.Kd_UPB
					AND a.Kd_Aset1 = a2.Kd_Aset1
					AND a.Kd_Aset2 = a2.Kd_Aset2
					AND a.Kd_Aset3 = a2.Kd_Aset3
					AND a.Kd_Aset4 = a2.Kd_Aset4
					AND a.Kd_Aset5 = a2.Kd_Aset5
					AND a.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang FROM Ta_KIB_E a WHERE 1=1 $where $tgl_pembukuan) as Ta_KIB_E";
			/*print_r($query); exit();*/
			return $this->db->query($query);
		}

	/**
	 * Menampilkan semua data kib b
	 */
	function bi($bidang='',$unit='',$sub='',$upb='', $tahunawal, $tahunakhir,$like)
	{			
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan 	= " AND Tgl_Pembukuan ".$like;
	$tgl_dokumen 	= " AND Tgl_Dokumen <= '".$tahunakhir."'";

	$query = "SELECT a.Nm_Aset5,COUNT(a.No_register) as jumlah_register,
	MIN(a.No_register) as min_register, MAX(a.No_register) as max_register,
	a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
	a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
	a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik,DATENAME(yyyy,a.Tgl_Perolehan) as Tahun,
	a.Asal_usul,a.Judul,a.Pencipta,a.LastKondisi,COUNT(*) as Jumlah,
	SUM(LastHarga) as  Harga,
			a.Keterangan FROM
			(
				SELECT *,((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
					FROM (	
						SELECT n.Nm_Aset5, a1.*,
						(CASE WHEN NewKondisi is null THEN Kondisi ELSE NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,

/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1

					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 AND YEAR(b.Tgl_Dokumen) <= '{$thn}'
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM   Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
					                   ON d.No_SK=e.No_SK
					                   WHERE a1.Kd_Bidang=d.Kd_Bidang
										AND a1.Kd_Unit=d.Kd_Unit
										AND a1.Kd_Sub=d.Kd_Sub
										AND a1.Kd_UPB=d.Kd_UPB
										AND a1.Kd_Aset1=d.Kd_Aset1
										AND a1.Kd_Aset2=d.Kd_Aset2
										AND a1.Kd_Aset3=d.Kd_Aset3
										AND a1.Kd_Aset4=d.Kd_Aset4
										AND a1.Kd_Aset5=d.Kd_Aset5
										AND a1.No_Register=d.No_Register AND YEAR(e.Tgl_SK) <= '{$thn}')
							AND NOT EXISTS (SELECT 1 FROM   Ta_KIBER e 
					                   WHERE a1.Kd_Bidang=e.Kd_Bidang
										AND a1.Kd_Unit=e.Kd_Unit
										AND a1.Kd_Sub=e.Kd_Sub
										AND a1.Kd_UPB=e.Kd_UPB
										AND a1.Kd_Aset1=e.Kd_Aset1
										AND a1.Kd_Aset2=e.Kd_Aset2
										AND a1.Kd_Aset3=e.Kd_Aset3
										AND a1.Kd_Aset4=e.Kd_Aset4
										AND a1.Kd_Aset5=e.Kd_Aset5
										AND a1.No_Register=e.No_Register  AND Kd_Riwayat IN (3,19) AND YEAR(e.Tgl_Dokumen) <= '{$thn}')			
						) as x

			) as a 
WHERE 1=1 $where $tgl_pembukuan

GROUP BY a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik, a.Kd_Pemilik,a.Tgl_Perolehan,
a.Asal_usul,a.Judul,a.Pencipta,a.lastKondisi,a.Keterangan,a.Nm_Aset5,a.LastKondisi
ORDER BY  a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3,a.Kd_Aset4, a.Kd_Aset5";

			// print_r($query); exit();
			return $this->db->query($query);
		}


	/**
	 * Menampilkan semua data per Kondisi
	 */
	function kib_eksint($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like,$kode,$kondisi)
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan = " AND Tgl_Pembukuan ".$like;
	$tgl_dokumen   = " AND Tgl_Dokumen <= '".$tahunakhir."'";
	$thn           =  $this->session->userdata('tahun_anggaran');
	$set_non_op    = " AND Kd_KA <> 0";
	$minSatuan     = $this->auth->getMinSatuan(5);
	if($kode == 1){
		$kode = " AND LastHarga < $minSatuan";
	}else{
		$kode = " AND LastHarga >= $minSatuan";
	}
	$query = "SELECT a.Nm_Aset5,COUNT(a.No_register) as jumlah_register,
			MIN(a.No_register) as min_register,
			MAX(a.No_register) as max_register,
			a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
			a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
			a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik,DATENAME(yyyy,a.Tgl_Perolehan) as Tahun,
			a.Asal_usul,a.LastKondisi,
			COUNT(CASE WHEN lastKondisi = 1 THEN 1 ELSE null END) as Baik,
			COUNT(CASE WHEN lastKondisi = 2 THEN 1 ELSE null END) as Kurang_baik,
			COUNT(CASE WHEN lastKondisi = 3 THEN 1 ELSE null END) as Rusak,
			COUNT(*) as Jumlah,
			SUM(LastHarga) as Harga,
			a.Keterangan FROM
			(
				SELECT *,floor((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga FROM (	
						SELECT n.Nm_Aset5, a1.*,(CASE WHEN b.NewKondisi is null THEN a1.Kondisi ELSE b.NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,

/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1
					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register
											AND Kd_Riwayat=1 
											AND b.Tgl_Dokumen <= '{$tahunakhir}'
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
					                   ON d.No_SK=e.No_SK
					                   WHERE a1.Kd_Bidang=d.Kd_Bidang
										AND a1.Kd_Unit=d.Kd_Unit
										AND a1.Kd_Sub=d.Kd_Sub
										AND a1.Kd_UPB=d.Kd_UPB
										AND a1.Kd_Aset1=d.Kd_Aset1
										AND a1.Kd_Aset2=d.Kd_Aset2
										AND a1.Kd_Aset3=d.Kd_Aset3
										AND a1.Kd_Aset4=d.Kd_Aset4
										AND a1.Kd_Aset5=d.Kd_Aset5
										AND a1.No_Register=d.No_Register
										AND e.Tgl_SK <= '{$tahunakhir}')
							AND NOT EXISTS (SELECT 1 FROM Ta_KIBER e 
					                   WHERE a1.Kd_Bidang=e.Kd_Bidang
										AND a1.Kd_Unit=e.Kd_Unit
										AND a1.Kd_Sub=e.Kd_Sub
										AND a1.Kd_UPB=e.Kd_UPB
										AND a1.Kd_Aset1=e.Kd_Aset1
										AND a1.Kd_Aset2=e.Kd_Aset2
										AND a1.Kd_Aset3=e.Kd_Aset3
										AND a1.Kd_Aset4=e.Kd_Aset4
										AND a1.Kd_Aset5=e.Kd_Aset5
										AND a1.No_Register=e.No_Register 
										AND Kd_Riwayat IN (3,19)
										AND e.Tgl_Dokumen <= '{$tahunakhir}')			
						) as x

			) as a 
WHERE 1=1 $where $tgl_pembukuan $kode $kondisi $set_non_op

GROUP BY a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik, a.Kd_Pemilik,a.Tgl_Perolehan,a.Asal_usul,
a.Kondisi,a.Keterangan,a.Nm_Aset5,a.LastKondisi";

			// print_r($query); exit();
			return $this->db->query($query);
		}

	/**
	 * Menampilkan semua data kib b
	 */
	function total_kib_eksint($bidang='',$unit='',$sub='',$upb='',$like,$kode,$kondisi)
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

		$tgl_pembukuan 	= " AND Tgl_Pembukuan ".$like;
		$tgl_dokumen 	= " AND Tgl_Dokumen ".$like;
		$minSatuan 		= $this->auth->getMinSatuan(5);
		if($kondisi == 1){
			$kondisi = " AND LastHarga < $minSatuan";
		}else{
			$kondisi = " AND LastHarga >= $minSatuan";
		}
		$query = "SELECT COUNT(*) as Jumlah,
				COUNT(CASE WHEN lastKondisi = 1 THEN 1 ELSE null END) as Baik,
				COUNT(CASE WHEN lastKondisi = 2 THEN 1 ELSE null END) as Kurang_baik,
				COUNT(CASE WHEN lastKondisi = 3 THEN 1 ELSE null END) as Rusak,
				SUM(LastHarga) as Harga
				 FROM (
				 	SELECT *,(Harga + (CASE WHEN Koreksi_Tambah is null THEN 0 ELSE Koreksi_Tambah END)) - (CASE WHEN Koreksi_Kurang is null THEN 0 ELSE Koreksi_Kurang END) as LastHarga
				 FROM 
(SELECT a1.*,(CASE WHEN b.NewKondisi is null THEN a1.Kondisi ELSE b.NewKondisi END) as lastKondisi,
	/* ----------- jumlah kapitalisasi ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '2'
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Kapitalisasi,
	/* ----------- jumlah pengurangan PINDAH SKPD------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '3'
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Pindah_SKPD,
	/* ----------- jumlah pengurangan PENGHAPUSAN------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '7'
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Penghapusan,
	/* ----------- jumlah pengurangan Ubah data------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '18'
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Ubah_data,
	/* ----------- jumlah penambahan Koreksi Nilai------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '21'
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,
	/* ----------- jumlah penambahan Koreksi Nilai------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = '23'
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang
FROM Ta_KIB_E a1
OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
             FROM Ta_KIBER b
             WHERE a1.Kd_Bidang=b.Kd_Bidang
				AND a1.Kd_Unit=b.Kd_Unit
				AND a1.Kd_Sub=b.Kd_Sub
				AND a1.Kd_UPB=b.Kd_UPB
				AND a1.Kd_Aset1=b.Kd_Aset1
				AND a1.Kd_Aset2=b.Kd_Aset2
				AND a1.Kd_Aset3=b.Kd_Aset3
				AND a1.Kd_Aset4=b.Kd_Aset4
				AND a1.Kd_Aset5=b.Kd_Aset5
				AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 $tgl_dokumen
             ORDER BY b.Tgl_Dokumen DESC) as b
) as a ) as a
 WHERE 1=1 $where $tgl_pembukuan $kondisi";

		// print_r($query); exit();
		return $this->db->query($query);
		}

	/**
	 * Menampilkan semua data aset pemeliharaan
	 */
	function pemeliharaan($bidang='',$unit='',$sub='',$upb='',$like)
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

	$tgl_dokumen 	= " AND Tgl_Dokumen ".$like;

	$query = "SELECT n.Nm_Aset5,COUNT(a.No_register) as jumlah_register,
			MIN(a.No_register) as min_register,
			MAX(a.No_register) as max_register,
			a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
			a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
			a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik,DATENAME(yyyy,a.Tgl_Perolehan) as Tahun,
			a.Asal_usul,COUNT(*) as Jumlah,
SUM(ar.Harga) as Harga,ar.Tgl_Dokumen,ar.No_Dokumen,ar.Keterangan

FROM Ta_KIBER ar LEFT JOIN Ta_KIB_E a ON ar.Kd_Bidang = a.Kd_Bidang
					AND ar.Kd_Unit = a.Kd_Unit
					AND ar.Kd_Sub = a.Kd_Sub
					AND ar.Kd_UPB = a.Kd_UPB
					AND ar.Kd_Aset1 = a.Kd_Aset1
					AND ar.Kd_Aset2 = a.Kd_Aset2
					AND ar.Kd_Aset3 = a.Kd_Aset3
					AND ar.Kd_Aset4 = a.Kd_Aset4
					AND ar.Kd_Aset5 = a.Kd_Aset5
					AND ar.No_Register = a.No_Register
LEFT JOIN Ref_Rek_Aset5 n ON ar.Kd_Aset1 = n.Kd_Aset1 AND ar.Kd_Aset2 = n.Kd_Aset2 AND ar.Kd_Aset3 = n.Kd_Aset3 AND ar.Kd_Aset4 = n.Kd_Aset4 AND ar.Kd_Aset5 = n.Kd_Aset5
WHERE Kd_Riwayat=2 $where $tgl_dokumen

GROUP BY a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik, a.Kd_Pemilik,a.Tgl_Perolehan,a.Asal_usul,ar.Tgl_Dokumen,ar.No_Dokumen,ar.Keterangan,n.Nm_Aset5";

			/*print_r($query); exit();*/
			return $this->db->query($query);
		}

	/**
	 * Menampilkan semua data kib bi
	 */
	function total_pemeliharaan($bidang='',$unit='',$sub='',$upb='',$like)
	{			
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$where = "";

		if ($this->session->userdata('lvl') == 01){
			if($bidang){
				$where = " AND (a.Kd_Bidang = $bidang)";
			}
			if($unit){
				$where .= " AND (a.Kd_Unit = $unit)";
			}
			if($sub){
				$where .= " AND (a.Kd_Sub = $sub)";
			}
			if($upb){
				$where .= " AND (a.Kd_UPB = $upb)";
			}
		}elseif ($this->session->userdata('lvl') == 02){
			if($bidang){
				$where = " AND (a.Kd_Bidang = $kb)";
			}
			if($unit){
				$where .= " AND (a.Kd_Unit = $ku)";
			}
			if($sub){
				$where .= " AND (a.Kd_Sub = $ks)";
			}
			if($upb){
				$where .= " AND (a.Kd_UPB = $upb)";
			}
		}else{
			if($bidang){
				$where = " AND (a.Kd_Bidang = $kb)";
			}
			if($unit){
				$where .= " AND (a.Kd_Unit = $ku)";
			}
			if($sub){
				$where .= " AND (a.Kd_Sub = $ks)";
			}
			if($upb){
				$where .= " AND (a.Kd_UPB = $kupb)";
			}
		}

	$tgl_dokumen 	= " AND Tgl_Dokumen ".$like;

	$query = "SELECT COUNT(*) as Jumlah, SUM(Harga) as Harga FROM Ta_KIBER a
	WHERE a.Kd_Riwayat=2 $where $tgl_dokumen";	

			/*print_r($query); exit();*/
			return $this->db->query($query);
		}

	function kib_susut($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
	{			
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}
	
	$tgl_pembukuan = " AND Tgl_Pembukuan ".$like;
	$tgl_dokumen   = " AND Tgl_Dokumen <= '".$tahunakhir."'";
	$minSatuan     = $this->auth->getMinSatuan(5);
	$set_intra     = " AND LastHarga >= $minSatuan";
	$kondisi       = "  AND lastKondisi <> 3";

	$query = "SELECT Nm_Aset5,Kd_Prov, Kd_Kab_Kota, Kd_Bidang, Kd_Unit,
			Kd_Sub, Kd_UPB, Kd_Aset1, Kd_Aset2, Kd_Aset3, 
			Kd_Aset4, Kd_Aset5,Masa_Manfaat, DATENAME(yyyy,Tgl_Perolehan) as Tahun,
			COUNT(*) as Jumlah,
			SUM(LastHarga) as Harga,
			SUM(Kapitalisasi) as Kapitalisasi,
			SUM(Susut) as Susut,
			SUM(Akm_Susut) as Akm_Susut,
			Sisa_Masa_Manfaat,
			SUM(NB) as NB,
			Keterangan FROM (
	SELECT *,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,1) as Susut,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,2) as Akm_Susut,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,3) as Sisa_Masa_Manfaat,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,4) as NB
	 FROM (
	SELECT *,
	((Harga + ISNULL(Total_Koreksi, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as HargaKoreksi,
	((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
	 FROM  (
			SELECT n.Nm_Aset5, a1.*,
						(CASE WHEN NewKondisi is null THEN Kondisi ELSE NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 21
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Total_Koreksi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 2
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Kapitalisasi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,

/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1

					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 AND YEAR(b.Tgl_Dokumen) <= '{$thn}'
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM   Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
					                   ON d.No_SK=e.No_SK
					                   WHERE a1.Kd_Bidang=d.Kd_Bidang
										AND a1.Kd_Unit=d.Kd_Unit
										AND a1.Kd_Sub=d.Kd_Sub
										AND a1.Kd_UPB=d.Kd_UPB
										AND a1.Kd_Aset1=d.Kd_Aset1
										AND a1.Kd_Aset2=d.Kd_Aset2
										AND a1.Kd_Aset3=d.Kd_Aset3
										AND a1.Kd_Aset4=d.Kd_Aset4
										AND a1.Kd_Aset5=d.Kd_Aset5
										AND a1.No_Register=d.No_Register AND YEAR(e.Tgl_SK) <= '{$thn}')
							AND NOT EXISTS (SELECT 1 FROM   Ta_KIBER e 
					                   WHERE a1.Kd_Bidang=e.Kd_Bidang
										AND a1.Kd_Unit=e.Kd_Unit
										AND a1.Kd_Sub=e.Kd_Sub
										AND a1.Kd_UPB=e.Kd_UPB
										AND a1.Kd_Aset1=e.Kd_Aset1
										AND a1.Kd_Aset2=e.Kd_Aset2
										AND a1.Kd_Aset3=e.Kd_Aset3
										AND a1.Kd_Aset4=e.Kd_Aset4
										AND a1.Kd_Aset5=e.Kd_Aset5
										AND a1.No_Register=e.No_Register  AND Kd_Riwayat IN (3,19) AND YEAR(e.Tgl_Dokumen) <= '{$thn}')	

)as a2 ) as a WHERE 1=1 AND a.Kd_Aset1=5 AND a.Kd_Aset2=18 $set_intra $kondisi $where $tgl_pembukuan  )
as a3 GROUP BY Nm_Aset5,Kd_Prov, Kd_Kab_Kota, Kd_Bidang, Kd_Unit,
			Kd_Sub, Kd_UPB, Kd_Aset1, Kd_Aset2, Kd_Aset3, 
			Kd_Aset4, Kd_Aset5,Masa_Manfaat, DATENAME(yyyy,Tgl_Perolehan),Sisa_Masa_Manfaat,Keterangan";

			// print_r($query); exit();
			return $this->db->query($query);
		}


	function rekap_susut($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
	{			
	
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan = " AND Tgl_Pembukuan ".$like;
	$tgl_dokumen   = " AND Tgl_Dokumen <= '".$tahunakhir."'";
	$minSatuan     = $this->auth->getMinSatuan(5);
	$set_intra    = " AND LastHarga >= $minSatuan";
	$kondisi	   = "  AND lastKondisi <> 3";
	$thn           = $this->session->userdata('tahun_anggaran');

	$query = "SELECT Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2,
	SUM(LastHarga) as Harga,
	SUM(NB_Awal) as NB_Awal,
	SUM(NB_Akhir) as NB_Akhir,
	SUM(Akm_Susut) as Akm_Susut,
	SUM(Kapitalisasi) as Kapitalisasi
	 FROM Ref_Rek_Aset2 LEFT JOIN (

	 	SELECT *,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,4) as NB_Awal,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,4) as NB_Akhir,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,2) as Akm_Susut
	 		 FROM (
	SELECT *,
	((Harga + ISNULL(Total_Koreksi, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as HargaKoreksi,
	((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
	 FROM  (
			SELECT n.Nm_Aset5, a1.*,
						(CASE WHEN NewKondisi is null THEN Kondisi ELSE NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 21
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Total_Koreksi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 2
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Kapitalisasi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,

/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1

					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 AND YEAR(b.Tgl_Dokumen) <= '{$thn}'
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM   Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
					                   ON d.No_SK=e.No_SK
					                   WHERE a1.Kd_Bidang=d.Kd_Bidang
										AND a1.Kd_Unit=d.Kd_Unit
										AND a1.Kd_Sub=d.Kd_Sub
										AND a1.Kd_UPB=d.Kd_UPB
										AND a1.Kd_Aset1=d.Kd_Aset1
										AND a1.Kd_Aset2=d.Kd_Aset2
										AND a1.Kd_Aset3=d.Kd_Aset3
										AND a1.Kd_Aset4=d.Kd_Aset4
										AND a1.Kd_Aset5=d.Kd_Aset5
										AND a1.No_Register=d.No_Register AND YEAR(e.Tgl_SK) <= '{$thn}')
							AND NOT EXISTS (SELECT 1 FROM   Ta_KIBER e 
					                   WHERE a1.Kd_Bidang=e.Kd_Bidang
										AND a1.Kd_Unit=e.Kd_Unit
										AND a1.Kd_Sub=e.Kd_Sub
										AND a1.Kd_UPB=e.Kd_UPB
										AND a1.Kd_Aset1=e.Kd_Aset1
										AND a1.Kd_Aset2=e.Kd_Aset2
										AND a1.Kd_Aset3=e.Kd_Aset3
										AND a1.Kd_Aset4=e.Kd_Aset4
										AND a1.Kd_Aset5=e.Kd_Aset5
										AND a1.No_Register=e.No_Register  AND Kd_Riwayat IN (3,19) AND YEAR(e.Tgl_Dokumen) <= '{$thn}')	

)as a2 ) as a WHERE 1=1 AND a.Kd_Aset1=5 AND a.Kd_Aset2=18 $set_intra $kondisi $where $tgl_pembukuan

	 	 ) as a3 ON 
	a3.Kd_Aset1=Ref_Rek_Aset2.Kd_Aset1 AND 
	a3.Kd_Aset2=Ref_Rek_Aset2.Kd_Aset2   WHERE Ref_Rek_Aset2.Kd_Aset1=5 AND Ref_Rek_Aset2.Kd_Aset2=18
	GROUP BY Ref_Rek_Aset2.Kd_Aset1,Ref_Rek_Aset2.Kd_Aset2,Nm_Aset2 ORDER BY Kd_Aset2";

			// print_r($query); exit();
			return $this->db->query($query);
		}

	function insert_usul_penghapusan($data){
		$sql = $this->db->insert("Ta_KIBEHapus", $data);
		if ($sql)
				return true;
		
	}

	function kib_usul_hapus($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like,$kondisi)
	{			
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan 	= " AND Tgl_Pembukuan ".$like;
	$tgl_dokumen 	= " AND Tgl_Dokumen <= '".$tahunakhir."'";

	$query = "SELECT a.Nm_Aset5,COUNT(a.No_register) as jumlah_register,
	MIN(a.No_register) as min_register, MAX(a.No_register) as max_register,
	a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
	a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
	a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik,DATENAME(yyyy,a.Tgl_Perolehan) as Tahun,
	a.Asal_usul,a.Judul,a.Pencipta,a.LastKondisi,COUNT(*) as Jumlah,
	SUM(LastHarga) as  Harga,
			a.Keterangan FROM
			(
				SELECT *,((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga FROM (
			SELECT n.Nm_Aset5, a1.*,h.Tgl_UP, h.No_UP, (CASE WHEN NewKondisi is null THEN a1.Kondisi ELSE NewKondisi END) as lastKondisi,
			/* ----------- jumlah Penambahan ------------*/
								(SELECT SUM(Harga) as Rharga
								FROM Ta_KIBER a2
								WHERE a2.Kd_Riwayat IN (2, 21)
								AND a1.Kd_Bidang = a2.Kd_Bidang
								AND a1.Kd_Unit = a2.Kd_Unit
								AND a1.Kd_Sub = a2.Kd_Sub
								AND a1.Kd_UPB = a2.Kd_UPB
								AND a1.Kd_Aset1 = a2.Kd_Aset1
								AND a1.Kd_Aset2 = a2.Kd_Aset2
								AND a1.Kd_Aset3 = a2.Kd_Aset3
								AND a1.Kd_Aset4 = a2.Kd_Aset4
								AND a1.Kd_Aset5 = a2.Kd_Aset5
								AND a1.No_Register = a2.No_Register $tgl_dokumen
								) AS Koreksi_Tambah,

			/* ----------- jumlah Berkurang ------------*/
								(SELECT SUM(Harga) as Rharga
								FROM Ta_KIBER a2
								WHERE a2.Kd_Riwayat IN (7, 23)
								AND a1.Kd_Bidang = a2.Kd_Bidang
								AND a1.Kd_Unit = a2.Kd_Unit
								AND a1.Kd_Sub = a2.Kd_Sub
								AND a1.Kd_UPB = a2.Kd_UPB
								AND a1.Kd_Aset1 = a2.Kd_Aset1
								AND a1.Kd_Aset2 = a2.Kd_Aset2
								AND a1.Kd_Aset3 = a2.Kd_Aset3
								AND a1.Kd_Aset4 = a2.Kd_Aset4
								AND a1.Kd_Aset5 = a2.Kd_Aset5
								AND a1.No_Register = a2.No_Register $tgl_dokumen
								) AS Koreksi_Kurang

			FROM Ta_KIBEHapus h LEFT JOIN Ta_KIB_E a1
			ON h.Kd_Bidang = a1.Kd_Bidang
									AND h.Kd_Unit = a1.Kd_Unit
									AND h.Kd_Sub = a1.Kd_Sub
									AND h.Kd_UPB = a1.Kd_UPB
									AND h.Kd_Aset1 = a1.Kd_Aset1
									AND h.Kd_Aset2 = a1.Kd_Aset2
									AND h.Kd_Aset3 = a1.Kd_Aset3
									AND h.Kd_Aset4 = a1.Kd_Aset4
									AND h.Kd_Aset5 = a1.Kd_Aset5
									AND h.No_Register = a1.No_Register

			INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5
																
				OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
										 FROM Ta_KIBER b
										 WHERE a1.Kd_Bidang=b.Kd_Bidang
										AND a1.Kd_Unit=b.Kd_Unit
										AND a1.Kd_Sub=b.Kd_Sub
										AND a1.Kd_UPB=b.Kd_UPB
										AND a1.Kd_Aset1=b.Kd_Aset1
										AND a1.Kd_Aset2=b.Kd_Aset2
										AND a1.Kd_Aset3=b.Kd_Aset3
										AND a1.Kd_Aset4=b.Kd_Aset4
										AND a1.Kd_Aset5=b.Kd_Aset5
										AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 AND YEAR(b.Tgl_Dokumen) <= '{$thn}'
										 ORDER BY b.Tgl_Dokumen DESC) as b
					) as x

				) as a 
WHERE 1=1 $where $tgl_pembukuan $kondisi

GROUP BY a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik, a.Kd_Pemilik,a.Tgl_Perolehan,
a.Asal_usul,a.Judul,a.Pencipta,a.lastKondisi,a.Keterangan,a.Nm_Aset5,a.LastKondisi
ORDER BY  a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3,a.Kd_Aset4, a.Kd_Aset5";

			// print_r($query); exit();
			return $this->db->query($query);
		}

	function kib_sk_hapus($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like,$sk)
	{			
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan 	= " AND Tgl_SK ".$like;
	$tgl_dokumen 	= " AND Tgl_Dokumen <= '".$tahunakhir."'";

	$query = "SELECT a.Nm_Aset5,COUNT(a.No_register) as jumlah_register,
		MIN(a.No_register) as min_register, MAX(a.No_register) as max_register,
		a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
		a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
		a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik,DATENAME(yyyy,a.Tgl_Perolehan) as Tahun,
		a.Asal_usul,a.Judul,a.Pencipta,a.LastKondisi,COUNT(*) as Jumlah,
		SUM(LastHarga) as  Harga,
			a.Keterangan FROM
			(
				SELECT *,((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga FROM (
			SELECT n.Nm_Aset5, a1.*, a3.No_SK, a3.Tgl_SK, (CASE WHEN NewKondisi is null THEN a1.Kondisi ELSE NewKondisi END) as lastKondisi,
			/* ----------- jumlah Penambahan ------------*/
								(SELECT SUM(Harga) as Rharga
								FROM Ta_KIBER a2
								WHERE a2.Kd_Riwayat IN (2, 21)
								AND a1.Kd_Bidang = a2.Kd_Bidang
								AND a1.Kd_Unit = a2.Kd_Unit
								AND a1.Kd_Sub = a2.Kd_Sub
								AND a1.Kd_UPB = a2.Kd_UPB
								AND a1.Kd_Aset1 = a2.Kd_Aset1
								AND a1.Kd_Aset2 = a2.Kd_Aset2
								AND a1.Kd_Aset3 = a2.Kd_Aset3
								AND a1.Kd_Aset4 = a2.Kd_Aset4
								AND a1.Kd_Aset5 = a2.Kd_Aset5
								AND a1.No_Register = a2.No_Register $tgl_dokumen
								) AS Koreksi_Tambah,
			/* ----------- jumlah Berkurang ------------*/
								(SELECT SUM(Harga) as Rharga
								FROM Ta_KIBER a2
								WHERE a2.Kd_Riwayat IN (7, 23)
								AND a1.Kd_Bidang = a2.Kd_Bidang
								AND a1.Kd_Unit = a2.Kd_Unit
								AND a1.Kd_Sub = a2.Kd_Sub
								AND a1.Kd_UPB = a2.Kd_UPB
								AND a1.Kd_Aset1 = a2.Kd_Aset1
								AND a1.Kd_Aset2 = a2.Kd_Aset2
								AND a1.Kd_Aset3 = a2.Kd_Aset3
								AND a1.Kd_Aset4 = a2.Kd_Aset4
								AND a1.Kd_Aset5 = a2.Kd_Aset5
								AND a1.No_Register = a2.No_Register $tgl_dokumen
								) AS Koreksi_Kurang

			FROM Ta_KIBEHapus h LEFT JOIN Ta_KIB_E a1
			ON h.Kd_Bidang = a1.Kd_Bidang
									AND h.Kd_Unit = a1.Kd_Unit
									AND h.Kd_Sub = a1.Kd_Sub
									AND h.Kd_UPB = a1.Kd_UPB
									AND h.Kd_Aset1 = a1.Kd_Aset1
									AND h.Kd_Aset2 = a1.Kd_Aset2
									AND h.Kd_Aset3 = a1.Kd_Aset3
									AND h.Kd_Aset4 = a1.Kd_Aset4
									AND h.Kd_Aset5 = a1.Kd_Aset5
									AND h.No_Register = a1.No_Register
			RIGHT JOIN Ta_Penghapusan a3 ON h.No_SK = a3.No_SK

			INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5
																
				OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
										 FROM Ta_KIBER b
										 WHERE a1.Kd_Bidang=b.Kd_Bidang
										AND a1.Kd_Unit=b.Kd_Unit
										AND a1.Kd_Sub=b.Kd_Sub
										AND a1.Kd_UPB=b.Kd_UPB
										AND a1.Kd_Aset1=b.Kd_Aset1
										AND a1.Kd_Aset2=b.Kd_Aset2
										AND a1.Kd_Aset3=b.Kd_Aset3
										AND a1.Kd_Aset4=b.Kd_Aset4
										AND a1.Kd_Aset5=b.Kd_Aset5
										AND a1.No_Register=b.No_Register
										AND Kd_Riwayat=1 AND b.Tgl_Dokumen <= '{$tahunakhir}'
										 ORDER BY b.Tgl_Dokumen DESC) as b
					) as x

				) as a 
WHERE 1=1 $where $tgl_pembukuan $sk

GROUP BY a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik, a.Kd_Pemilik,a.Tgl_Perolehan,
a.Asal_usul,a.Judul,a.Pencipta,a.lastKondisi,a.Keterangan,a.Nm_Aset5,a.LastKondisi
ORDER BY  a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3,a.Kd_Aset4, a.Kd_Aset5";

			// print_r($query); exit();
			return $this->db->query($query);
		}

	function rincian_barang($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
	{			
	
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan = " AND Tgl_Pembukuan ".$like;
	$tgl_dokumen   = " AND Tgl_Dokumen <= '".$tahunakhir."'";
	$minSatuan     = $this->auth->getMinSatuan(5);
	$set_intra     = " LastHarga >= '$minSatuan'";
	$kondisi       = "  AND lastKondisi <> 3";
	$thn           = $this->session->userdata('tahun_anggaran');

	$query = "SELECT d5.Kd_Bidang,d5.Kd_Unit,d5.Kd_Sub,d5.Kd_UPB,
		d5.Kd_Aset1,d5.Kd_Aset2,d5.Kd_Aset3,d5.Kd_Aset4,d5.Kd_Aset5,
		d2.Nm_Aset2,d3.Nm_Aset3,d4.Nm_Aset4,d5.Nm_Aset5,
		COUNT(CASE WHEN d5.LastKondisi = 1 THEN d5.LastHarga END) as B,
		COUNT(CASE WHEN d5.LastKondisi = 2 THEN d5.LastHarga END) as KB,
		COUNT(CASE WHEN d5.LastKondisi = 3 THEN d5.LastHarga END) as RB,
		COUNT(*) as Jumlah,
		SUM(d5.LastHarga) as Harga
		FROM  (
				SELECT *,
			((Harga + ISNULL(Total_Koreksi, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as HargaKoreksi,
			((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
			 FROM  (
					SELECT n.Nm_Aset5, a1.*,
						(CASE WHEN NewKondisi is null THEN Kondisi ELSE NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 21
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Total_Koreksi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 2
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Kapitalisasi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,

/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1

					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 AND YEAR(b.Tgl_Dokumen) <= '{$thn}'
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM   Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
					                   ON d.No_SK=e.No_SK
					                   WHERE a1.Kd_Bidang=d.Kd_Bidang
										AND a1.Kd_Unit=d.Kd_Unit
										AND a1.Kd_Sub=d.Kd_Sub
										AND a1.Kd_UPB=d.Kd_UPB
										AND a1.Kd_Aset1=d.Kd_Aset1
										AND a1.Kd_Aset2=d.Kd_Aset2
										AND a1.Kd_Aset3=d.Kd_Aset3
										AND a1.Kd_Aset4=d.Kd_Aset4
										AND a1.Kd_Aset5=d.Kd_Aset5
										AND a1.No_Register=d.No_Register AND YEAR(e.Tgl_SK) <= '{$thn}')
							AND NOT EXISTS (SELECT 1 FROM   Ta_KIBER e 
					                   WHERE a1.Kd_Bidang=e.Kd_Bidang
										AND a1.Kd_Unit=e.Kd_Unit
										AND a1.Kd_Sub=e.Kd_Sub
										AND a1.Kd_UPB=e.Kd_UPB
										AND a1.Kd_Aset1=e.Kd_Aset1
										AND a1.Kd_Aset2=e.Kd_Aset2
										AND a1.Kd_Aset3=e.Kd_Aset3
										AND a1.Kd_Aset4=e.Kd_Aset4
										AND a1.Kd_Aset5=e.Kd_Aset5
										AND a1.No_Register=e.No_Register  AND Kd_Riwayat IN (3,19) AND YEAR(e.Tgl_Dokumen) <= '{$thn}')	

) as a WHERE 1=1 $where $tgl_pembukuan )
as d5 LEFT JOIN Ref_Rek_Aset4 d4 ON d5.Kd_Aset1=d4.Kd_Aset1 AND d5.Kd_Aset2=d4.Kd_Aset2 AND d5.Kd_Aset3=d4.Kd_Aset3 AND d5.Kd_Aset4=d4.Kd_Aset4
LEFT JOIN Ref_Rek_Aset3 d3 ON d5.Kd_Aset1=d3.Kd_Aset1 AND d5.Kd_Aset2=d3.Kd_Aset2 AND d5.Kd_Aset3=d3.Kd_Aset3
LEFT JOIN Ref_Rek_Aset2 d2 ON d5.Kd_Aset1=d2.Kd_Aset1 AND d5.Kd_Aset2=d2.Kd_Aset2
GROUP BY d5.Kd_Bidang,d5.Kd_Unit,d5.Kd_Sub,d5.Kd_UPB,
d5.Kd_Aset1,d5.Kd_Aset2,d5.Kd_Aset3,d5.Kd_Aset4,d5.Kd_Aset5,
d2.Nm_Aset2,d3.Nm_Aset3,d4.Nm_Aset4,d5.Nm_Aset5";

			// print_r($query); exit();
			return $this->db->query($query);
		}
	
	function non_operasional($bidang='',$unit='',$sub='',$upb='', $tahunawal, $tahunakhir,$like)
	{			
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan 	= " AND Tgl_Pembukuan ".$like;
	$tgl_dokumen 	= " AND Tgl_Dokumen <= '".$tahunakhir."'";

	$query = "SELECT a.Nm_Aset5,COUNT(a.No_register) as jumlah_register,
	MIN(a.No_register) as min_register, MAX(a.No_register) as max_register,
	a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
	a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
	a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik,DATENAME(yyyy,a.Tgl_Perolehan) as Tahun,
	a.Asal_usul,a.Judul,a.Pencipta,a.LastKondisi,COUNT(*) as Jumlah,
	SUM(LastHarga) as  Harga,
			a.Keterangan FROM
			(
				SELECT *,((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
					FROM (	
						SELECT n.Nm_Aset5, a1.*,
						(CASE WHEN NewKondisi is null THEN Kondisi ELSE NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,

/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1

					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 AND YEAR(b.Tgl_Dokumen) <= '{$thn}'
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM   Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
					                   ON d.No_SK=e.No_SK
					                   WHERE a1.Kd_Bidang=d.Kd_Bidang
										AND a1.Kd_Unit=d.Kd_Unit
										AND a1.Kd_Sub=d.Kd_Sub
										AND a1.Kd_UPB=d.Kd_UPB
										AND a1.Kd_Aset1=d.Kd_Aset1
										AND a1.Kd_Aset2=d.Kd_Aset2
										AND a1.Kd_Aset3=d.Kd_Aset3
										AND a1.Kd_Aset4=d.Kd_Aset4
										AND a1.Kd_Aset5=d.Kd_Aset5
										AND a1.No_Register=d.No_Register AND YEAR(e.Tgl_SK) <= '{$thn}')
							AND NOT EXISTS (SELECT 1 FROM   Ta_KIBER e 
					                   WHERE a1.Kd_Bidang=e.Kd_Bidang
										AND a1.Kd_Unit=e.Kd_Unit
										AND a1.Kd_Sub=e.Kd_Sub
										AND a1.Kd_UPB=e.Kd_UPB
										AND a1.Kd_Aset1=e.Kd_Aset1
										AND a1.Kd_Aset2=e.Kd_Aset2
										AND a1.Kd_Aset3=e.Kd_Aset3
										AND a1.Kd_Aset4=e.Kd_Aset4
										AND a1.Kd_Aset5=e.Kd_Aset5
										AND a1.No_Register=e.No_Register  AND Kd_Riwayat IN (3,19) AND YEAR(e.Tgl_Dokumen) <= '{$thn}')			
						) as x

			) as a 
WHERE 1=1 AND Kd_KA = 0 $where $tgl_pembukuan

GROUP BY a.Kd_Prov, a.Kd_Kab_Kota, a.Kd_Bidang, a.Kd_Unit,
a.Kd_Sub, a.Kd_UPB, a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
a.Kd_Aset4, a.Kd_Aset5, a.Kd_Pemilik, a.Kd_Pemilik,a.Tgl_Perolehan,
a.Asal_usul,a.Judul,a.Pencipta,a.lastKondisi,a.Keterangan,a.Nm_Aset5,a.LastKondisi
ORDER BY  a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3,a.Kd_Aset4, a.Kd_Aset5";

			// print_r($query); exit();
			return $this->db->query($query);
		}

	function akm_hapus($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
	{			
	
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan = " AND Tgl_SK ".$like;
	$tgl_dokumen   = " AND Tgl_Dokumen <= '".$tahunakhir."'";
	$minSatuan     = $this->auth->getMinSatuan(5);
	$set_intra     = " LastHarga >= '$minSatuan'";
	$kondisi       = " AND lastKondisi <> 3";
	$thn           = $this->session->userdata('tahun_anggaran')-1;

	$query = "SELECT n.Kd_Aset1,n.Kd_Aset2,n.Nm_Aset2,
	COUNT(CASE WHEN $set_intra AND LastKondisi <> 3 AND Kd_KA=1 THEN 1 END) as Jumlah,
	SUM(CASE WHEN $set_intra AND LastKondisi <> 3 AND Kd_KA=1 THEN Susut END) as Beban_Susut,
	SUM(CASE WHEN $set_intra AND LastKondisi <> 3 AND Kd_KA=1 THEN Akm_Susut END) as Akm_Susut,
	SUM(CASE WHEN $set_intra AND LastKondisi <> 3 AND Kd_KA=1 THEN NB_Akhir END) as Nilai_Buku,
	SUM(CASE WHEN $set_intra AND LastKondisi <> 3 AND Kd_KA=1 THEN LastHarga END) as Nilai_Perolehan
	FROM Ref_Rek_Aset2 n LEFT JOIN (
	
	SELECT *,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,1) as Susut,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,4) as NB_Akhir,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,2) as Akm_Susut
	 	FROM (
	 		 SELECT *,
	 		 ((Harga + ISNULL(Total_Koreksi, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as HargaKoreksi,
			 ((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
	 		  FROM (
			SELECT n.Nm_Aset5, a1.*, a3.No_SK, a3.Tgl_SK, (CASE WHEN NewKondisi is null THEN a1.Kondisi ELSE NewKondisi END) as lastKondisi,
			/* ----------- jumlah Penambahan ------------*/
								(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 21
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Total_Koreksi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 2
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Kapitalisasi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,
			/* ----------- jumlah Berkurang ------------*/
								(SELECT SUM(Harga) as Rharga
								FROM Ta_KIBER a2
								WHERE a2.Kd_Riwayat IN (7, 23)
								AND a1.Kd_Bidang = a2.Kd_Bidang
								AND a1.Kd_Unit = a2.Kd_Unit
								AND a1.Kd_Sub = a2.Kd_Sub
								AND a1.Kd_UPB = a2.Kd_UPB
								AND a1.Kd_Aset1 = a2.Kd_Aset1
								AND a1.Kd_Aset2 = a2.Kd_Aset2
								AND a1.Kd_Aset3 = a2.Kd_Aset3
								AND a1.Kd_Aset4 = a2.Kd_Aset4
								AND a1.Kd_Aset5 = a2.Kd_Aset5
								AND a1.No_Register = a2.No_Register $tgl_dokumen
								) AS Koreksi_Kurang

			FROM Ta_KIBEHapus h LEFT JOIN Ta_KIB_E a1
			ON h.Kd_Bidang = a1.Kd_Bidang
									AND h.Kd_Unit = a1.Kd_Unit
									AND h.Kd_Sub = a1.Kd_Sub
									AND h.Kd_UPB = a1.Kd_UPB
									AND h.Kd_Aset1 = a1.Kd_Aset1
									AND h.Kd_Aset2 = a1.Kd_Aset2
									AND h.Kd_Aset3 = a1.Kd_Aset3
									AND h.Kd_Aset4 = a1.Kd_Aset4
									AND h.Kd_Aset5 = a1.Kd_Aset5
									AND h.No_Register = a1.No_Register
			RIGHT JOIN Ta_Penghapusan a3 ON h.No_SK = a3.No_SK

			INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5
																
				OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
										 FROM Ta_KIBER b
										 WHERE a1.Kd_Bidang=b.Kd_Bidang
										AND a1.Kd_Unit=b.Kd_Unit
										AND a1.Kd_Sub=b.Kd_Sub
										AND a1.Kd_UPB=b.Kd_UPB
										AND a1.Kd_Aset1=b.Kd_Aset1
										AND a1.Kd_Aset2=b.Kd_Aset2
										AND a1.Kd_Aset3=b.Kd_Aset3
										AND a1.Kd_Aset4=b.Kd_Aset4
										AND a1.Kd_Aset5=b.Kd_Aset5
										AND a1.No_Register=b.No_Register
										AND Kd_Riwayat=1 AND b.Tgl_Dokumen <= '{$tahunakhir}'
										 ORDER BY b.Tgl_Dokumen DESC) as b
					) as x



	 ) as a WHERE 1=1 $where $tgl_pembukuan

		) as a3 ON a3.Kd_Aset1=n.Kd_Aset1 AND a3.Kd_Aset2=n.Kd_Aset2
		WHERE n.Kd_Aset1=5
		GROUP BY n.Kd_Aset1,n.Kd_Aset2,n.Nm_Aset2 ORDER BY Kd_Aset2";

			// print_r($query); exit();
			return $this->db->query($query);
		}

	function akm_pindah($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
	{			
	
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan = " AND Tgl_SK ".$like;
	$tgl_dokumen   = " AND Tgl_Dokumen <= '".$tahunakhir."'";
	$minSatuan     = $this->auth->getMinSatuan(5);
	$set_intra     = " LastHarga >= '$minSatuan'";
	$kondisi       = " AND lastKondisi <> 3";
	$thn           = $this->session->userdata('tahun_anggaran')-1;

	$query = "SELECT n.Kd_Aset1,n.Kd_Aset2,n.Nm_Aset2,
	COUNT(CASE WHEN $set_intra AND LastKondisi <> 3 AND Kd_KA=1 THEN 1 END) as Jumlah,
	SUM(CASE WHEN $set_intra AND LastKondisi <> 3 AND Kd_KA=1 THEN Susut END) as Beban_Susut,
	SUM(CASE WHEN $set_intra AND LastKondisi <> 3 AND Kd_KA=1 THEN Akm_Susut END) as Akm_Susut,
	SUM(CASE WHEN $set_intra AND LastKondisi <> 3 AND Kd_KA=1 THEN NB_Akhir END) as Nilai_Buku,
	SUM(CASE WHEN $set_intra AND LastKondisi <> 3 AND Kd_KA=1 THEN LastHarga END) as Nilai_Perolehan
	FROM Ref_Rek_Aset2 n LEFT JOIN (
	
	SELECT *,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,1) as Susut,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,4) as NB_Akhir,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,2) as Akm_Susut
	 	FROM (
	 		 SELECT *,
	 		 ((Harga + ISNULL(Total_Koreksi, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as HargaKoreksi,
			 ((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
	 		  FROM (
			SELECT n.Nm_Aset5, a1.*, a3.No_SK, a3.Tgl_SK, (CASE WHEN NewKondisi is null THEN a1.Kondisi ELSE NewKondisi END) as lastKondisi,
			/* ----------- jumlah Penambahan ------------*/
								(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 21
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Total_Koreksi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 2
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Kapitalisasi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,
			/* ----------- jumlah Berkurang ------------*/
								(SELECT SUM(Harga) as Rharga
								FROM Ta_KIBER a2
								WHERE a2.Kd_Riwayat IN (7, 23)
								AND a1.Kd_Bidang = a2.Kd_Bidang
								AND a1.Kd_Unit = a2.Kd_Unit
								AND a1.Kd_Sub = a2.Kd_Sub
								AND a1.Kd_UPB = a2.Kd_UPB
								AND a1.Kd_Aset1 = a2.Kd_Aset1
								AND a1.Kd_Aset2 = a2.Kd_Aset2
								AND a1.Kd_Aset3 = a2.Kd_Aset3
								AND a1.Kd_Aset4 = a2.Kd_Aset4
								AND a1.Kd_Aset5 = a2.Kd_Aset5
								AND a1.No_Register = a2.No_Register $tgl_dokumen
								) AS Koreksi_Kurang

			FROM Ta_KIBEHapus h LEFT JOIN Ta_KIB_E a1
			ON h.Kd_Bidang = a1.Kd_Bidang
									AND h.Kd_Unit = a1.Kd_Unit
									AND h.Kd_Sub = a1.Kd_Sub
									AND h.Kd_UPB = a1.Kd_UPB
									AND h.Kd_Aset1 = a1.Kd_Aset1
									AND h.Kd_Aset2 = a1.Kd_Aset2
									AND h.Kd_Aset3 = a1.Kd_Aset3
									AND h.Kd_Aset4 = a1.Kd_Aset4
									AND h.Kd_Aset5 = a1.Kd_Aset5
									AND h.No_Register = a1.No_Register
			RIGHT JOIN Ta_Penghapusan a3 ON h.No_SK = a3.No_SK

			INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5
																
				OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
										 FROM Ta_KIBER b
										 WHERE a1.Kd_Bidang=b.Kd_Bidang
										AND a1.Kd_Unit=b.Kd_Unit
										AND a1.Kd_Sub=b.Kd_Sub
										AND a1.Kd_UPB=b.Kd_UPB
										AND a1.Kd_Aset1=b.Kd_Aset1
										AND a1.Kd_Aset2=b.Kd_Aset2
										AND a1.Kd_Aset3=b.Kd_Aset3
										AND a1.Kd_Aset4=b.Kd_Aset4
										AND a1.Kd_Aset5=b.Kd_Aset5
										AND a1.No_Register=b.No_Register
										AND Kd_Riwayat=1 AND b.Tgl_Dokumen <= '{$tahunakhir}'
										 ORDER BY b.Tgl_Dokumen DESC) as b
					) as x



	 ) as a WHERE 1=1 $where $tgl_pembukuan

		) as a3 ON a3.Kd_Aset1=n.Kd_Aset1 AND a3.Kd_Aset2=n.Kd_Aset2
		WHERE n.Kd_Aset1=5
		GROUP BY n.Kd_Aset1,n.Kd_Aset2,n.Nm_Aset2 ORDER BY Kd_Aset2";

			// print_r($query); exit();
			return $this->db->query($query);
		}

	function rincian_susut($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
	{			
	
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		$thn   =  $this->session->userdata('tahun_anggaran');
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

			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";

			if($upb){
				$where .= "AND (a.Kd_UPB = $upb)";
			}
		}else{
			$where = "AND (a.Kd_Bidang = $kb)";
			$where .= "AND (a.Kd_Unit = $ku)";
			$where .= "AND (a.Kd_Sub = $ks)";
			$where .= "AND (a.Kd_UPB = $kupb)";
		}

	$tgl_pembukuan = " AND Tgl_Pembukuan ".$like;
	$tgl_dokumen   = " AND Tgl_Dokumen <= '".$tahunakhir."'";
	$minSatuan     = $this->auth->getMinSatuan(5);
	$set_intra     = " AND LastHarga >= '$minSatuan'";
	$kondisi       = " AND lastKondisi <> 3";
	$set_non_op    = " AND Kd_KA <> 0";
	$thn           = $this->session->userdata('tahun_anggaran');

	$query = "SELECT d5.Kd_Bidang,d5.Kd_Unit,d5.Kd_Sub,d5.Kd_UPB,
		d5.Kd_Aset1,d5.Kd_Aset2,d5.Kd_Aset3,d5.Kd_Aset4,d5.Kd_Aset5,
		d2.Nm_Aset2,d3.Nm_Aset3,d4.Nm_Aset4,d5.Nm_Aset5,
		SUM(LastHarga) as NB_Awal,
		SUM(Susut) as Susut,
		SUM(Akm_Susut) as Akm_Susut,
		SUM(NB) as NB_Akhir
FROM (
	SELECT *,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,1) as Susut,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,2) as Akm_Susut,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,3) as Sisa_Masa_Manfaat,
	dbo.fn_KIB_E_Susut(Kd_Prov,Kd_Kab_Kota,Kd_Bidang,Kd_Unit,Kd_Sub,Kd_UPB,Kd_Aset1,Kd_Aset2,Kd_Aset3,Kd_Aset4,Kd_Aset5,No_Register,Masa_Manfaat,HargaKoreksi,YEAR(Tgl_Perolehan),$thn,$this->neraca_awal,4) as NB
	 FROM (
	SELECT *,
			((Harga + ISNULL(Total_Koreksi, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as HargaKoreksi,
			((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
			 FROM  (
					SELECT n.Nm_Aset5, a1.*,
						(CASE WHEN NewKondisi is null THEN Kondisi ELSE NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 21
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Total_Koreksi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat = 2
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Kapitalisasi,

					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (2, 21)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Tambah,

/* ----------- jumlah Berkurang ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBER a2
					WHERE a2.Kd_Riwayat IN (7, 23)
					AND a1.Kd_Bidang = a2.Kd_Bidang
					AND a1.Kd_Unit = a2.Kd_Unit
					AND a1.Kd_Sub = a2.Kd_Sub
					AND a1.Kd_UPB = a2.Kd_UPB
					AND a1.Kd_Aset1 = a2.Kd_Aset1
					AND a1.Kd_Aset2 = a2.Kd_Aset2
					AND a1.Kd_Aset3 = a2.Kd_Aset3
					AND a1.Kd_Aset4 = a2.Kd_Aset4
					AND a1.Kd_Aset5 = a2.Kd_Aset5
					AND a1.No_Register = a2.No_Register $tgl_dokumen
					) AS Koreksi_Kurang

					FROM Ta_KIB_E a1

					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBER b
					             WHERE a1.Kd_Bidang=b.Kd_Bidang
											AND a1.Kd_Unit=b.Kd_Unit
											AND a1.Kd_Sub=b.Kd_Sub
											AND a1.Kd_UPB=b.Kd_UPB
											AND a1.Kd_Aset1=b.Kd_Aset1
											AND a1.Kd_Aset2=b.Kd_Aset2
											AND a1.Kd_Aset3=b.Kd_Aset3
											AND a1.Kd_Aset4=b.Kd_Aset4
											AND a1.Kd_Aset5=b.Kd_Aset5
											AND a1.No_Register=b.No_Register AND Kd_Riwayat=1 AND YEAR(b.Tgl_Dokumen) <= '{$thn}'
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM   Ta_KIBEHapus d INNER JOIN Ta_Penghapusan e
					                   ON d.No_SK=e.No_SK
					                   WHERE a1.Kd_Bidang=d.Kd_Bidang
										AND a1.Kd_Unit=d.Kd_Unit
										AND a1.Kd_Sub=d.Kd_Sub
										AND a1.Kd_UPB=d.Kd_UPB
										AND a1.Kd_Aset1=d.Kd_Aset1
										AND a1.Kd_Aset2=d.Kd_Aset2
										AND a1.Kd_Aset3=d.Kd_Aset3
										AND a1.Kd_Aset4=d.Kd_Aset4
										AND a1.Kd_Aset5=d.Kd_Aset5
										AND a1.No_Register=d.No_Register AND YEAR(e.Tgl_SK) <= '{$thn}')
							AND NOT EXISTS (SELECT 1 FROM   Ta_KIBER e 
					                   WHERE a1.Kd_Bidang=e.Kd_Bidang
										AND a1.Kd_Unit=e.Kd_Unit
										AND a1.Kd_Sub=e.Kd_Sub
										AND a1.Kd_UPB=e.Kd_UPB
										AND a1.Kd_Aset1=e.Kd_Aset1
										AND a1.Kd_Aset2=e.Kd_Aset2
										AND a1.Kd_Aset3=e.Kd_Aset3
										AND a1.Kd_Aset4=e.Kd_Aset4
										AND a1.Kd_Aset5=e.Kd_Aset5
										AND a1.No_Register=e.No_Register  AND Kd_Riwayat IN (3,19) AND YEAR(e.Tgl_Dokumen) <= '{$thn}')	

)as a2 ) as a WHERE 1=1 AND a.Kd_Aset1=5 AND a.Kd_Aset2=18 $where $tgl_pembukuan $kondisi $set_non_op $set_intra)
as d5 LEFT JOIN Ref_Rek_Aset4 d4 ON d5.Kd_Aset1=d4.Kd_Aset1 AND d5.Kd_Aset2=d4.Kd_Aset2 AND d5.Kd_Aset3=d4.Kd_Aset3 AND d5.Kd_Aset4=d4.Kd_Aset4
LEFT JOIN Ref_Rek_Aset3 d3 ON d5.Kd_Aset1=d3.Kd_Aset1 AND d5.Kd_Aset2=d3.Kd_Aset2 AND d5.Kd_Aset3=d3.Kd_Aset3
LEFT JOIN Ref_Rek_Aset2 d2 ON d5.Kd_Aset1=d2.Kd_Aset1 AND d5.Kd_Aset2=d2.Kd_Aset2
GROUP BY d5.Kd_Bidang,d5.Kd_Unit,d5.Kd_Sub,d5.Kd_UPB,
d5.Kd_Aset1,d5.Kd_Aset2,d5.Kd_Aset3,d5.Kd_Aset4,d5.Kd_Aset5,
d2.Nm_Aset2,d3.Nm_Aset3,d4.Nm_Aset4,d5.Nm_Aset5";

			// print_r($query); exit();
			return $this->db->query($query);
		}

}

/* End of file Contoh_model.php */
/* Location: ./system/application/models/Contoh_model.php */