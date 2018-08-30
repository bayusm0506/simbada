<?php
/**
 * Contoh_model Class
 */
class Pengeluaran_model extends CI_Model {
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
			
				SELECT *,
				ROW_NUMBER () OVER (ORDER BY Log_entry DESC) AS no_urut FROM (
					SELECT f.No_Kontrak,b.Nm_UPB,c.*,d.Nm_Aset6,e.Nm_Satuan,a.Harga	FROM  Ta_Pengeluaran c LEFT JOIN Ref_UPB b ON c.Kd_Bidang=b.Kd_Bidang AND c.Kd_Unit=b.Kd_Unit AND c.Kd_Sub=b.Kd_Sub AND c.Kd_UPB=b.Kd_UPB
				LEFT JOIN Ta_Penerimaan_Rinc a ON c.Kd_Prov=a.Kd_Prov AND c.Kd_Kab_Kota=a.Kd_Kab_Kota AND c.Kd_Bidang=a.Kd_Bidang AND c.Kd_Unit=a.Kd_Unit AND c.Kd_Sub=a.Kd_Sub AND c.Kd_UPB=a.Kd_UPB AND c.No_ID=a.No_ID AND c.Kd_Aset1=a.Kd_Aset1 AND c.Kd_Aset2=a.Kd_Aset2 AND c.Kd_Aset3=a.Kd_Aset3 AND c.Kd_Aset4=a.Kd_Aset4 AND c.Kd_Aset5=a.Kd_Aset5 AND c.Kd_Aset6=a.Kd_Aset6 AND c.No_Register=a.No_Register
				INNER JOIN Ta_Penerimaan f ON f.Kd_Prov=a.Kd_Prov AND f.Kd_Kab_Kota=a.Kd_Kab_Kota AND f.Kd_Bidang=a.Kd_Bidang AND f.Kd_Unit=a.Kd_Unit AND f.Kd_Sub=a.Kd_Sub AND f.Kd_UPB=a.Kd_UPB AND f.No_ID=a.No_ID
				INNER JOIN Ref_SSH d ON c.Kd_Aset1=d.Kd_Aset1 AND c.Kd_Aset2=d.Kd_Aset2 AND c.Kd_Aset3=d.Kd_Aset3 AND c.Kd_Aset4=d.Kd_Aset4 AND c.Kd_Aset5=d.Kd_Aset5 AND c.Kd_Aset6=d.Kd_Aset6
				LEFT JOIN Ref_Satuan_Harga e ON a.Kd_Satuan=e.Kd_Satuan
				 WHERE 1=1 $where
				) as a WHERE 1=1 $like

			) AS a WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC";

		// print_r($sql); exit();
 		return $this->db->query($sql);
 	}

 	/* count kib b total page */
	function count_data($where, $like) {

		$sql = "SELECT  COUNT(*) as Jumlah, SUM(Harga * Jumlah) as Total FROM  (
			
				SELECT * FROM (
					SELECT f.No_Kontrak,b.Nm_UPB,c.*,d.Nm_Aset6,e.Nm_Satuan,a.Harga	FROM  Ta_Pengeluaran c LEFT JOIN Ref_UPB b ON c.Kd_Bidang=b.Kd_Bidang AND c.Kd_Unit=b.Kd_Unit AND c.Kd_Sub=b.Kd_Sub AND c.Kd_UPB=b.Kd_UPB
				LEFT JOIN Ta_Penerimaan_Rinc a ON c.Kd_Prov=a.Kd_Prov AND c.Kd_Kab_Kota=a.Kd_Kab_Kota AND c.Kd_Bidang=a.Kd_Bidang AND c.Kd_Unit=a.Kd_Unit AND c.Kd_Sub=a.Kd_Sub AND c.Kd_UPB=a.Kd_UPB AND c.No_ID=a.No_ID AND c.Kd_Aset1=a.Kd_Aset1 AND c.Kd_Aset2=a.Kd_Aset2 AND c.Kd_Aset3=a.Kd_Aset3 AND c.Kd_Aset4=a.Kd_Aset4 AND c.Kd_Aset5=a.Kd_Aset5 AND c.Kd_Aset6=a.Kd_Aset6 AND c.No_Register=a.No_Register
				INNER JOIN Ta_Penerimaan f ON f.Kd_Prov=a.Kd_Prov AND f.Kd_Kab_Kota=a.Kd_Kab_Kota AND f.Kd_Bidang=a.Kd_Bidang AND f.Kd_Unit=a.Kd_Unit AND f.Kd_Sub=a.Kd_Sub AND f.Kd_UPB=a.Kd_UPB AND f.No_ID=a.No_ID
				INNER JOIN Ref_SSH d ON c.Kd_Aset1=d.Kd_Aset1 AND c.Kd_Aset2=d.Kd_Aset2 AND c.Kd_Aset3=d.Kd_Aset3 AND c.Kd_Aset4=d.Kd_Aset4 AND c.Kd_Aset5=d.Kd_Aset5 AND c.Kd_Aset6=d.Kd_Aset6
				LEFT JOIN Ref_Satuan_Harga e ON a.Kd_Satuan=e.Kd_Satuan
				 WHERE 1=1 $where
				) as a WHERE 1=1 $like

			) AS a ";
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

	function buku_persediaan($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
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

	$tgl_diterima 	= " AND Tgl_Diterima ".$like;
	$tgl_kontrak 	= " AND Tgl_Kontrak <= '".$tahunakhir."'";

	$query = "SELECT     b.Nm_UPB,a.*	FROM  Ta_Penerimaan a LEFT JOIN Ref_UPB b ON a.Kd_Bidang=b.Kd_Bidang AND a.Kd_Unit=b.Kd_Unit AND
			a.Kd_Sub=b.Kd_Sub AND a.Kd_UPB=b.Kd_UPB
			WHERE 1=1 $where";

			// print_r($query); exit();
			return $this->db->query($query);
		}

	function pengeluaran_rinc()
	{	

		$where  = " AND Sess_Out = '".$this->session->userdata('sess_out')."'";
		$query  = "SELECT a.*,d.Nm_Aset6,d.Spesifikasi,e.Nm_Satuan,c.Harga FROM  Ta_Pengeluaran a 
			LEFT JOIN Ta_Penerimaan_Rinc c ON a.Kd_Prov=c.Kd_Prov AND a.Kd_Kab_Kota=c.Kd_Kab_Kota AND a.Kd_Bidang=c.Kd_Bidang AND a.Kd_Unit=c.Kd_Unit AND a.Kd_Sub=c.Kd_Sub AND a.Kd_UPB=c.Kd_UPB AND a.No_ID=c.No_ID AND a.Kd_Aset1=c.Kd_Aset1 AND a.Kd_Aset2=c.Kd_Aset2 AND a.Kd_Aset3=c.Kd_Aset3 AND a.Kd_Aset4=c.Kd_Aset4 AND a.Kd_Aset5=c.Kd_Aset5 AND a.Kd_Aset6=c.Kd_Aset6 AND a.No_Register=c.No_Register

			INNER JOIN Ref_SSH d ON a.Kd_Aset1=d.Kd_Aset1 AND a.Kd_Aset2=d.Kd_Aset2 AND a.Kd_Aset3=d.Kd_Aset3 AND a.Kd_Aset4=d.Kd_Aset4 AND a.Kd_Aset5=d.Kd_Aset5 AND a.Kd_Aset6=d.Kd_Aset6
			INNER JOIN Ref_Satuan_Harga e ON c.Kd_Satuan=e.Kd_Satuan

			WHERE 1=1 $where ORDER BY Log_entry DESC";
		// print_r($query); exit();
        return $this->db->query($query);
		
	}

	function get_last_idkeluar($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6,$no_register)
	{	
		$where  = "";
		$where .= "AND Kd_prov = $kd_prov AND Kd_Kab_Kota = $kd_kab AND Kd_Bidang = $kd_bidang AND Kd_Unit = $kd_unit AND Kd_Sub=$kd_sub AND Kd_UPB=$kd_upb 
				   AND Kd_Aset1=$kd_aset1 AND Kd_Aset2=$kd_aset2 AND Kd_Aset3=$kd_aset3 AND Kd_Aset4=$kd_aset4 AND Kd_Aset5=$kd_aset5
				   AND Kd_Aset6=$kd_aset6 AND No_Register=$no_register";
		$query   = "SELECT MAX(Id_Pengeluaran) as Id_Pengeluaran FROM Ta_Pengeluaran WHERE 1=1 $where";

		// print_r($query); exit();

        $row = $this->db->query($query)->row_array();

        return $row['Id_Pengeluaran']+1;		
	}

	function hapus($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6,$no_register,$id_pengeluaran)
	{
		$this->db->delete("Ta_Pengeluaran", array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab_kota,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'No_ID' => $no_id,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'Kd_Aset6' => $kd_aset6,'No_Register' => $no_register,'Id_Pengeluaran' => $id_pengeluaran));
	}

	function json_stok_persediaan($keyword){
		
		$kd_prov   = $this->session->userdata('kd_prov');
		$kd_kab    = $this->session->userdata('kd_kab_kota');
		$kd_bidang = $this->session->userdata('addKd_Bidang');
		$kd_unit   = $this->session->userdata('addKd_Unit');
		$kd_sub    = $this->session->userdata('addKd_Sub');
		$kd_upb    = $this->session->userdata('addKd_UPB');

		$where  = "";
		$where .= "AND a.Kd_Prov = $kd_prov AND a.Kd_Kab_Kota = $kd_kab AND a.Kd_Bidang = $kd_bidang
				   AND a.Kd_Unit = $kd_unit AND a.Kd_Sub = $kd_sub AND a.Kd_UPB = $kd_upb";
		$where .= "AND a.Nm_Aset6 LIKE '%$keyword%'";		   
		$query  = "SELECT TOP 10 a.*,d.Nm_Satuan,b.Nm_Aset6, 
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
WHERE 1=1";

		$sql = $this->db->query($query);
        
        return $sql->result();
	}


	function get_pengeluaran($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6,$no_register,$like)
	{

	$where = "AND a.Kd_prov = $kd_prov AND a.Kd_Kab_Kota = $kd_kab_kota AND a.Kd_Bidang = $kd_bidang AND a.Kd_Unit = $kd_unit AND a.Kd_Sub=$kd_sub AND a.Kd_UPB=$kd_upb 
			  AND a.Kd_Aset1=$kd_aset1 AND a.Kd_Aset2=$kd_aset2 AND a.Kd_Aset3=$kd_aset3 AND a.Kd_Aset4=$kd_aset4 AND a.Kd_Aset5=$kd_aset5 AND a.Kd_Aset6=$kd_aset6 AND a.No_Register=$no_register";
	$query = "SELECT * FROM Ta_Pengeluaran a WHERE 1=1 $where $like ORDER BY a.Tgl_Pengeluaran";

			// print_r($query); exit();
			return $this->db->query($query);
	}

	function buku_pengeluaran($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
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

		$tgl_pengeluaran 	= " AND Tgl_Pengeluaran ".$like;

		$query = "SELECT a.*,c.Nm_Aset6,c.Spesifikasi,b.Harga,e.Nm_Satuan FROM Ta_Pengeluaran a
			LEFT JOIN Ta_Penerimaan_Rinc b ON a.Kd_Prov=b.Kd_Prov AND a.Kd_Kab_Kota=b.Kd_Kab_Kota AND a.Kd_Bidang=b.Kd_Bidang AND a.Kd_Unit=b.Kd_Unit AND a.Kd_Sub=b.Kd_Sub AND a.Kd_UPB=b.Kd_UPB AND a.No_ID=b.No_ID
			AND a.Kd_Aset1=b.Kd_Aset1 AND a.Kd_Aset2=b.Kd_Aset2 AND a.Kd_Aset3=b.Kd_Aset3 AND a.Kd_Aset4=b.Kd_Aset4 AND a.Kd_Aset5=b.Kd_Aset5 AND a.Kd_Aset6=b.Kd_Aset6 AND a.No_Register=b.No_Register
			INNER JOIN Ref_SSH c ON a.Kd_Aset1=c.Kd_Aset1 AND a.Kd_Aset2=c.Kd_Aset2 AND a.Kd_Aset3=c.Kd_Aset3 AND a.Kd_Aset4=c.Kd_Aset4 AND a.Kd_Aset5=c.Kd_Aset5 AND a.Kd_Aset6=c.Kd_Aset6
			INNER JOIN Ref_Satuan_Harga e ON b.Kd_Satuan=e.Kd_Satuan

			WHERE 1=1 $where $tgl_pengeluaran";

			// print_r($query); exit();
			return $this->db->query($query);
		}

}

/* End of file Contoh_model.php */
/* Location: ./system/application/models/Contoh_model.php */