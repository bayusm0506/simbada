<?php
/**
 * Contoh_model Class
 */
class Penerimaan_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
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
		SELECT     b.Nm_UPB,a.*, ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS no_urut,
			(SELECT SUM(a2.Jumlah * a2.Harga ) as Jumlah
				FROM Ta_Penerimaan_Rinc a2
				WHERE a.Kd_Prov = a2.Kd_Prov
				AND a.Kd_Kab_Kota = a2.Kd_Kab_Kota
				AND a.Kd_Bidang = a2.Kd_Bidang
				AND a.Kd_UPB = a2.Kd_UPB
				AND a.Kd_Unit = a2.Kd_Unit
				AND a.Kd_Sub = a2.Kd_Sub
				AND a.Kd_UPB = a2.Kd_UPB
				AND a.No_ID = a2.No_ID
				) AS Total
			FROM  Ta_Penerimaan a LEFT JOIN Ref_UPB b ON a.Kd_Bidang=b.Kd_Bidang AND a.Kd_Unit=b.Kd_Unit AND
			a.Kd_Sub=b.Kd_Sub AND a.Kd_UPB=b.Kd_UPB
			WHERE 1=1 $where $like 

		) AS a WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC";

		// print_r($sql); exit();
 		return $this->db->query($sql);
 	}

 	/* count kib b total page */
	function count_kib($where, $like) {

		$sql = "SELECT   COUNT(*) as Jumlah, SUM(Total) as Total FROM  (
		SELECT     b.Nm_UPB,a.*,
			(SELECT SUM(a2.Jumlah * a2.Harga ) as Total
				FROM Ta_Penerimaan_Rinc a2
				WHERE a.Kd_Prov = a2.Kd_Prov
				AND a.Kd_Kab_Kota = a2.Kd_Kab_Kota
				AND a.Kd_Bidang = a2.Kd_Bidang
				AND a.Kd_UPB = a2.Kd_UPB
				AND a.Kd_Unit = a2.Kd_Unit
				AND a.Kd_Sub = a2.Kd_Sub
				AND a.Kd_UPB = a2.Kd_UPB
				AND a.No_ID = a2.No_ID
				) AS Total
			FROM  Ta_Penerimaan a LEFT JOIN Ref_UPB b ON a.Kd_Bidang=b.Kd_Bidang AND a.Kd_Unit=b.Kd_Unit AND
			a.Kd_Sub=b.Kd_Sub AND a.Kd_UPB=b.Kd_UPB
			WHERE 1=1 $where $like 

		) AS a
			WHERE 1=1 $where $like ";

		// print_r($sql); exit();

		return  $this->db->query($sql)->row();

    }
	
	function save($data){


		$id = $this->get_last_noid($this->session->userdata('kd_prov'),
									$this->session->userdata('kd_kab_kota'),
									$this->session->userdata('addKd_Bidang'),
									$this->session->userdata('addKd_Unit'),
									$this->session->userdata('addKd_Sub'),
									$this->session->userdata('addKd_UPB'));
        $arr = array(
			'Kd_Prov'            => $this->session->userdata('kd_prov'),
			'Kd_Kab_Kota'        => $this->session->userdata('kd_kab_kota'),
			'Kd_Bidang'          => $this->session->userdata('addKd_Bidang'),
			'Kd_Unit'            => $this->session->userdata('addKd_Unit'),
			'Kd_Sub'             => $this->session->userdata('addKd_Sub'),
			'Kd_UPB'             => $this->session->userdata('addKd_UPB'),
			'No_ID'              => $id,
			'Tgl_Diterima'       => $data['Tgl_Diterima'],
			'Nm_Rekanan'         => isset($data['Nm_Rekanan']) ? $data['Nm_Rekanan']: '',
			'No_Kontrak'         => $data['No_Kontrak'],
			'Tgl_Kontrak'        => $data['Tgl_Kontrak'],
			'No_BA_Pemeriksaan'  => $data['No_BA_Pemeriksaan'],
			'Tgl_BA_Pemeriksaan' => $data['Tgl_BA_Pemeriksaan'],
			'Keterangan'         => $data['Keterangan'],
			'Log_entry'          => date("Y-m-d H:i:s"),
			'Log_User'           => $this->session->userdata('username')
        );

        return $this->db->insert('Ta_Penerimaan',$arr);
    }

    function update($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id,$data){

        $arr = array(
			'Tgl_Diterima'       => $data['Tgl_Diterima'],
			'Nm_Rekanan'         => isset($data['Nm_Rekanan']) ? $data['Nm_Rekanan']: '',
			'No_Kontrak'         => $data['No_Kontrak'],
			'Tgl_Kontrak'        => $data['Tgl_Kontrak'],
			'No_BA_Pemeriksaan'  => $data['No_BA_Pemeriksaan'],
			'Tgl_BA_Pemeriksaan' => $data['Tgl_BA_Pemeriksaan'],
			'Keterangan'         => $data['Keterangan'],
			'Log_entry'          => date("Y-m-d H:i:s"),
			'Log_User'           => $this->session->userdata('username')
        );

        $this->db->where('Kd_Prov', $kd_prov);
		$this->db->where('Kd_Kab_Kota', $kd_kab);
		$this->db->where('Kd_Bidang', $kd_bidang);
		$this->db->where('Kd_Unit', $kd_unit);
		$this->db->where('Kd_Sub', $kd_sub);
		$this->db->where('Kd_UPB', $kd_upb);
		$this->db->where('No_ID', $no_id);

        $this->db->update('Ta_Penerimaan',$arr);

        // echo $this->db->last_query(); exit();
    }

	function get_last_noid($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb)
	{	
		$where  = "";
		$where .= "AND Kd_Prov = $kd_prov AND Kd_Kab_Kota = $kd_kab AND Kd_Bidang = $kd_bidang
				   AND Kd_Unit = $kd_unit AND Kd_Sub=$kd_sub AND Kd_UPB=$kd_upb";
		$query  = "SELECT ISNULL(MAX(No_ID),0) as No_ID FROM Ta_Penerimaan WHERE 1=1 $where";

		// print_r($query); exit();


        $row = $this->db->query($query)->row_array();

        return $row['No_ID']+1;
		
	}

	function get_penerimaan_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id)
	{	
		$where  = "";
		$where .= "AND Kd_Prov = $kd_prov AND Kd_Kab_Kota = $kd_kab AND Kd_Bidang = $kd_bidang
				   AND Kd_Unit = $kd_unit AND Kd_Sub = $kd_sub AND Kd_UPB = $kd_upb AND No_ID = $no_id";
		$query  = "SELECT * FROM Ta_Penerimaan WHERE 1=1 $where";

        return $this->db->query($query);
		
	}

	function get_penerimaan_rinc_by_id($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6,$no_register)
	{	
		$where  = "";
		$where .= "AND a.Kd_Prov = $kd_prov AND a.Kd_Kab_Kota = $kd_kab_kota AND a.Kd_Bidang = $kd_bidang
				   AND a.Kd_Unit = $kd_unit AND a.Kd_Sub = $kd_sub AND a.Kd_UPB = $kd_upb AND a.No_ID = $no_id";

		$where .= "AND a.Kd_Aset1 = $kd_aset1 AND a.Kd_Aset2 = $kd_aset2 AND a.Kd_Aset3 = $kd_aset3
				   AND a.Kd_Aset4 = $kd_aset4 AND a.Kd_Aset5 = $kd_aset5 AND a.Kd_Aset6 = $kd_aset6 AND a.No_Register = $no_register";
		
		$query  = "SELECT a.*,c.Nm_Aset6 FROM Ta_Penerimaan_Rinc a INNER JOIN Ref_SSH c ON a.Kd_Aset1=c.Kd_Aset1 AND a.Kd_Aset2=c.Kd_Aset2 AND a.Kd_Aset3=c.Kd_Aset3 AND a.Kd_Aset4=c.Kd_Aset4 AND a.Kd_Aset5=c.Kd_Aset5 AND a.Kd_Aset6=c.Kd_Aset6
		 WHERE 1=1 $where";

		// print_r($query); exit();

        return $this->db->query($query);
		
	}

	function get_penerimaan($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
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

	$query = "SELECT b.Tgl_Diterima,c.Nm_Aset6,c.Spesifikasi,b.Tgl_Kontrak,b.No_Kontrak,b.No_BA_Pemeriksaan,b.Tgl_BA_Pemeriksaan,d.Nm_Satuan, a.*,b.Keterangan FROM Ta_Penerimaan_Rinc a
		    INNER JOIN Ta_Penerimaan b ON a.Kd_Prov=b.Kd_Prov AND a.Kd_Kab_Kota=b.Kd_Kab_Kota AND a.Kd_Bidang=b.Kd_Bidang AND a.Kd_Unit=b.Kd_Unit AND a.Kd_Sub=b.Kd_Sub AND a.Kd_UPB=b.Kd_UPB AND a.No_ID=b.No_ID
			INNER JOIN Ref_SSH c ON a.Kd_Aset1=c.Kd_Aset1 AND a.Kd_Aset2=c.Kd_Aset2 AND a.Kd_Aset3=c.Kd_Aset3 AND a.Kd_Aset4=c.Kd_Aset4 AND a.Kd_Aset5=c.Kd_Aset5 AND a.Kd_Aset6=c.Kd_Aset6
			INNER JOIN Ref_Satuan_Harga d ON a.Kd_Satuan=d.Kd_Satuan
			WHERE 1=1 $where $like";

			// print_r($query); exit();
			return $this->db->query($query);
		}

	

	function penerimaan_rinc($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id)
	{	

		$where  = "";
		$where .= "AND a.Kd_Prov = $kd_prov AND a.Kd_Kab_Kota = $kd_kab AND a.Kd_Bidang = $kd_bidang
				   AND a.Kd_Unit = $kd_unit AND a.Kd_Sub = $kd_sub AND a.Kd_UPB = $kd_upb AND a.No_ID = $no_id";
		$query  = "SELECT c.Tgl_Diterima,c.Nm_Rekanan,c.No_Kontrak,c.Tgl_Kontrak,c.No_BA_Pemeriksaan,c.No_BA_Pemeriksaan,c.Tgl_BA_Pemeriksaan,c.Keterangan,b.Nm_Aset6,a.*,d.Nm_Satuan FROM Ta_Penerimaan_Rinc a LEFT JOIN Ta_Penerimaan c ON a.Kd_Prov=c.Kd_Prov AND a.Kd_Kab_Kota=c.Kd_Kab_Kota AND a.Kd_Bidang=c.Kd_Bidang AND a.Kd_Unit=c.Kd_Unit AND a.Kd_Sub=c.Kd_Sub AND a.Kd_UPB=c.Kd_UPB AND a.No_ID=c.No_ID
					LEFT JOIN Ref_SSH b ON a.Kd_Aset1=b.Kd_Aset1 AND a.Kd_Aset2=b.Kd_Aset2 AND a.Kd_Aset3=b.Kd_Aset3 AND a.Kd_Aset4=b.Kd_Aset4 AND a.Kd_Aset5=b.Kd_Aset5 AND a.Kd_Aset6=b.Kd_Aset6
					INNER JOIN Ref_Satuan_Harga d ON a.Kd_Satuan=d.Kd_Satuan
					WHERE 1=1 $where ORDER BY Log_entry DESC";
		// print_r($query); exit();
        return $this->db->query($query);
		
	}

	function get_last_noreg($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6)
	{	
		$where  = "";
		$where .= "AND Kd_prov = $kd_prov AND Kd_Kab_Kota = $kd_kab AND Kd_Bidang = $kd_bidang AND Kd_Unit = $kd_unit AND Kd_Sub=$kd_sub AND Kd_UPB=$kd_upb 
				   AND Kd_Aset1=$kd_aset1 AND Kd_Aset2=$kd_aset2 AND Kd_Aset3=$kd_aset3 AND Kd_Aset4=$kd_aset4 AND Kd_Aset5=$kd_aset5
				   AND Kd_Aset6=$kd_aset6";
		$query   = "SELECT MAX(No_Register) as No_Register FROM Ta_Penerimaan_Rinc WHERE 1=1 $where";

		// print_r($query); exit();

        $row = $this->db->query($query)->row_array();

        return $row['No_Register']+1;
	}

	function hapus_rinc($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6,$no_register)
	{
		$this->db->delete("Ta_Penerimaan_Rinc", array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'No_ID' => $no_id,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'Kd_Aset6' => $kd_aset6,'No_Register' => $no_register));
		$this->db->delete("Ta_Pengeluaran", array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'No_ID' => $no_id,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'Kd_Aset6' => $kd_aset6,'No_Register' => $no_register));
	}

	function hapus_kontrak($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id)
	{
		$this->db->delete("Ta_Penerimaan", array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'No_ID' => $no_id));
		$this->db->delete("Ta_Penerimaan_Rinc", array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'No_ID' => $no_id));
		$this->db->delete("Ta_Pengeluaran", array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'No_ID' => $no_id));
	}


	function json_stok_penerimaan($keyword){
		
		$kd_prov   = $this->session->userdata('kd_prov');
		$kd_kab    = $this->session->userdata('kd_kab_kota');
		$kd_bidang = $this->session->userdata('addKd_Bidang');
		$kd_unit   = $this->session->userdata('addKd_Unit');
		$kd_sub    = $this->session->userdata('addKd_Sub');
		$kd_upb    = $this->session->userdata('addKd_UPB');

		$where  = "";
		$where .= "AND a.Kd_Prov = $kd_prov AND a.Kd_Kab_Kota = $kd_kab AND a.Kd_Bidang = $kd_bidang
				   AND a.Kd_Unit = $kd_unit AND a.Kd_Sub = $kd_sub AND a.Kd_UPB = $kd_upb";
		$where .= "AND a.Nm_Aset LIKE '%$keyword%'";		   
		$query  = "SELECT TOP 10 * FROM (
			SELECT e.Tgl_Diterima, a.*,d.Nm_Satuan,b.Nm_Aset6,b.Spesifikasi, (b.Nm_Aset6+' '+b.Spesifikasi) as Nm_Aset,
(SELECT SUM(a2.Jumlah) as Jumlah
	FROM Ta_Pengeluaran a2
	WHERE a.Kd_Prov = a2.Kd_Prov
	AND a.Kd_Kab_Kota = a2.Kd_Kab_Kota
	AND a.Kd_Bidang = a2.Kd_Bidang
	AND a.Kd_UPB = a2.Kd_UPB
	AND a.Kd_Unit = a2.Kd_Unit
	AND a.Kd_Sub = a2.Kd_Sub
	AND a.Kd_UPB = a2.Kd_UPB
	AND a.No_ID = a2.No_ID
	AND a.Kd_Aset1 = a2.Kd_Aset1
	AND a.Kd_Aset2 = a2.Kd_Aset2
  AND a.Kd_Aset3 = a2.Kd_Aset3
  AND a.Kd_Aset4 = a2.Kd_Aset4
  AND a.Kd_Aset5 = a2.Kd_Aset5
  AND a.Kd_Aset6 = a2.Kd_Aset6
  AND a.No_Register = a2.No_Register
	) AS Keluar,
(SELECT (ISNULL(a.Jumlah, 0) - ISNULL(SUM(Jumlah), 0)) as Jumlah
	FROM Ta_Pengeluaran a2
	WHERE a.Kd_Prov = a2.Kd_Prov
	AND a.Kd_Kab_Kota = a2.Kd_Kab_Kota
	AND a.Kd_Bidang = a2.Kd_Bidang
	AND a.Kd_UPB = a2.Kd_UPB
	AND a.Kd_Unit = a2.Kd_Unit
	AND a.Kd_Sub = a2.Kd_Sub
	AND a.Kd_UPB = a2.Kd_UPB
	AND a.No_ID = a2.No_ID
	AND a.Kd_Aset1 = a2.Kd_Aset1
	AND a.Kd_Aset2 = a2.Kd_Aset2
  AND a.Kd_Aset3 = a2.Kd_Aset3
  AND a.Kd_Aset4 = a2.Kd_Aset4
  AND a.Kd_Aset5 = a2.Kd_Aset5
  AND a.Kd_Aset6 = a2.Kd_Aset6
  AND a.No_Register = a2.No_Register
	) AS Stok

 FROM Ta_Penerimaan_Rinc a
INNER JOIN Ref_SSH b ON a.Kd_Aset1=b.Kd_Aset1 AND a.Kd_Aset2=b.Kd_Aset2 AND a.Kd_Aset3=b.Kd_Aset3 AND a.Kd_Aset4=b.Kd_Aset4 AND a.Kd_Aset5=b.Kd_Aset5 AND a.Kd_Aset6=b.Kd_Aset6
LEFT JOIN Ref_Satuan_Harga d ON a.Kd_Satuan=d.Kd_Satuan
LEFT JOIN Ta_Penerimaan e ON a.Kd_Prov=e.Kd_Prov AND a.Kd_Kab_Kota=e.Kd_Kab_Kota AND a.Kd_Bidang=e.Kd_Bidang AND a.Kd_Unit=e.Kd_Unit AND a.Kd_Sub=e.Kd_Sub AND a.Kd_UPB=e.Kd_UPB AND a.No_ID=e.No_ID
		) as a
