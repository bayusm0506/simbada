<?php
/**
 * Contoh_model Class
 */
class Kibbr_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
	/* Inisialisasi nama tabel yang digunakan */
	var $table = 'Ta_KIBBR';
	
	
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

(SELECT TOP 1 Status FROM Ta_KIBBHapus h
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
no_urut		FROM  Ta_KIB_B a INNER JOIN Ref_Rek_Aset5 ON 
a.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND a.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
AND a.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND a.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
AND a.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 LEFT JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang AND a.Kd_Unit=Ref_UPB.Kd_Unit AND
a.Kd_Sub=Ref_UPB.Kd_Sub AND a.Kd_UPB=Ref_UPB.Kd_UPB
   WHERE $where $like AND
a.Kd_Aset1 = '2') AS a WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC";

		// print_r($sql); exit();
 		return $this->db->query($sql);
 	}
	
	/* count kib b total page */
	function count_kib($where, $like) {
		$sql = "SELECT COUNT(*) as Jumlah, SUM(Harga) as Total FROM Ta_KIB_B a INNER JOIN Ref_Rek_Aset5 ON a.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND 
				a.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND a.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
				AND a.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND 
				a.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 WHERE 1=1 ";
		$sql .= "AND $where ";

		$sql .= "$like ";

		$sql .= "AND a.Kd_Aset1 = '2'";

		// print_r($sql); exit();

		return  $this->db->query($sql)->row();

    }
		
	
}

/* End of file Contoh_model.php */
/* Location: ./system/application/models/Contoh_model.php */