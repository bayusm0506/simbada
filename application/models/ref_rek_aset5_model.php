<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_rek_aset5_model extends CI_Model{
	
	var $table = 'Ref_Rek_Aset5';
	
	/**
	 * Konvert nama Aset
	 */
	function nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5)
	{	
		$this->db->select('Nm_Aset5');
		$this->db->where('Kd_Aset1',$kd_aset1);
		$this->db->where('Kd_Aset2',$kd_aset2);
		$this->db->where('Kd_Aset3',$kd_aset3);
		$this->db->where('Kd_Aset4',$kd_aset4);
		$this->db->where('Kd_Aset5',$kd_aset5);
        $this->db->from($this->table);
		$sql = $this->db->get();
		foreach ($sql->result() as $row)
		{
			$result = $row->Nm_Aset5;
		}
		return $result;
	}
	
	function json_all($keyword){
		$this->db->LIMIT(10);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	function count_all($like) {
		$like != '' ? $this->db->like($like) : '';
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_all($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }
		

		if ($like != ''){
		return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM    (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}
	
	function json_kiba($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1',1);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	function count_kiba($like) {
		$like != '' ? $this->db->like($like) : '';
		$this->db->where('Kd_Aset1',1);
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_kiba($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }
		

		if ($like != ''){
		return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '1' AND $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '1') AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}
	
	function json_kibb($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1',2);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
		
	function count_kibb($like) {
		$like != '' ? $this->db->like($like) : '';
		$this->db->where('Kd_Aset1',2);
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_kibb($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }
		
		if ($like != ''){
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '2' AND Kd_Aset2 <> '3' AND $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE  Kd_Aset1 = '2' AND Kd_Aset2 <> '3') AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}
	
	function json_kendaraan($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1',2);
		$this->db->where('Kd_Aset2',3);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	function count_kendaraan($like) {
		$like != '' ? $this->db->like($like) : '';
		$this->db->where('Kd_Aset1',2);
		$this->db->where('Kd_Aset2',3);
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_kendaraan($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }
		
		if ($like != ''){
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '2' AND Kd_Aset2 = '3' AND $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE  Kd_Aset1 = '2' AND Kd_Aset2 = '3') AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}
	
	function json_kibc($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1',3);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	
	function count_kibc($like) {
		$like != '' ? $this->db->like($like) : '';
		$this->db->where('Kd_Aset1',3);
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_kibc($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }

		if ($like != ''){
		return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '3' AND $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '3') AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}
	
	function json_kibd($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1',4);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	
	function count_kibd($like) {
		$like != '' ? $this->db->like($like) : '';
		$this->db->where('Kd_Aset1',4);
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_kibd($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }
	
		
		if ($like != ''){
		return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '4' AND $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE  Kd_Aset1 = '4') AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}
	
	function json_kibe($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1',5);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	
	function count_kibe($like) {
		$like != '' ? $this->db->like($like) : '';
		$this->db->where('Kd_Aset1',5);
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_kibe($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }
	
		
		if ($like != ''){
		return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '5' AND $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE  Kd_Aset1 = '5') AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}
	
	
	function json_kibf($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1 != 2 AND Kd_Aset1 != 5');
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	
	function count_kibf($like) {
		$like != '' ? $this->db->like($like) : '';
		$this->db->where('Kd_Aset1 != 2 AND Kd_Aset1 != 5');
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_kibf($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }

		if ($like != ''){
		return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '3' AND $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '3') AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}


	function json_lainnya($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1',7);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}


	function json_aset_exist($keyword){
		$thn       = $this->session->userdata('tahun_anggaran');
		$kd_bidang = $this->session->userdata('addKd_Bidang');
		$kd_unit   = $this->session->userdata('addKd_Unit');
		$kd_sub    = $this->session->userdata('addKd_Sub');
		$kd_upb    = $this->session->userdata('addKd_UPB');
		
		$where     = " AND a.Kd_Bidang = $kd_bidang";
		$where     .= " AND a.Kd_Unit = $kd_unit";
		$where     .= " AND a.Kd_Sub = $kd_sub";
		$where     .= " AND a.Kd_UPB = $kd_upb";

		$tgl_pembukuan 	= " AND YEAR(Tgl_Pembukuan) <=".$thn;
		$tgl_dokumen 	= " AND YEAR(Tgl_Dokumen) <= '".$thn."'";

		$query = "SELECT TOP 10 a.Nm_Aset5,a.No_Register,a.Kd_Aset1, a.Kd_Aset2, a.Kd_Aset3, 
				a.Kd_Aset4, a.Kd_Aset5, (ISNULL(a.Merk,'-') + ' / ' + ISNULL(a.Type,'-') + ' / ' + ISNULL(a.CC,'-') + ' / ' + ISNULL(a.Bahan,'-') + ' / ' + ISNULL(a.Nomor_Pabrik,'-') + ' / ' + ISNULL(a.Nomor_Rangka,'-') + ' / ' + ISNULL(a.Nomor_Mesin,'-') + ' / ' + ISNULL(a.Nomor_Polisi,'-') + ' / ' + ISNULL(a.Nomor_BPKB,'-')) AS Spesifikasi,
			DATENAME(yyyy,a.Tgl_Perolehan) as Tahun,a.LastKondisi,LastHarga as  Harga,
			a.Keterangan FROM
			(
				SELECT *,((Harga + ISNULL(Koreksi_Tambah, 0) ) - ISNULL(Koreksi_Kurang, 0) ) as LastHarga
					FROM (	
						SELECT n.Nm_Aset5, a1.*,
						(CASE WHEN NewKondisi is null THEN Kondisi ELSE NewKondisi END) as lastKondisi,
/* ----------- jumlah Penambahan ------------*/
					(SELECT SUM(Harga) as Rharga
					FROM Ta_KIBBR a2
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
					FROM Ta_KIBBR a2
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

					FROM Ta_KIB_B a1

					OUTER APPLY (SELECT TOP 1 b.Kondisi as NewKondisi
					             FROM Ta_KIBBR b
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
											AND Kd_Riwayat=1 AND YEAR(b.Tgl_Dokumen) <= '{$thn}'
					             ORDER BY b.Tgl_Dokumen DESC) as b
					
					INNER JOIN Ref_Rek_Aset5 n ON a1.Kd_Aset1 = n.Kd_Aset1 AND a1.Kd_Aset2 = n.Kd_Aset2 
								AND a1.Kd_Aset3 = n.Kd_Aset3 AND a1.Kd_Aset4 = n.Kd_Aset4 
								AND a1.Kd_Aset5 = n.Kd_Aset5

					WHERE NOT EXISTS (SELECT 1 FROM   Ta_KIBBHapus d INNER JOIN Ta_Penghapusan e
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
							AND NOT EXISTS (SELECT 1 FROM  Ta_KIBBR e 
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
WHERE 1=1 $where $tgl_pembukuan";

			// print_r($query); exit();
			return $this->db->query($query);
	}
	
}