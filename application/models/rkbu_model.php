<?php
/**
 * Contoh_model Class
 */
class Rkbu_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	

	function get_page($limit, $offset, $where='', $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

       $sql ="SELECT * FROM ( 
				SELECT b.Nm_Aset5, '' as Spesifikasi,a.*,c.Nm_Rek_5,ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS urutan FROM Ta_RKBU a
				LEFT JOIN Ref_Rek_Aset5 b ON a.Kd_Aset1=b.Kd_Aset1 AND a.Kd_Aset2=b.Kd_Aset2 AND a.Kd_Aset3=b.Kd_Aset3 AND 
				a.Kd_Aset4=b.Kd_Aset4 AND a.Kd_Aset5=b.Kd_Aset5 
				LEFT JOIN Ref_Rek_5 c ON a.Kd_Rek_1=c.Kd_Rek_1 AND a.Kd_Rek_2=c.Kd_Rek_2 AND a.Kd_Rek_3=c.Kd_Rek_3 AND a.Kd_Rek_4=c.Kd_Rek_4 AND a.Kd_Rek_5=c.Kd_Rek_5
				WHERE 1=1 $where
			) AS Ta_RKBU WHERE 1=1";
		
		// print_r($sql); exit();

		$ret['total'] = $this->db->query($sql)->num_rows();
		$sql .= "AND urutan BETWEEN $first AND $last ORDER BY Log_entry DESC";		
		$ret['data']  = $this->db->query($sql)->result();		
 		return $ret;
 	}

 	function get_rkbu_by_id($kd_prov,$kd_kab,$bidang,$unit,$sub,$upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6,$no_id)
	{
		$tahun =  $this->session->userdata('tahun_anggaran');
		$kb    =  $this->session->userdata('kd_bidang');
		$ku    =  $this->session->userdata('kd_unit');
		$ks    =  $this->session->userdata('kd_sub_unit');
		$kupb  =  $this->session->userdata('kd_upb');
		
		$where  = "";
		$where .= "AND (a.Tahun = $tahun)";
		$where .= "AND (a.Kd_Prov = $kd_prov)";
		$where .= "AND (a.Kd_Kab_Kota = $kd_kab)";

		if ($this->session->userdata('lvl') == 01){
			$where .= "AND (a.Kd_Bidang = $bidang) 
					  AND (a.Kd_Unit = $unit) 
					  AND (a.Kd_Sub = $sub) 
					  AND (a.Kd_UPB = $upb)";
		}elseif ($this->session->userdata('lvl') == 02){
			$where .= "AND (a.Kd_Bidang = $kb )
					  AND (a.Kd_Unit =$ku )
					  AND (a.Kd_Sub = $ks) 
					  AND (a.Kd_UPB = $upb)";
		}else{
			$where .= "AND (a.Kd_Bidang = $kb) 
					  AND (a.Kd_Unit = $ku) 
					  AND (a.Kd_Sub = $ks) 
					  AND (a.Kd_UPB = $kupb)";
		}
			$where .= "AND a.Kd_Aset1 = {$kd_aset1} AND a.Kd_Aset2 = {$kd_aset2} AND a.Kd_Aset3 = {$kd_aset3} AND a.Kd_Aset4 = {$kd_aset4} AND a.Kd_Aset5 = {$kd_aset5} AND a.Kd_Aset6 = {$kd_aset6} AND a.No_ID = {$no_id} ";

			$query = "SELECT b.Nm_Aset6,b.Spesifikasi,a.*,c.Nm_Rek_5 FROM Ta_RKBU a
				LEFT JOIN Ref_SSH b ON a.Kd_Aset1=b.Kd_Aset1 AND a.Kd_Aset2=b.Kd_Aset2 AND a.Kd_Aset3=b.Kd_Aset3 AND 
				a.Kd_Aset4=b.Kd_Aset4 AND a.Kd_Aset5=b.Kd_Aset5 AND a.Kd_Aset6=b.Kd_Aset6 AND a.Tahun=b.Tahun
				LEFT JOIN Ref_Rek_5 c ON a.Kd_Rek_1=c.Kd_Rek_1 AND a.Kd_Rek_2=c.Kd_Rek_2 AND a.Kd_Rek_3=c.Kd_Rek_3 AND a.Kd_Rek_4=c.Kd_Rek_4 AND a.Kd_Rek_5=c.Kd_Rek_5
				WHERE 1=1 $where";

			// print_r($query); exit();
			return $this->db->query($query);
	}

 	function get_last_noid($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6)
	{	
		$thn   =  $this->session->userdata('tahun_anggaran');
		$where  = "";
		$where .= "AND Tahun = $thn ";
		$where .= "AND Kd_Bidang = $kd_bidang AND Kd_Unit = $kd_unit AND Kd_Sub=$kd_sub
				   AND Kd_UPB=$kd_upb AND Kd_Aset1=$kd_aset1 AND Kd_Aset2=$kd_aset2
				   AND Kd_Aset3=$kd_aset3 AND Kd_Aset4=$kd_aset4 AND Kd_Aset5=$kd_aset5
				   AND Kd_Aset6=$kd_aset6";
		$query   = "SELECT MAX(No_ID) as No_ID FROM Ta_RKBU WHERE 1=1 $where";

		// print_r($query); exit();

        $row = $this->db->query($query)->row_array();

        return $row['No_ID']+1;
		
	}

	function ProgramList($bidang='',$unit='',$sub=''){
		$result = array();
		$thn   =  $this->session->userdata('tahun_anggaran');
		$where =  "";
		// $where .= " AND Tahun=$thn";
		$where .= " AND (a.Kd_Bidang = $bidang)";
		$where .= " AND (a.Kd_Unit = $unit)";
		$where .= " AND (a.Kd_Sub = $sub)";

		$query = "SELECT * FROM Ta_Program a WHERE 1=1 $where";
		// print_r($query); exit();
		$sql = $this->db->query($query);

        foreach ($sql->result() as $row)
        {
            $result['']= '- Pilih Program -';
            $result[$row->Kd_Prog]= $row->Kd_Prog.'. '.$row->Ket_Program;
        }
        
        return $result;
	}

	function KegiatanList($bidang='',$unit='',$sub='',$kd_prog=''){
		$result = array();
		$thn   =  $this->session->userdata('tahun_anggaran');
		$where =  "";
		// $where .= " AND Tahun=$thn";
		$where .= " AND (a.Kd_Bidang = $bidang)";
		$where .= " AND (a.Kd_Unit = $unit)";
		$where .= " AND (a.Kd_Sub = $sub)";
		$where .= " AND (a.Kd_Prog = $kd_prog)";

		$query = "SELECT * FROM Ta_Kegiatan a WHERE 1=1 $where";
		// print_r($query); exit();
		$sql = $this->db->query($query);

        foreach ($sql->result() as $row)
        {
            $result['']= '- Pilih Kegiatan -';
            $result[$row->Kd_Keg]= $row->Kd_Keg.'. '.$row->Ket_Kegiatan;
        }
        
        return $result;
	}

	function save($data){

        $arr = array(
            'Tahun'       => $this->session->userdata('tahun_anggaran'),
			'Kd_Prov'     => $this->session->userdata('kd_prov'),
			'Kd_Kab_Kota' => $this->session->userdata('kd_kab_kota'),
			'Kd_Bidang'   => $this->session->userdata('addKd_Bidang'),
			'Kd_Unit'     => $this->session->userdata('addKd_Unit'),
			'Kd_Sub'      => $this->session->userdata('addKd_Sub'),
			'Kd_UPB'      => $this->session->userdata('addKd_UPB'),
			'No_ID'       => $data['No_ID'],
			'Kd_Aset1'    => $data['Kd_Aset1'],
			'Kd_Aset2'    => $data['Kd_Aset2'],
			'Kd_Aset3'    => $data['Kd_Aset3'],
			'Kd_Aset4'    => $data['Kd_Aset4'],
			'Kd_Aset5'    => $data['Kd_Aset5'],
			'Kd_Aset6'    => $data['Kd_Aset6'],
			'Type'        => isset($data['Type']) ? $data['Type']: '',
			'Ukuran'      => isset($data['Ukuran']) ? $data['Ukuran']: '',
			'Jumlah'      => $data['Jumlah'],
			'Harga'       => $data['Harga'],
			'Kebutuhan_Max'      => $data['Kebutuhan_Max'],
			'Kd_Rek_1'    => $data['Kd_Rek_1'],
			'Kd_Rek_2'    => $data['Kd_Rek_2'],
			'Kd_Rek_3'    => $data['Kd_Rek_3'],
			'Kd_Rek_4'    => $data['Kd_Rek_4'],
			'Kd_Rek_5'    => $data['Kd_Rek_5'],
			'Kd_Prog'     => $data['Kd_Prog'],
			'ID_Prog'     => isset($data['ID_Prog']) ? $data['ID_Prog']: '',
			'Kd_Keg'      => $data['Kd_Keg'],
			'Keterangan'  => $data['Keterangan'],
			'Log_entry'   => date("Y-m-d H:i:s")
        );

        // print_r($arr); exit();
        
        return $this->db->insert('Ta_RKBU',$arr);
    }

    function hapus($tahun,$kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6,$no_id)
	{
		$this->db->delete("Ta_RKBU", array('Tahun' => $tahun,'Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,'Kd_Aset4' => $kd_aset4,
		'Kd_Aset5' => $kd_aset5,'Kd_Aset6' => $kd_aset6,'No_ID' => $no_id));
	}

	function update($data){

        $arr = array(
			'No_ID'       => $data['No_ID'],
			'Kd_Aset1'    => $data['Kd_Aset1'],
			'Kd_Aset2'    => $data['Kd_Aset2'],
			'Kd_Aset3'    => $data['Kd_Aset3'],
			'Kd_Aset4'    => $data['Kd_Aset4'],
			'Kd_Aset5'    => $data['Kd_Aset5'],
			'Kd_Aset6'    => $data['Kd_Aset6'],
			'Type'        => isset($data['Type']) ? $data['Type']: '',
			'Ukuran'      => isset($data['Ukuran']) ? $data['Ukuran']: '',
			'Jumlah'      => $data['Jumlah'],
			'Harga'       => $data['Harga'],
			'Kebutuhan_Max'      => $data['Kebutuhan_Max'],
			'Kd_Rek_1'    => $data['Kd_Rek_1'],
			'Kd_Rek_2'    => $data['Kd_Rek_2'],
			'Kd_Rek_3'    => $data['Kd_Rek_3'],
			'Kd_Rek_4'    => $data['Kd_Rek_4'],
			'Kd_Rek_5'    => $data['Kd_Rek_5'],
			'Kd_Prog'     => $data['Kd_Prog'],
			'ID_Prog'     => isset($data['ID_Prog']) ? $data['ID_Prog']: '',
			'Kd_Keg'      => $data['Kd_Keg'],
			'Keterangan'  => $data['Keterangan'],
        );

        // print_r($arr); exit();

    	$tahun       = $this->session->userdata('tmp_Tahun');
		$kd_prov     = $this->session->userdata('tmp_Kd_Prov');
		$kd_kab_kota = $this->session->userdata('tmp_Kd_Kab_Kota');
		$kd_bidang   = $this->session->userdata('tmp_Kd_Bidang');
		$kd_unit     = $this->session->userdata('tmp_Kd_Unit');
		$kd_sub      = $this->session->userdata('tmp_Kd_Sub');
		$kd_upb      = $this->session->userdata('tmp_Kd_UPB');
		$kd_aset1    = $this->session->userdata('tmp_Kd_Aset1');
		$kd_aset2    = $this->session->userdata('tmp_Kd_Aset2');
		$kd_aset3    = $this->session->userdata('tmp_Kd_Aset3');
		$kd_aset4    = $this->session->userdata('tmp_Kd_Aset4');
		$kd_aset5    = $this->session->userdata('tmp_Kd_Aset5');
		$kd_aset6    = $this->session->userdata('tmp_Kd_Aset6');
		$no_id       = $this->session->userdata('tmp_No_ID');
        
        $this->db->where('Tahun', $tahun);
        $this->db->where('Kd_Prov', $kd_prov);
		$this->db->where('Kd_Kab_Kota', $kd_kab_kota);
		$this->db->where('Kd_Bidang', $kd_bidang);
		$this->db->where('Kd_Unit', $kd_unit);
		$this->db->where('Kd_Sub', $kd_sub);
		$this->db->where('Kd_UPB', $kd_upb);
		$this->db->where('Kd_Aset1', $kd_aset1);
		$this->db->where('Kd_Aset2', $kd_aset2);
		$this->db->where('Kd_Aset3', $kd_aset3);
		$this->db->where('Kd_Aset4', $kd_aset4);
		$this->db->where('Kd_Aset5', $kd_aset5);
		$this->db->where('Kd_Aset6', $kd_aset6);
		$this->db->where('No_ID', $no_id);
        $this->db->update('Ta_RKBU',$arr);
        // echo $this->db->last_query(); die;
        return true;
    }

    function laporan_usul_rkbu($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
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

		$query = "SELECT d.Ket_Kegiatan as Ket_Program,b.Nm_Aset5,a.*,c.Nm_Rek_5,e.Nm_Aset5 as Nm_Aset_ekist, e.Jumlah as Jumlah_ekist FROM Ta_RKBU a
				LEFT JOIN Ref_Rek_Aset5 b ON a.Kd_Aset1=b.Kd_Aset1 AND a.Kd_Aset2=b.Kd_Aset2 AND a.Kd_Aset3=b.Kd_Aset3 AND 
				a.Kd_Aset4=b.Kd_Aset4 AND a.Kd_Aset5=b.Kd_Aset5
				LEFT JOIN Ref_Rek_5 c ON a.Kd_Rek_1=c.Kd_Rek_1 AND a.Kd_Rek_2=c.Kd_Rek_2 AND a.Kd_Rek_3=c.Kd_Rek_3 AND a.Kd_Rek_4=c.Kd_Rek_4 AND a.Kd_Rek_5=c.Kd_Rek_5
				INNER JOIN Ta_Kegiatan d ON a.Kd_Bidang=d.Kd_Bidang AND a.Kd_Unit=d.Kd_Unit AND a.Kd_Sub=d.Kd_Sub AND a.Kd_Prog=d.Kd_Prog AND a.Kd_Keg=d.Kd_Keg
				LEFT JOIN view_data_optimalisasi e ON a.Kd_Bidang=e.Kd_Bidang AND a.Kd_Unit=e.Kd_Unit AND a.Kd_Sub=e.Kd_Sub AND a.Kd_UPB=e.Kd_UPB AND a.Kd_Aset1 = e.Kd_Aset1 AND a.Kd_Aset2=e.Kd_Aset2 AND a.Kd_Aset3=e.Kd_Aset3 AND a.Kd_Aset4=e.Kd_Aset4 AND a.Kd_Aset5=e.Kd_Aset5
				WHERE 1=1 $where";

 		// print_r($query); exit();
		return $this->db->query($query);
	}

    function laporan_rkbu($bidang='',$unit='',$sub='',$upb='',$tahunawal,$tahunakhir,$like)
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

		$query = "SELECT b.Nm_Aset6,b.Spesifikasi,b.Satuan,a.*,c.Nm_Rek_5 FROM Ta_RKBU a
				LEFT JOIN Ref_SSH b ON a.Kd_Aset1=b.Kd_Aset1 AND a.Kd_Aset2=b.Kd_Aset2 AND a.Kd_Aset3=b.Kd_Aset3 AND 
				a.Kd_Aset4=b.Kd_Aset4 AND a.Kd_Aset5=b.Kd_Aset5 AND a.Kd_Aset6=b.Kd_Aset6
				LEFT JOIN Ref_Rek_5 c ON a.Kd_Rek_1=c.Kd_Rek_1 AND a.Kd_Rek_2=c.Kd_Rek_2 AND a.Kd_Rek_3=c.Kd_Rek_3 AND a.Kd_Rek_4=c.Kd_Rek_4 AND a.Kd_Rek_5=c.Kd_Rek_5
				WHERE 1=1 $where";

 		// print_r($query); exit();
		return $this->db->query($query);
	}

}

/* End of file Contoh_model.php */
/* Location: ./system/application/models/Contoh_model.php */