WHERE 1=1 $where AND Stok <> 0 ORDER BY Tgl_Diterima,Nm_Aset6";

		$sql = $this->db->query($query);
        
        return $sql->result();
	}

	function buku_penerimaan($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
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

		$query = "SELECT a.*,c.Nm_Aset6,e.Nm_Satuan,c.Spesifikasi,b.Tgl_Diterima,b.Nm_Rekanan,b.No_Kontrak,b.Tgl_Kontrak,b.Tgl_BA_Pemeriksaan,b.No_BA_Pemeriksaan,b.Keterangan FROM Ta_Penerimaan_Rinc a
		LEFT JOIN Ta_Penerimaan b ON a.Kd_Prov=b.Kd_Prov AND a.Kd_Kab_Kota=b.Kd_Kab_Kota AND a.Kd_Bidang=b.Kd_Bidang AND a.Kd_Unit=b.Kd_Unit AND a.Kd_Sub=b.Kd_Sub AND a.Kd_UPB=b.Kd_UPB AND a.No_ID=b.No_ID

		INNER JOIN Ref_SSH c ON a.Kd_Aset1=c.Kd_Aset1 AND a.Kd_Aset2=c.Kd_Aset2 AND a.Kd_Aset3=c.Kd_Aset3 AND a.Kd_Aset4=c.Kd_Aset4 AND a.Kd_Aset5=c.Kd_Aset5 AND a.Kd_Aset6=c.Kd_Aset6
		INNER JOIN Ref_Satuan_Harga e ON a.Kd_Satuan=e.Kd_Satuan

			WHERE 1=1 $where";

			// print_r($query); exit();
			return $this->db->query($query);
		}

	function getDataStok($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb)
	{	
		$where  = "";
		$where .= "AND a.Kd_Prov = $kd_prov AND a.Kd_Kab_Kota = $kd_kab AND a.Kd_Bidang = $kd_bidang
				   AND a.Kd_Unit = $kd_unit AND a.Kd_Sub = $kd_sub AND a.Kd_UPB = $kd_upb";

		$query  = " SELECT * FROM (
			SELECT e.Tgl_Diterima,e.No_Kontrak, a.*,d.Nm_Satuan,b.Nm_Aset6, (b.Nm_Aset6+' '+b.Spesifikasi) as Nm_Aset,
				(SELECT SUM(a2.Jumlah) as Jumlah
					FROM Ta_Pengeluaran a2
					WHERE a.Kd_Prov = a2.Kd_Prov
					AND a.Kd_Kab_Kota = a2.Kd_Kab_Kota
					AND a.Kd_Bidang = a2.Kd_Bidang
					AND a.Kd_Unit = a2.Kd_Unit
					AND a.Kd_Sub = a2.Kd_Sub
					AND a.Kd_UPB = a2.Kd_UPB
					AND a.No_ID = a2.No_ID
					AND a.Kd_Aset1 = a2.Kd_Aset1
					AND a.Kd_Aset2 = a2.Kd_Aset2
				  AND a.Kd_Aset3 = a2.Kd_Aset3
				  AND a.Kd_Aset4 = a2.Kd_Aset4
				  AND a.Kd_Aset5 = a2.Kd_Aset5
				  AND a.Kd_Aset6 = a2.Kd_Aset6
				  AND a.No_Register = a2.No_Register
					) AS Keluar,
				(SELECT (ISNULL(a.Jumlah, 0) - ISNULL(SUM(Jumlah), 0)) as Jumlah
					FROM Ta_Pengeluaran a2
					WHERE a.Kd_Prov = a2.Kd_Prov
					AND a.Kd_Kab_Kota = a2.Kd_Kab_Kota
					AND a.Kd_Bidang = a2.Kd_Bidang
					AND a.Kd_Unit = a2.Kd_Unit
					AND a.Kd_Sub = a2.Kd_Sub
					AND a.Kd_UPB = a2.Kd_UPB
					AND a.No_ID = a2.No_ID
					AND a.Kd_Aset1 = a2.Kd_Aset1
					AND a.Kd_Aset2 = a2.Kd_Aset2
				  AND a.Kd_Aset3 = a2.Kd_Aset3
				  AND a.Kd_Aset4 = a2.Kd_Aset4
				  AND a.Kd_Aset5 = a2.Kd_Aset5
				  AND a.Kd_Aset6 = a2.Kd_Aset6
				  AND a.No_Register = a2.No_Register
					) AS Stok

 FROM Ta_Penerimaan_Rinc a
INNER JOIN Ref_SSH b ON a.Kd_Aset1=b.Kd_Aset1 AND a.Kd_Aset2=b.Kd_Aset2 AND a.Kd_Aset3=b.Kd_Aset3 AND a.Kd_Aset4=b.Kd_Aset4 AND a.Kd_Aset5=b.Kd_Aset5 AND a.Kd_Aset6=b.Kd_Aset6
LEFT JOIN Ref_Satuan_Harga d ON a.Kd_Satuan=d.Kd_Satuan
LEFT JOIN Ta_Penerimaan e ON a.Kd_Prov=e.Kd_Prov AND a.Kd_Kab_Kota=e.Kd_Kab_Kota AND a.Kd_Bidang=e.Kd_Bidang AND a.Kd_Unit=e.Kd_Unit AND a.Kd_Sub=e.Kd_Sub AND a.Kd_UPB=e.Kd_UPB AND a.No_ID=e.No_ID
		) as a
