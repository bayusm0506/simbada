<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Penghapusan_model extends CI_Model{
	
	var $table = 'Ta_Penghapusan';
	

	function getSK_Penghapusan($limit, $offset, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT  * FROM  (
			SELECT    *, ROW_NUMBER() OVER (ORDER BY a.Tahun DESC) AS 
			no_urut	FROM  Ta_Penghapusan a) AS a";

		$ret['total'] = $this->db->query($sql)->num_rows();
        
        // $sql .=" WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC ";
        
        $ret['data']  = $this->db->query($sql)->result();

		return $ret;
 	}

 	function getRincian_KIBA($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT  * FROM  (
		SELECT     n.Nm_Aset5,Ref_UPB.Nm_UPB,a.*,b.Harga,b.Tgl_Perolehan,b.Alamat,b.Keterangan,
		ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS 
		no_urut		FROM  Ta_KIBAHapus a LEFT JOIN Ta_KIB_A b ON a.Kd_Prov = b.Kd_Prov
							AND a.Kd_Kab_Kota = b.Kd_Kab_Kota
							AND a.Kd_Bidang = b.Kd_Bidang
							AND a.Kd_Unit = b.Kd_Unit
							AND a.Kd_Sub = b.Kd_Sub
							AND a.Kd_UPB = b.Kd_UPB
							AND a.Kd_Aset1 = b.Kd_Aset1
							AND a.Kd_Aset2 = b.Kd_Aset2
							AND a.Kd_Aset3 = b.Kd_Aset3
							AND a.Kd_Aset4 = b.Kd_Aset4
							AND a.Kd_Aset5 = b.Kd_Aset5
							AND a.No_Register = b.No_Register
		 INNER JOIN Ref_Rek_Aset5 n ON 
		a.Kd_Aset1=n.Kd_Aset1 AND a.Kd_Aset2 = n.Kd_Aset2 
		AND a.Kd_Aset3 = n.Kd_Aset3 AND a.Kd_Aset4 = n.Kd_Aset4 
		AND a.Kd_Aset5 = n.Kd_Aset5
		LEFT JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang AND a.Kd_Unit=Ref_UPB.Kd_Unit
		AND	a.Kd_Sub=Ref_UPB.Kd_Sub AND a.Kd_UPB=Ref_UPB.Kd_UPB
		WHERE 1=1 $where $like) AS a";

		// print_r($sql); exit();

		$ret['total'] = $this->db->query($sql)->num_rows();
        
        $sql .=" WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC ";
        
        $ret['data']  = $this->db->query($sql)->result();

		return $ret;
 	}

 	function getUsulan_KIBA($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT  * FROM  (
			SELECT     n.Nm_Aset5,Ref_UPB.Nm_UPB,a.*,b.Harga,b.Tgl_Perolehan,b.Alamat,b.Keterangan,
			ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS 
			no_urut		FROM  Ta_KIBAHapus a LEFT JOIN Ta_KIB_A b ON a.Kd_Prov = b.Kd_Prov
							AND a.Kd_Kab_Kota = b.Kd_Kab_Kota
							AND a.Kd_Bidang = b.Kd_Bidang
							AND a.Kd_Unit = b.Kd_Unit
							AND a.Kd_Sub = b.Kd_Sub
							AND a.Kd_UPB = b.Kd_UPB
							AND a.Kd_Aset1 = b.Kd_Aset1
							AND a.Kd_Aset2 = b.Kd_Aset2
							AND a.Kd_Aset3 = b.Kd_Aset3
							AND a.Kd_Aset4 = b.Kd_Aset4
							AND a.Kd_Aset5 = b.Kd_Aset5
							AND a.No_Register = b.No_Register
		 INNER JOIN Ref_Rek_Aset5 n ON 
		a.Kd_Aset1=n.Kd_Aset1 AND a.Kd_Aset2 = n.Kd_Aset2 
		AND a.Kd_Aset3 = n.Kd_Aset3 AND a.Kd_Aset4 = n.Kd_Aset4 
		AND a.Kd_Aset5 = n.Kd_Aset5
		LEFT JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang AND a.Kd_Unit=Ref_UPB.Kd_Unit
		AND	a.Kd_Sub=Ref_UPB.Kd_Sub AND a.Kd_UPB=Ref_UPB.Kd_UPB
		WHERE 1=1 $where $like) AS a";

		// print_r($sql); exit();

		$ret['total'] = $this->db->query($sql)->num_rows();
        
        $sql .=" WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC ";
        
        $ret['data']  = $this->db->query($sql)->result();

		return $ret;
 	}


 	function getRincian_KIBB($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT  * FROM  (
		SELECT     n.Nm_Aset5,Ref_UPB.Nm_UPB,a.*,b.Harga,b.Tgl_Perolehan,b.Merk,b.Type,b.Kondisi,b.Keterangan,
		ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS 
		no_urut		FROM  Ta_KIBBHapus a LEFT JOIN Ta_KIB_B b ON a.Kd_Prov = b.Kd_Prov
							AND a.Kd_Kab_Kota = b.Kd_Kab_Kota
							AND a.Kd_Bidang = b.Kd_Bidang
							AND a.Kd_Unit = b.Kd_Unit
							AND a.Kd_Sub = b.Kd_Sub
							AND a.Kd_UPB = b.Kd_UPB
							AND a.Kd_Aset1 = b.Kd_Aset1
							AND a.Kd_Aset2 = b.Kd_Aset2
							AND a.Kd_Aset3 = b.Kd_Aset3
							AND a.Kd_Aset4 = b.Kd_Aset4
							AND a.Kd_Aset5 = b.Kd_Aset5
							AND a.No_Register = b.No_Register
		 INNER JOIN Ref_Rek_Aset5 n ON 
		a.Kd_Aset1=n.Kd_Aset1 AND a.Kd_Aset2 = n.Kd_Aset2 
		AND a.Kd_Aset3 = n.Kd_Aset3 AND a.Kd_Aset4 = n.Kd_Aset4 
		AND a.Kd_Aset5 = n.Kd_Aset5
		LEFT JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang AND a.Kd_Unit=Ref_UPB.Kd_Unit
		AND	a.Kd_Sub=Ref_UPB.Kd_Sub AND a.Kd_UPB=Ref_UPB.Kd_UPB
		WHERE 1=1 $where $like) AS a";

		// print_r($sql); exit();

		$ret['total'] = $this->db->query($sql)->num_rows();
        
        $sql .=" WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC ";
        
        $ret['data']  = $this->db->query($sql)->result();

		return $ret;
 	}

 	function getUsulan_KIBB($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT  * FROM  (
		SELECT     n.Nm_Aset5,Ref_UPB.Nm_UPB,a.*,b.Harga,b.Tgl_Perolehan,b.Merk,b.Type,b.Kondisi,b.Keterangan,
		ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS 
		no_urut		FROM  Ta_KIBBHapus a LEFT JOIN Ta_KIB_B b ON a.Kd_Prov = b.Kd_Prov
							AND a.Kd_Kab_Kota = b.Kd_Kab_Kota
							AND a.Kd_Bidang = b.Kd_Bidang
							AND a.Kd_Unit = b.Kd_Unit
							AND a.Kd_Sub = b.Kd_Sub
							AND a.Kd_UPB = b.Kd_UPB
							AND a.Kd_Aset1 = b.Kd_Aset1
							AND a.Kd_Aset2 = b.Kd_Aset2
							AND a.Kd_Aset3 = b.Kd_Aset3
							AND a.Kd_Aset4 = b.Kd_Aset4
							AND a.Kd_Aset5 = b.Kd_Aset5
							AND a.No_Register = b.No_Register
		 INNER JOIN Ref_Rek_Aset5 n ON 
		a.Kd_Aset1=n.Kd_Aset1 AND a.Kd_Aset2 = n.Kd_Aset2 
		AND a.Kd_Aset3 = n.Kd_Aset3 AND a.Kd_Aset4 = n.Kd_Aset4 
		AND a.Kd_Aset5 = n.Kd_Aset5
		LEFT JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang AND a.Kd_Unit=Ref_UPB.Kd_Unit
		AND	a.Kd_Sub=Ref_UPB.Kd_Sub AND a.Kd_UPB=Ref_UPB.Kd_UPB
		WHERE 1=1 $where $like) AS a";

		// print_r($sql); exit();

		$ret['total'] = $this->db->query($sql)->num_rows();
        
        $sql .=" WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC ";
        
        $ret['data']  = $this->db->query($sql)->result();

		return $ret;
 	}

 	function getRincian_KIBC($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT  * FROM  (
		SELECT     n.Nm_Aset5,Ref_UPB.Nm_UPB,a.*,b.Harga,b.Tgl_Perolehan,b.Beton_Tidak,b.Luas_Lantai,b.Lokasi,b.Keterangan,
		ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS 
		no_urut		FROM  Ta_KIBCHapus a LEFT JOIN Ta_KIB_C b ON a.Kd_Prov = b.Kd_Prov
							AND a.Kd_Kab_Kota = b.Kd_Kab_Kota
							AND a.Kd_Bidang = b.Kd_Bidang
							AND a.Kd_Unit = b.Kd_Unit
							AND a.Kd_Sub = b.Kd_Sub
							AND a.Kd_UPB = b.Kd_UPB
							AND a.Kd_Aset1 = b.Kd_Aset1
							AND a.Kd_Aset2 = b.Kd_Aset2
							AND a.Kd_Aset3 = b.Kd_Aset3
							AND a.Kd_Aset4 = b.Kd_Aset4
							AND a.Kd_Aset5 = b.Kd_Aset5
							AND a.No_Register = b.No_Register
		 INNER JOIN Ref_Rek_Aset5 n ON 
		a.Kd_Aset1=n.Kd_Aset1 AND a.Kd_Aset2 = n.Kd_Aset2 
		AND a.Kd_Aset3 = n.Kd_Aset3 AND a.Kd_Aset4 = n.Kd_Aset4 
		AND a.Kd_Aset5 = n.Kd_Aset5
		LEFT JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang AND a.Kd_Unit=Ref_UPB.Kd_Unit
		AND	a.Kd_Sub=Ref_UPB.Kd_Sub AND a.Kd_UPB=Ref_UPB.Kd_UPB
		WHERE 1=1 $where $like) AS a";

		// print_r($sql); exit();

		$ret['total'] = $this->db->query($sql)->num_rows();
        
        $sql .=" WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC ";
        
        $ret['data']  = $this->db->query($sql)->result();

		return $ret;
 	}

 	function getUsulan_KIBC($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT  * FROM  (
			SELECT     n.Nm_Aset5,Ref_UPB.Nm_UPB,a.*,b.Harga,b.Tgl_Perolehan,b.Beton_Tidak,b.Luas_Lantai,b.Lokasi,b.Keterangan,
			ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS 
			no_urut		FROM  Ta_KIBCHapus a LEFT JOIN Ta_KIB_C b ON a.Kd_Prov = b.Kd_Prov
							AND a.Kd_Kab_Kota = b.Kd_Kab_Kota
							AND a.Kd_Bidang = b.Kd_Bidang
							AND a.Kd_Unit = b.Kd_Unit
							AND a.Kd_Sub = b.Kd_Sub
							AND a.Kd_UPB = b.Kd_UPB
							AND a.Kd_Aset1 = b.Kd_Aset1
							AND a.Kd_Aset2 = b.Kd_Aset2
							AND a.Kd_Aset3 = b.Kd_Aset3
							AND a.Kd_Aset4 = b.Kd_Aset4
							AND a.Kd_Aset5 = b.Kd_Aset5
							AND a.No_Register = b.No_Register
		 INNER JOIN Ref_Rek_Aset5 n ON 
		a.Kd_Aset1=n.Kd_Aset1 AND a.Kd_Aset2 = n.Kd_Aset2 
		AND a.Kd_Aset3 = n.Kd_Aset3 AND a.Kd_Aset4 = n.Kd_Aset4 
		AND a.Kd_Aset5 = n.Kd_Aset5
		LEFT JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang AND a.Kd_Unit=Ref_UPB.Kd_Unit
		AND	a.Kd_Sub=Ref_UPB.Kd_Sub AND a.Kd_UPB=Ref_UPB.Kd_UPB
		WHERE 1=1 $where $like) AS a";

		// print_r($sql); exit();

		$ret['total'] = $this->db->query($sql)->num_rows();
        
        $sql .=" WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC ";
        
        $ret['data']  = $this->db->query($sql)->result();

		return $ret;
 	}

 	function getRincian_KIBD($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT  * FROM  (
		SELECT     n.Nm_Aset5,Ref_UPB.Nm_UPB,a.*,b.Harga,b.Tgl_Perolehan,b.Konstruksi,b.Lokasi,b.Keterangan,
		ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS 
		no_urut		FROM  Ta_KIBDHapus a LEFT JOIN Ta_KIB_D b ON a.Kd_Prov = b.Kd_Prov
							AND a.Kd_Kab_Kota = b.Kd_Kab_Kota
							AND a.Kd_Bidang = b.Kd_Bidang
							AND a.Kd_Unit = b.Kd_Unit
							AND a.Kd_Sub = b.Kd_Sub
							AND a.Kd_UPB = b.Kd_UPB
							AND a.Kd_Aset1 = b.Kd_Aset1
							AND a.Kd_Aset2 = b.Kd_Aset2
							AND a.Kd_Aset3 = b.Kd_Aset3
							AND a.Kd_Aset4 = b.Kd_Aset4
							AND a.Kd_Aset5 = b.Kd_Aset5
							AND a.No_Register = b.No_Register
		 INNER JOIN Ref_Rek_Aset5 n ON 
		a.Kd_Aset1=n.Kd_Aset1 AND a.Kd_Aset2 = n.Kd_Aset2 
		AND a.Kd_Aset3 = n.Kd_Aset3 AND a.Kd_Aset4 = n.Kd_Aset4 
		AND a.Kd_Aset5 = n.Kd_Aset5
		LEFT JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang AND a.Kd_Unit=Ref_UPB.Kd_Unit
		AND	a.Kd_Sub=Ref_UPB.Kd_Sub AND a.Kd_UPB=Ref_UPB.Kd_UPB
		WHERE 1=1 $where $like) AS a";

		// print_r($sql); exit();

		$ret['total'] = $this->db->query($sql)->num_rows();
        
        $sql .=" WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC ";
        
        $ret['data']  = $this->db->query($sql)->result();

		return $ret;
 	}

 	function getUsulan_KIBD($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT  * FROM  (
			SELECT     n.Nm_Aset5,Ref_UPB.Nm_UPB,a.*,b.Harga,b.Tgl_Perolehan,b.Konstruksi,b.Lokasi,b.Keterangan,
			ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS 
			no_urut		FROM  Ta_KIBDHapus a LEFT JOIN Ta_KIB_D b ON a.Kd_Prov = b.Kd_Prov
							AND a.Kd_Kab_Kota = b.Kd_Kab_Kota
							AND a.Kd_Bidang = b.Kd_Bidang
							AND a.Kd_Unit = b.Kd_Unit
							AND a.Kd_Sub = b.Kd_Sub
							AND a.Kd_UPB = b.Kd_UPB
							AND a.Kd_Aset1 = b.Kd_Aset1
							AND a.Kd_Aset2 = b.Kd_Aset2
							AND a.Kd_Aset3 = b.Kd_Aset3
							AND a.Kd_Aset4 = b.Kd_Aset4
							AND a.Kd_Aset5 = b.Kd_Aset5
							AND a.No_Register = b.No_Register
		 INNER JOIN Ref_Rek_Aset5 n ON 
		a.Kd_Aset1=n.Kd_Aset1 AND a.Kd_Aset2 = n.Kd_Aset2 
		AND a.Kd_Aset3 = n.Kd_Aset3 AND a.Kd_Aset4 = n.Kd_Aset4 
		AND a.Kd_Aset5 = n.Kd_Aset5
		LEFT JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang AND a.Kd_Unit=Ref_UPB.Kd_Unit
		AND	a.Kd_Sub=Ref_UPB.Kd_Sub AND a.Kd_UPB=Ref_UPB.Kd_UPB
		WHERE 1=1 $where $like) AS a";

		// print_r($sql); exit();

		$ret['total'] = $this->db->query($sql)->num_rows();
        
        $sql .=" WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC ";
        
        $ret['data']  = $this->db->query($sql)->result();

		return $ret;
 	}

 	function getRincian_KIBE($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT  * FROM  (
		SELECT     n.Nm_Aset5,Ref_UPB.Nm_UPB,a.*,b.Harga,b.Tgl_Perolehan,b.Judul,b.Pencipta,b.Bahan,b.Keterangan,
		ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS 
		no_urut		FROM  Ta_KIBEHapus a LEFT JOIN Ta_KIB_E b ON a.Kd_Prov = b.Kd_Prov
							AND a.Kd_Kab_Kota = b.Kd_Kab_Kota
							AND a.Kd_Bidang = b.Kd_Bidang
							AND a.Kd_Unit = b.Kd_Unit
							AND a.Kd_Sub = b.Kd_Sub
							AND a.Kd_UPB = b.Kd_UPB
							AND a.Kd_Aset1 = b.Kd_Aset1
							AND a.Kd_Aset2 = b.Kd_Aset2
							AND a.Kd_Aset3 = b.Kd_Aset3
							AND a.Kd_Aset4 = b.Kd_Aset4
							AND a.Kd_Aset5 = b.Kd_Aset5
							AND a.No_Register = b.No_Register
		 INNER JOIN Ref_Rek_Aset5 n ON 
		a.Kd_Aset1=n.Kd_Aset1 AND a.Kd_Aset2 = n.Kd_Aset2 
		AND a.Kd_Aset3 = n.Kd_Aset3 AND a.Kd_Aset4 = n.Kd_Aset4 
		AND a.Kd_Aset5 = n.Kd_Aset5
		LEFT JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang AND a.Kd_Unit=Ref_UPB.Kd_Unit
		AND	a.Kd_Sub=Ref_UPB.Kd_Sub AND a.Kd_UPB=Ref_UPB.Kd_UPB
		WHERE 1=1 $where $like) AS a";

		// print_r($sql); exit();

		$ret['total'] = $this->db->query($sql)->num_rows();
        
        $sql .=" WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC ";
        
        $ret['data']  = $this->db->query($sql)->result();

		return $ret;
 	}

 	function getUsulan_KIBE($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT  * FROM  (
			SELECT     n.Nm_Aset5,Ref_UPB.Nm_UPB,a.*,b.Harga,b.Tgl_Perolehan,b.Judul,b.Pencipta,b.Bahan,b.Keterangan,
			ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS 
			no_urut		FROM  Ta_KIBEHapus a LEFT JOIN Ta_KIB_E b ON a.Kd_Prov = b.Kd_Prov
							AND a.Kd_Kab_Kota = b.Kd_Kab_Kota
							AND a.Kd_Bidang = b.Kd_Bidang
							AND a.Kd_Unit = b.Kd_Unit
							AND a.Kd_Sub = b.Kd_Sub
							AND a.Kd_UPB = b.Kd_UPB
							AND a.Kd_Aset1 = b.Kd_Aset1
							AND a.Kd_Aset2 = b.Kd_Aset2
							AND a.Kd_Aset3 = b.Kd_Aset3
							AND a.Kd_Aset4 = b.Kd_Aset4
							AND a.Kd_Aset5 = b.Kd_Aset5
							AND a.No_Register = b.No_Register
		 INNER JOIN Ref_Rek_Aset5 n ON 
		a.Kd_Aset1=n.Kd_Aset1 AND a.Kd_Aset2 = n.Kd_Aset2 
		AND a.Kd_Aset3 = n.Kd_Aset3 AND a.Kd_Aset4 = n.Kd_Aset4 
		AND a.Kd_Aset5 = n.Kd_Aset5
		LEFT JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang AND a.Kd_Unit=Ref_UPB.Kd_Unit
		AND	a.Kd_Sub=Ref_UPB.Kd_Sub AND a.Kd_UPB=Ref_UPB.Kd_UPB
		WHERE 1=1 $where $like) AS a";

		// print_r($sql); exit();

		$ret['total'] = $this->db->query($sql)->num_rows();
        
        $sql .=" WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC ";
        
        $ret['data']  = $this->db->query($sql)->result();

		return $ret;
 	}

 	function getRincian_KIBL($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT  * FROM  (
		SELECT     n.Nm_Aset5,Ref_UPB.Nm_UPB,a.*,b.Harga,b.Tgl_Perolehan,b.Judul,b.Pencipta,b.Bahan,b.Keterangan,
		ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS 
		no_urut		FROM  Ta_KILHapus a LEFT JOIN Ta_Lainnya b ON a.Kd_Prov = b.Kd_Prov
							AND a.Kd_Kab_Kota = b.Kd_Kab_Kota
							AND a.Kd_Bidang = b.Kd_Bidang
							AND a.Kd_Unit = b.Kd_Unit
							AND a.Kd_Sub = b.Kd_Sub
							AND a.Kd_UPB = b.Kd_UPB
							AND a.Kd_Aset1 = b.Kd_Aset1
							AND a.Kd_Aset2 = b.Kd_Aset2
							AND a.Kd_Aset3 = b.Kd_Aset3
							AND a.Kd_Aset4 = b.Kd_Aset4
							AND a.Kd_Aset5 = b.Kd_Aset5
							AND a.No_Register = b.No_Register
		 INNER JOIN Ref_Rek_Aset5 n ON 
		a.Kd_Aset1=n.Kd_Aset1 AND a.Kd_Aset2 = n.Kd_Aset2 
		AND a.Kd_Aset3 = n.Kd_Aset3 AND a.Kd_Aset4 = n.Kd_Aset4 
		AND a.Kd_Aset5 = n.Kd_Aset5
		LEFT JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang AND a.Kd_Unit=Ref_UPB.Kd_Unit
		AND	a.Kd_Sub=Ref_UPB.Kd_Sub AND a.Kd_UPB=Ref_UPB.Kd_UPB
		WHERE 1=1 $where $like) AS a";

		// print_r($sql); exit();

		$ret['total'] = $this->db->query($sql)->num_rows();
        
        $sql .=" WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC ";
        
        $ret['data']  = $this->db->query($sql)->result();

		return $ret;
 	}

 	function getUsulan_KIBL($limit, $offset, $where, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit -1);
        }

        $sql ="SELECT  * FROM  (
			SELECT     n.Nm_Aset5,Ref_UPB.Nm_UPB,a.*,b.Harga,b.Tgl_Perolehan,b.Judul,b.Pencipta,b.Bahan,b.Keterangan,
			ROW_NUMBER() OVER (ORDER BY a.Log_entry DESC) AS 
			no_urut		FROM  Ta_KILHapus a LEFT JOIN Ta_Lainnya b ON a.Kd_Prov = b.Kd_Prov
							AND a.Kd_Kab_Kota = b.Kd_Kab_Kota
							AND a.Kd_Bidang = b.Kd_Bidang
							AND a.Kd_Unit = b.Kd_Unit
							AND a.Kd_Sub = b.Kd_Sub
							AND a.Kd_UPB = b.Kd_UPB
							AND a.Kd_Aset1 = b.Kd_Aset1
							AND a.Kd_Aset2 = b.Kd_Aset2
							AND a.Kd_Aset3 = b.Kd_Aset3
							AND a.Kd_Aset4 = b.Kd_Aset4
							AND a.Kd_Aset5 = b.Kd_Aset5
							AND a.No_Register = b.No_Register
		 INNER JOIN Ref_Rek_Aset5 n ON 
		a.Kd_Aset1=n.Kd_Aset1 AND a.Kd_Aset2 = n.Kd_Aset2 
		AND a.Kd_Aset3 = n.Kd_Aset3 AND a.Kd_Aset4 = n.Kd_Aset4 
		AND a.Kd_Aset5 = n.Kd_Aset5
		LEFT JOIN Ref_UPB ON a.Kd_Bidang=Ref_UPB.Kd_Bidang AND a.Kd_Unit=Ref_UPB.Kd_Unit
		AND	a.Kd_Sub=Ref_UPB.Kd_Sub AND a.Kd_UPB=Ref_UPB.Kd_UPB
		WHERE 1=1 $where $like) AS a";

		// print_r($sql); exit();

		$ret['total'] = $this->db->query($sql)->num_rows();
        
        $sql .=" WHERE no_urut BETWEEN $first AND $last  ORDER BY no_urut ASC ";
        
        $ret['data']  = $this->db->query($sql)->result();

		return $ret;
 	}

 	function batal_hapus_a($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id)
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
		$this->db->where('Kd_Id', $kd_id);

    	$sql = $this->db->update('Ta_KIBAHapus',array('No_SK' => null,'Tgl_SK' => null,'Status' => 1));
		if($sql){
			return TRUE;
		}
	}

	function proses_usul_a($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id,$data)
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
		$this->db->where('Kd_Id', $kd_id);

    	$sql = $this->db->update('Ta_KIBAHapus',$data);
		if($sql){
			return TRUE;
		}
	}

 	function batal_hapus_b($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id)
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
		$this->db->where('Kd_Id', $kd_id);

    	$sql = $this->db->update('Ta_KIBBHapus',array('No_SK' => null,'Tgl_SK' => null,'Status' => 1));
		if($sql){
			return TRUE;
		}
	}

	function proses_usul_b($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id,$data)
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
		$this->db->where('Kd_Id', $kd_id);

    	$sql = $this->db->update('Ta_KIBBHapus',$data);
		if($sql){
			return TRUE;
		}
	}

	function batal_hapus_c($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id)
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
		$this->db->where('Kd_Id', $kd_id);

    	$sql = $this->db->update('Ta_KIBCHapus',array('No_SK' => null,'Tgl_SK' => null,'Status' => 1));
		if($sql){
			return TRUE;
		}
	}

	function proses_usul_c($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id,$data)
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
		$this->db->where('Kd_Id', $kd_id);

    	$sql = $this->db->update('Ta_KIBCHapus',$data);
		if($sql){
			return TRUE;
		}
	}


	function batal_hapus_d($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id)
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
		$this->db->where('Kd_Id', $kd_id);

    	$sql = $this->db->update('Ta_KIBDHapus',array('No_SK' => null,'Tgl_SK' => null,'Status' => 1));
		if($sql){
			return TRUE;
		}
	}

	function proses_usul_d($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id,$data)
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
		$this->db->where('Kd_Id', $kd_id);

    	$sql = $this->db->update('Ta_KIBDHapus',$data);
		if($sql){
			return TRUE;
		}
	}

	function proses_usul_e($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id,$data)
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
		$this->db->where('Kd_Id', $kd_id);

    	$sql = $this->db->update('Ta_KIBEHapus',$data);
		if($sql){
			return TRUE;
		}
	}

	function proses_usul_l($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$kd_id,$data)
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
		$this->db->where('Kd_Id', $kd_id);

    	$sql = $this->db->update('Ta_KILHapus',$data);
		if($sql){
			return TRUE;
		}
	}

	function add($data){
		$sql = $this->db->insert($this->table, $data);
		if($sql)
			return TRUE;
	}

	function get_sk_by_id($id){

		$sql = "SELECT * FROM Ta_Penghapusan WHERE No_SK = '{$id}'";

		return $this->db->query($sql);

	}

	function update($data){
        
		$arr1 = array(
			'No_SK'      => $data['No_SK'],
			'Tgl_SK'     => $data['Tgl_SK'],
			'Keterangan' => $data['Keterangan']
        );

        // print_r($this->session->userdata('id_sk_tmp')); exit();

		$this->db->trans_begin();
			$this->db->where('No_SK', $this->session->userdata('id_sk_tmp') );
			$this->db->update('Ta_Penghapusan', $arr1);

			$this->db->where('No_SK', $this->session->userdata('id_sk_tmp') );
			$this->db->update('Ta_KIBAHapus', array('No_SK'=> $data['No_SK']) );

			$this->db->where('No_SK', $this->session->userdata('id_sk_tmp') );
			$this->db->update('Ta_KIBBHapus', array('No_SK'=> $data['No_SK']) );

			$this->db->where('No_SK', $this->session->userdata('id_sk_tmp') );
			$this->db->update('Ta_KIBCHapus', array('No_SK'=> $data['No_SK']) );

			$this->db->where('No_SK', $this->session->userdata('id_sk_tmp') );
			$this->db->update('Ta_KIBDHapus', array('No_SK'=> $data['No_SK']) );

			$this->db->where('No_SK', $this->session->userdata('id_sk_tmp') );
			$this->db->update('Ta_KIBEHapus', array('No_SK'=> $data['No_SK']) );

		if($this->db->trans_status()==false){
			
			$this->session->set_flashdata('message', 'SK Penghapusan Gagal diupdate!');
			redirect('penghapusan/sk');
		}else{
			$this->db->trans_commit();
			$this->session->set_flashdata('message', 'SK Penghapusan berhasil diupdate!');
			redirect('penghapusan/sk');
		}
	}

	
}