WHERE 1=1 $where AND Stok > 0 ORDER BY Nm_Aset6,Tgl_Diterima";

		// print_r($query); exit();

        return $this->db->query($query);
	}


	function rekap_persediaan($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
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
	
	$tgl_pembukuan    = " AND Tgl_Pembukuan ".$like;
	$thn_before_login = $this->session->userdata('tahun_anggaran')-1;
	$thn              = $this->session->userdata('tahun_anggaran');

	$query = "SELECT a.Kd_Bidang,a.Kd_Unit,a.Kd_Sub,a.Kd_UPB,
		a.Kd_Aset1,a.Kd_Aset2,a.Kd_Aset3,a.Kd_Aset4,a.Kd_Aset5,a.Kd_Aset6,
		b.Nm_SSH2,c.Nm_SSH3,d.Nm_SSH4,e.Nm_SSH5,
		SUM(CASE WHEN Cluster = 'before' THEN Stok ELSE null END) as Jumlah_Awal,
		SUM(CASE WHEN Cluster = 'before' THEN Stok*Harga ELSE null END) as Total_Awal,
		SUM(Jml_Kurang_Now) as Jumlah_Kurang_Now,
		SUM(Jml_Kurang_Now*Harga) as Total_Kurang_Now,
		SUM(CASE WHEN Cluster = 'now' THEN Stok ELSE null END) as Jumlah_Tambah_Now,
		SUM(CASE WHEN Cluster = 'now' THEN Stok*Harga ELSE null END) as Total_Tambah_Now,
		SUM(ISNULL((Stok),0)-ISNULL((Jml_Kurang_Now),0)) as Jumlah_Akhir,
		SUM(ISNULL((Stok*Harga),0) - ISNULL((Jml_Kurang_Now*Harga),0) )  as Total_Akhir
		 FROM (

	/* start view tahun 2015 */
	SELECT 'before' as Cluster,* FROM (

	SELECT h.Tgl_Diterima,h.No_Kontrak,a.*,

		(SELECT (ISNULL(a.Jumlah, 0) - ISNULL(SUM(Jumlah), 0)) as Jumlah
			FROM Ta_Pengeluaran a2
			WHERE a.Kd_Prov = a2.Kd_Prov
			AND a.Kd_Kab_Kota = a2.Kd_Kab_Kota
			AND a.Kd_Bidang = a2.Kd_Bidang
			AND a.Kd_UPB = a2.Kd_UPB
			AND a.Kd_Unit = a2.Kd_Unit
			AND a.Kd_Sub = a2.Kd_Sub
			AND a.Kd_UPB = a2.Kd_UPB
			AND a.No_ID = a2.No_ID
			AND a.Kd_Aset1 = a2.Kd_Aset1
			AND a.Kd_Aset2 = a2.Kd_Aset2
			AND a.Kd_Aset3 = a2.Kd_Aset3
			AND a.Kd_Aset4 = a2.Kd_Aset4
			AND a.Kd_Aset5 = a2.Kd_Aset5
			AND a.Kd_Aset6 = a2.Kd_Aset6
			AND a.No_Register = a2.No_Register
			AND a2.Tgl_Pengeluaran < '{$tahunawal}'
		) AS Stok,

		(SELECT SUM(a2.Jumlah) as Jumlah
			FROM Ta_Pengeluaran a2
			WHERE a.Kd_Prov = a2.Kd_Prov
			AND a.Kd_Kab_Kota = a2.Kd_Kab_Kota
			AND a.Kd_Bidang = a2.Kd_Bidang
			AND a.Kd_UPB = a2.Kd_UPB
			AND a.Kd_Unit = a2.Kd_Unit
			AND a.Kd_Sub = a2.Kd_Sub
			AND a.Kd_UPB = a2.Kd_UPB
			AND a.No_ID = a2.No_ID
			AND a.Kd_Aset1 = a2.Kd_Aset1
			AND a.Kd_Aset2 = a2.Kd_Aset2
			AND a.Kd_Aset3 = a2.Kd_Aset3
			AND a.Kd_Aset4 = a2.Kd_Aset4
			AND a.Kd_Aset5 = a2.Kd_Aset5
			AND a.Kd_Aset6 = a2.Kd_Aset6
			AND a.No_Register = a2.No_Register
			AND a2.Tgl_Pengeluaran BETWEEN '$tahunawal' AND '$tahunakhir'
		) AS Jml_Kurang_Now

	 FROM Ta_Penerimaan_Rinc a 

	LEFT JOIN Ref_Satuan_Harga g ON a.Kd_Satuan=g.Kd_Satuan
	
	LEFT JOIN Ta_Penerimaan h ON a.Kd_Prov=h.Kd_Prov AND a.Kd_Kab_Kota=h.Kd_Kab_Kota AND a.Kd_Bidang=h.Kd_Bidang AND a.Kd_Unit=h.Kd_Unit AND a.Kd_Sub=h.Kd_Sub AND a.Kd_UPB=h.Kd_UPB AND a.No_ID=h.No_ID

	) as data_awal WHERE Stok > 0 AND Tgl_Diterima < '{$tahunawal}'
	/* end view tahun 2015 */
	UNION ALL

	SELECT 'now' as Cluster,h.Tgl_Diterima,h.No_Kontrak,a.*, Jumlah as Stok,

		(SELECT SUM(a2.Jumlah) as Jumlah
			FROM Ta_Pengeluaran a2
			WHERE a.Kd_Prov = a2.Kd_Prov
			AND a.Kd_Kab_Kota = a2.Kd_Kab_Kota
			AND a.Kd_Bidang = a2.Kd_Bidang
			AND a.Kd_UPB = a2.Kd_UPB
			AND a.Kd_Unit = a2.Kd_Unit
			AND a.Kd_Sub = a2.Kd_Sub
			AND a.Kd_UPB = a2.Kd_UPB
			AND a.No_ID = a2.No_ID
			AND a.Kd_Aset1 = a2.Kd_Aset1
			AND a.Kd_Aset2 = a2.Kd_Aset2
			AND a.Kd_Aset3 = a2.Kd_Aset3
			AND a.Kd_Aset4 = a2.Kd_Aset4
			AND a.Kd_Aset5 = a2.Kd_Aset5
			AND a.Kd_Aset6 = a2.Kd_Aset6
			AND a.No_Register = a2.No_Register
			AND a2.Tgl_Pengeluaran BETWEEN '$tahunawal' AND '$tahunakhir'
		) AS Jml_Kurang_Now

	 FROM Ta_Penerimaan_Rinc a 
	LEFT JOIN Ref_Satuan_Harga g ON a.Kd_Satuan=g.Kd_Satuan
	LEFT JOIN Ta_Penerimaan h ON a.Kd_Prov=h.Kd_Prov AND a.Kd_Kab_Kota=h.Kd_Kab_Kota AND a.Kd_Bidang=h.Kd_Bidang AND a.Kd_Unit=h.Kd_Unit AND a.Kd_Sub=h.Kd_Sub AND a.Kd_UPB=h.Kd_UPB AND a.No_ID=h.No_ID

	WHERE 1=1 AND Tgl_Diterima BETWEEN '$tahunawal' AND '$tahunakhir'

) as a INNER JOIN Ref_SSH2 b ON a.Kd_Aset1=b.Kd_SSH1 AND a.Kd_Aset2=b.Kd_SSH2

	INNER JOIN Ref_SSH3 c ON a.Kd_Aset1=c.Kd_SSH1 AND a.Kd_Aset2=c.Kd_SSH2 AND a.Kd_Aset3=c.Kd_SSH3

	INNER JOIN Ref_SSH4 d ON a.Kd_Aset1=d.Kd_SSH1 AND a.Kd_Aset2=d.Kd_SSH2 AND a.Kd_Aset3=d.Kd_SSH3 AND a.Kd_Aset4=d.Kd_SSH4

	INNER JOIN Ref_SSH5 e ON a.Kd_Aset1=e.Kd_SSH1 AND a.Kd_Aset2=e.Kd_SSH2 AND a.Kd_Aset3=e.Kd_SSH3 AND a.Kd_Aset4=e.Kd_SSH4 AND a.Kd_Aset5=e.Kd_SSH5
	
	WHERE 1=1 $where

	GROUP BY a.Kd_Bidang,a.Kd_Unit,a.Kd_Sub,a.Kd_UPB,
		a.Kd_Aset1,a.Kd_Aset2,a.Kd_Aset3,a.Kd_Aset4,a.Kd_Aset5,a.Kd_Aset6,
		b.Nm_SSH2,c.Nm_SSH3,d.Nm_SSH4,e.Nm_SSH5";

			// print_r($query); exit();
			return $this->db->query($query);
		}

}

/* End of file Contoh_model.php */
/* Location: ./system/application/models/Contoh_model.php */