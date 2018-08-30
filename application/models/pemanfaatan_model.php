<?php
/**
 * Contoh_model Class
 */
class Pemanfaatan_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
	/* Inisialisasi nama tabel yang digunakan */
	var $table = 'Tb_Pemanfaatan';
	
	
	function get_page($limit, $offset, $like){
		if($offset == 0  or !isset($offset)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $offset+1;
            $last  = $first + ($limit - 1);
        }

        $kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');

        $where = "AND (Tb_Pemanfaatan.Kd_Bidang = $kb) 
				  AND (Tb_Pemanfaatan.Kd_Unit = $ku) 
				  AND (Tb_Pemanfaatan.Kd_Sub = $ks) 
				  AND (Tb_Pemanfaatan.Kd_UPB = $kupb)";

		$query= "SELECT * FROM  (
				SELECT Tb_Pemanfaatan.*,
				ROW_NUMBER() OVER (ORDER BY Tb_Pemanfaatan.Log_entry DESC) AS urutan
				FROM  Tb_Pemanfaatan WHERE 1=1 $where $like
				) as Tb_Pemanfaatan WHERE urutan BETWEEN $first AND $last ORDER BY Log_entry DESC";				
 		return $this->db->query($query);
 	}
	
	/* count kib b total page */
	function count_kib($like) {
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');

        $where = "AND (Tb_Pemanfaatan.Kd_Bidang = $kb) 
				  AND (Tb_Pemanfaatan.Kd_Unit = $ku) 
				  AND (Tb_Pemanfaatan.Kd_Sub = $ks) 
				  AND (Tb_Pemanfaatan.Kd_UPB = $kupb)";
 		return $this->db->query("SELECT * FROM (SELECT * FROM  Tb_Pemanfaatan WHERE 1=1 $where ) AS Tb_Pemanfaatan");	
    }
	
	
	/**
	 * Tampilkan total harga kib b
	 */
	function total_kib($where, $like)
	{		
		/*$query= "SELECT SUM(Harga*Jumlah) AS Harga FROM  Tb_Pemanfaatan  WHERE $where $like";
			
		$sql = $this->db->query($query);
		foreach ($sql->result() as $row)
		{
			$result = $row->Harga;
		}*/
		return 1234567890;
	}


	function save($data){
        
        $arr = array(
			'Jenis_Dokumen'   => $data['Jenis_Dokumen'],
			'Kd_Prov'         => 2,
			'Kd_Kab_Kota'     => 14,
			'Kd_Bidang'       => $this->session->userdata('addKd_Bidang'),
			'Kd_Unit'         => $this->session->userdata('addKd_Unit'),
			'Kd_Sub'          => $this->session->userdata('addKd_Sub'),
			'Kd_UPB'          => $this->session->userdata('addKd_UPB'),
			'Nama_Pihak_1'    => $data['Nama_Pihak_1'],
			'Nip_Pihak_1'     => $data['Nip_Pihak_1'],
			'Jabatan_Pihak_1' => $data['Jabatan_Pihak_1'],
			'Alamat_Pihak_1'  => $data['Alamat_Pihak_1'],
			'Nama_Pihak_2'    => $data['Nama_Pihak_2'],
			'Nip_Pihak_2'     => $data['Nip_Pihak_2'],
			'Jabatan_Pihak_2' => $data['Jabatan_Pihak_2'],
			'Alamat_Pihak_2'  => $data['Alamat_Pihak_2'],
			'No_Dokumen'      => $data['No_Dokumen'],
			'Kode_Dokumen'    => strtoupper($this->session->userdata('username')),
			'Tgl_Dokumen'     => $data['Tgl_Dokumen'],
			'Tgl_Pinjam'      => $data['Tgl_Pinjam'],
			'Tgl_Kembali'     => $data['Tgl_Kembali'],
			'Keterangan'      => $data['Keterangan'],
			'Log_User'        => $this->session->userdata('username'),
			'Log_entry'       => date("Y-m-d H:i:s")
        );
        
        $this->db->trans_begin();
        
        $this->db->insert('Tb_Pemanfaatan',$arr);

        $rinci = array();
        foreach($data['data2'] as $rc){
        
            if($rc['kd_aset1']!='' AND $rc['no_register']!=''){

            	$dr = $this->db->select('*')
                              ->where('Kd_Prov',2)
                              ->where('Kd_Kab_Kota',14)
                              ->where('Kd_Bidang',$this->session->userdata('addKd_Bidang'))
                              ->where('Kd_Unit',$this->session->userdata('addKd_Unit'))
                              ->where('Kd_Sub',$this->session->userdata('addKd_Sub'))
                              ->where('Kd_UPB',$this->session->userdata('addKd_UPB'))
                              ->where('Kd_Aset1',$rc['kd_aset1'])
                              ->where('Kd_Aset2',$rc['kd_aset2'])
                              ->where('Kd_Aset3',$rc['kd_aset3'])
                              ->where('Kd_Aset4',$rc['kd_aset4'])
                              ->where('Kd_Aset5',$rc['kd_aset5'])
                              ->where('No_Register',$rc['no_register'])
                              ->get('Ta_KIB_B')->row_array();
            
                $tmp = array(
						'Jenis_Dokumen'  => $data['Jenis_Dokumen'],
						'No_Dokumen'     => $data['No_Dokumen'],
						'Kd_Prov'        => $dr['Kd_Prov'],
						'Kd_Kab_Kota'    => $dr['Kd_Kab_Kota'],
						'Kd_Bidang'      => $dr['Kd_Bidang'],
						'Kd_Unit'        => $dr['Kd_Unit'],
						'Kd_Sub'         => $dr['Kd_Sub'],
						'Kd_UPB'         => $dr['Kd_UPB'],
						'Kd_Aset1'       => $dr['Kd_Aset1'],
						'Kd_Aset2'       => $dr['Kd_Aset2'],
						'Kd_Aset3'       => $dr['Kd_Aset3'],
						'Kd_Aset4'       => $dr['Kd_Aset4'],
						'Kd_Aset5'       => $dr['Kd_Aset5'],
						'No_Register'    => $dr['No_Register'],
						'Kd_Ruang'       => $dr['Kd_Ruang'],
						'Kd_Pemilik'     => $dr['Kd_Pemilik'],
						'Merk'           => $dr['Merk'],
						'Type'           => $dr['Type'],
						'CC'             => $dr['CC'],
						'Bahan'          => $dr['Bahan'],
						'Tgl_Perolehan'  => $dr['Tgl_Perolehan'],
						'Nomor_Pabrik'   => $dr['Nomor_Pabrik'],
						'Nomor_Rangka'   => $dr['Nomor_Rangka'],
						'Nomor_Mesin'    => $dr['Nomor_Mesin'],
						'Nomor_Polisi'   => $dr['Nomor_Polisi'],
						'Nomor_BPKB'     => $dr['Nomor_BPKB'],
						'Asal_usul'      => $dr['Asal_usul'],
						'Kondisi'        => $dr['Kondisi'],
						'Harga'          => $dr['Harga'],
						'Masa_Manfaat'   => $dr['Masa_Manfaat'],
						'Nilai_Sisa'     => $dr['Nilai_Sisa'],
						'Keterangan'     => $dr['Keterangan'],
						'Tahun'          => $dr['Tahun'],
						'No_SP2D'        => $dr['No_SP2D'],
						'No_ID'          => $dr['No_ID'],
						'Tgl_Pembukuan'  => $dr['Tgl_Pembukuan'],
						'Kd_Kecamatan'   => $dr['Kd_Kecamatan'],
						'Kd_Desa'        => $dr['Kd_Desa'],
						'Invent'         => $dr['Invent'],
						'No_SKGuna'      => $dr['No_SKGuna'],
						'Kd_Penyusutan'  => $dr['Kd_Penyusutan'],
						'Kd_Data'        => $dr['Kd_Data'],
						'Kd_KA'          => $dr['Kd_KA'],
						'Log_User'       => $dr['Log_User'],
						'Log_entry'      => $dr['Log_entry'],
						'Kd_Masalah'     => $dr['Kd_Masalah'],
						'Ket_Masalah'    => $dr['Ket_Masalah'],
						'aktif'          => $dr['aktif'],
						'ekstrakomtabel' => $dr['ekstrakomtabel'],
						'Harga_0'        => $dr['Harga_0'],
						'Pemakai'        => $dr['Pemakai'],
						'Jumlah_Roda'    => $dr['Jumlah_Roda']
	                );

               $this->db->insert('Tb_Rincian',$tmp);
            }
        }
        
        if($this->db->trans_status()===false){
            
            $this->db->trans_rollback();
            return false;    
            
        }else{
            
            $this->db->trans_complete();
            return true;
        }      
    
    }


    function update($data){
        
        $arr = array(
			'Jenis_Dokumen'   => $data['Jenis_Dokumen'],
			'Kd_Prov'         => 2,
			'Kd_Kab_Kota'     => 14,
			'Kd_Bidang'       => $this->session->userdata('addKd_Bidang'),
			'Kd_Unit'         => $this->session->userdata('addKd_Unit'),
			'Kd_Sub'          => $this->session->userdata('addKd_Sub'),
			'Kd_UPB'          => $this->session->userdata('addKd_UPB'),
			'Nama_Pihak_1'    => $data['Nama_Pihak_1'],
			'Nip_Pihak_1'     => $data['Nip_Pihak_1'],
			'Jabatan_Pihak_1' => $data['Jabatan_Pihak_1'],
			'Alamat_Pihak_1'  => $data['Alamat_Pihak_1'],
			'Nama_Pihak_2'    => $data['Nama_Pihak_2'],
			'Nip_Pihak_2'     => $data['Nip_Pihak_2'],
			'Jabatan_Pihak_2' => $data['Jabatan_Pihak_2'],
			'Alamat_Pihak_2'  => $data['Alamat_Pihak_2'],
			'No_Dokumen'      => $data['No_Dokumen'],
			'Kode_Dokumen'    => strtoupper($this->session->userdata('username')),
			'Tgl_Dokumen'     => $data['Tgl_Dokumen'],
			'Tgl_Pinjam'      => $data['Tgl_Pinjam'],
			'Tgl_Kembali'     => $data['Tgl_Kembali'],
			'Keterangan'      => $data['Keterangan'],
			'Log_User'        => $this->session->userdata('username'),
			'Log_entry'       => date("Y-m-d H:i:s")
        );
        
        $this->db->trans_begin();
        $this->db->update('Tb_Pemanfaatan',$arr,array('Jenis_Dokumen'=>$this->session->userdata('Jenis_Dokumen_tmp'),'No_Dokumen'=>$this->session->userdata('No_Dokumen_tmp')));

        $tmp = array();
        foreach($data['data2'] as $rc){
            if($rc['kd_aset1']!='' AND $rc['no_register']!=''){
		        $dr = $this->db->select('*')
		                      ->where('Kd_Prov',$this->session->userdata('Kd_Prov_tmp'))
		                      ->where('Kd_Kab_Kota',$this->session->userdata('Kd_Kab_Kota_tmp'))
		                      ->where('Kd_Bidang',$this->session->userdata('Kd_Bidang_tmp'))
		                      ->where('Kd_Unit',$this->session->userdata('Kd_Unit_tmp'))
		                      ->where('Kd_Sub',$this->session->userdata('Kd_Sub_tmp'))
		                      ->where('Kd_UPB',$this->session->userdata('Kd_UPB_tmp'))
		                      ->where('Kd_Aset1',$rc['kd_aset1'])
		                      ->where('Kd_Aset2',$rc['kd_aset2'])
		                      ->where('Kd_Aset3',$rc['kd_aset3'])
		                      ->where('Kd_Aset4',$rc['kd_aset4'])
		                      ->where('Kd_Aset5',$rc['kd_aset5'])
		                      ->where('No_Register',$rc['no_register'])
		                      ->get('Ta_KIB_B')->row_array();

		        $tmp = array(
						'Jenis_Dokumen'  => $data['Jenis_Dokumen'],
						'No_Dokumen'     => $data['No_Dokumen'],
						'Kd_Prov'        => $dr['Kd_Prov'],
						'Kd_Kab_Kota'    => $dr['Kd_Kab_Kota'],
						'Kd_Bidang'      => $dr['Kd_Bidang'],
						'Kd_Unit'        => $dr['Kd_Unit'],
						'Kd_Sub'         => $dr['Kd_Sub'],
						'Kd_UPB'         => $dr['Kd_UPB'],
						'Kd_Aset1'       => $dr['Kd_Aset1'],
						'Kd_Aset2'       => $dr['Kd_Aset2'],
						'Kd_Aset3'       => $dr['Kd_Aset3'],
						'Kd_Aset4'       => $dr['Kd_Aset4'],
						'Kd_Aset5'       => $dr['Kd_Aset5'],
						'No_Register'    => $dr['No_Register'],
						'Kd_Ruang'       => $dr['Kd_Ruang'],
						'Kd_Pemilik'     => $dr['Kd_Pemilik'],
						'Merk'           => $dr['Merk'],
						'Type'           => $dr['Type'],
						'CC'             => $dr['CC'],
						'Bahan'          => $dr['Bahan'],
						'Tgl_Perolehan'  => $dr['Tgl_Perolehan'],
						'Nomor_Pabrik'   => $dr['Nomor_Pabrik'],
						'Nomor_Rangka'   => $dr['Nomor_Rangka'],
						'Nomor_Mesin'    => $dr['Nomor_Mesin'],
						'Nomor_Polisi'   => $dr['Nomor_Polisi'],
						'Nomor_BPKB'     => $dr['Nomor_BPKB'],
						'Asal_usul'      => $dr['Asal_usul'],
						'Kondisi'        => $dr['Kondisi'],
						'Harga'          => $dr['Harga'],
						'Masa_Manfaat'   => $dr['Masa_Manfaat'],
						'Nilai_Sisa'     => $dr['Nilai_Sisa'],
						'Keterangan'     => $dr['Keterangan'],
						'Tahun'          => $dr['Tahun'],
						'No_SP2D'        => $dr['No_SP2D'],
						'No_ID'          => $dr['No_ID'],
						'Tgl_Pembukuan'  => $dr['Tgl_Pembukuan'],
						'Kd_Kecamatan'   => $dr['Kd_Kecamatan'],
						'Kd_Desa'        => $dr['Kd_Desa'],
						'Invent'         => $dr['Invent'],
						'No_SKGuna'      => $dr['No_SKGuna'],
						'Kd_Penyusutan'  => $dr['Kd_Penyusutan'],
						'Kd_Data'        => $dr['Kd_Data'],
						'Kd_KA'          => $dr['Kd_KA'],
						'Log_User'       => $dr['Log_User'],
						'Log_entry'      => $dr['Log_entry'],
						'Kd_Masalah'     => $dr['Kd_Masalah'],
						'Ket_Masalah'    => $dr['Ket_Masalah'],
						'aktif'          => $dr['aktif'],
						'ekstrakomtabel' => $dr['ekstrakomtabel'],
						'Harga_0'        => $dr['Harga_0'],
						'Pemakai'        => $dr['Pemakai'],
						'Jumlah_Roda'    => $dr['Jumlah_Roda']
	                );

				if(empty($rc['no_register_tmp'])){
                 	$this->db->insert('Tb_Rincian',$tmp);
             	}else{
             		$Jenis_Dokumen_tmp  = $this->session->userdata('Jenis_Dokumen_tmp');
             		$No_Dokumen_tmp  = $this->session->userdata('No_Dokumen_tmp');
					$Kd_Prov_tmp     = $this->session->userdata('Kd_Prov_tmp');
					$Kd_Kab_Kota_tmp = $this->session->userdata('Kd_Kab_Kota_tmp');
					$Kd_Bidang_tmp   = $this->session->userdata('Kd_Bidang_tmp');
					$Kd_Unit_tmp     = $this->session->userdata('Kd_Unit_tmp');
					$Kd_Sub_tmp      = $this->session->userdata('Kd_Sub_tmp');
					$Kd_UPB_tmp      = $this->session->userdata('Kd_UPB_tmp');
					$Kd_Aset1_tmp    = $rc['kd_aset1_tmp'];
					$Kd_Aset2_tmp    = $rc['kd_aset2_tmp'];
					$Kd_Aset3_tmp    = $rc['kd_aset3_tmp'];
					$Kd_Aset4_tmp    = $rc['kd_aset4_tmp'];
					$Kd_Aset5_tmp    = $rc['kd_aset5_tmp'];
					$No_Register_tmp = $rc['no_register_tmp'];
                    $this->db->update('Tb_Rincian',$tmp,array('Kd_Prov'=>$Kd_Prov_tmp, 'Kd_Kab_Kota'=>$Kd_Kab_Kota_tmp, 'Kd_Bidang'=>$Kd_Bidang_tmp, 'Kd_Unit'=>$Kd_Unit_tmp, 'Kd_Sub'=>$Kd_Sub_tmp, 'Kd_UPB'=>$Kd_UPB_tmp, 'Kd_Aset1'=>$Kd_Aset1_tmp, 'Kd_Aset2'=>$Kd_Aset2_tmp, 'Kd_Aset3'=>$Kd_Aset3_tmp, 'Kd_Aset4'=>$Kd_Aset4_tmp, 'Kd_Aset5'=>$Kd_Aset5_tmp, 'No_Register'=>$No_Register_tmp,'Jenis_Dokumen'=>$Jenis_Dokumen_tmp, 'No_Dokumen'=>$No_Dokumen_tmp));
                }


			 }
        }
        if($this->db->trans_status()===false){
            
            $this->db->trans_rollback();
            return false;    
            
        }else{
            
            $this->db->trans_complete();
            return true;
        }      
    
    }

    function json_all($keyword){

    	$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');

		$query = "SELECT TOP 10 KIB.*,Ref_Rek_Aset5.Nm_Aset5 FROM
				(
					SELECT Ta_KIB_A.Kd_Prov, Ta_KIB_A.Kd_Kab_Kota, Ta_KIB_A.Kd_Bidang, Ta_KIB_A.Kd_Unit,Ta_KIB_A.Kd_Sub, Ta_KIB_A.Kd_UPB,
					Ta_KIB_A.Kd_Aset1,Ta_KIB_A.Kd_Aset2,Ta_KIB_A.Kd_Aset3,Ta_KIB_A.Kd_Aset4,Ta_KIB_A.Kd_Aset5,Ta_KIB_A.No_Register,DATENAME(yyyy,Ta_KIB_A.Tgl_Perolehan) as Tahun FROM Ta_KIB_A
					UNION
					SELECT Ta_KIB_B.Kd_Prov, Ta_KIB_B.Kd_Kab_Kota, Ta_KIB_B.Kd_Bidang, Ta_KIB_B.Kd_Unit,Ta_KIB_B.Kd_Sub, Ta_KIB_B.Kd_UPB,
					Ta_KIB_B.Kd_Aset1,Ta_KIB_B.Kd_Aset2,Ta_KIB_B.Kd_Aset3,Ta_KIB_B.Kd_Aset4,Ta_KIB_B.Kd_Aset5,Ta_KIB_B.No_Register,DATENAME(yyyy,Ta_KIB_B.Tgl_Perolehan) as Tahun FROM Ta_KIB_B
					UNION
					SELECT Ta_KIB_C.Kd_Prov, Ta_KIB_C.Kd_Kab_Kota, Ta_KIB_C.Kd_Bidang, Ta_KIB_C.Kd_Unit,Ta_KIB_C.Kd_Sub, Ta_KIB_C.Kd_UPB,
					Ta_KIB_C.Kd_Aset1,Ta_KIB_C.Kd_Aset2,Ta_KIB_C.Kd_Aset3,Ta_KIB_C.Kd_Aset4,Ta_KIB_C.Kd_Aset5,Ta_KIB_C.No_Register,DATENAME(yyyy,Ta_KIB_C.Tgl_Perolehan) as Tahun FROM Ta_KIB_C
					UNION
					SELECT Ta_KIB_D.Kd_Prov, Ta_KIB_D.Kd_Kab_Kota, Ta_KIB_D.Kd_Bidang, Ta_KIB_D.Kd_Unit,Ta_KIB_D.Kd_Sub, Ta_KIB_D.Kd_UPB,
					Ta_KIB_D.Kd_Aset1,Ta_KIB_D.Kd_Aset2,Ta_KIB_D.Kd_Aset3,Ta_KIB_D.Kd_Aset4,Ta_KIB_D.Kd_Aset5,Ta_KIB_D.No_Register,DATENAME(yyyy,Ta_KIB_D.Tgl_Perolehan) as Tahun FROM Ta_KIB_D
					UNION
					SELECT Ta_KIB_E.Kd_Prov, Ta_KIB_E.Kd_Kab_Kota, Ta_KIB_E.Kd_Bidang, Ta_KIB_E.Kd_Unit,Ta_KIB_E.Kd_Sub, Ta_KIB_E.Kd_UPB,
					Ta_KIB_E.Kd_Aset1,Ta_KIB_E.Kd_Aset2,Ta_KIB_E.Kd_Aset3,Ta_KIB_E.Kd_Aset4,Ta_KIB_E.Kd_Aset5,Ta_KIB_E.No_Register,DATENAME(yyyy,Ta_KIB_E.Tgl_Perolehan) as Tahun FROM Ta_KIB_E
				) as KIB LEFT JOIN Ref_Rek_Aset5 ON KIB.Kd_Aset1=Ref_Rek_Aset5.Kd_Aset1 AND KIB.Kd_Aset2=Ref_Rek_Aset5.Kd_Aset2 AND KIB.Kd_Aset3=Ref_Rek_Aset5.Kd_Aset3 AND KIB.Kd_Aset4=Ref_Rek_Aset5.Kd_Aset4 AND KIB.Kd_Aset5=Ref_Rek_Aset5.Kd_Aset5 WHERE 1=1";
		$query .= "AND Ref_Rek_Aset5.Nm_Aset5 like '%{$keyword}%'";

		$query .= "AND (KIB.Kd_Bidang = $kb) 
				  AND (KIB.Kd_Unit = $ku) 
				  AND (KIB.Kd_Sub = $ks) 
				  AND (KIB.Kd_UPB = $kupb)";

		$sql = $this->db->query($query);
        
        return $sql->result();
	}
	
	function json_kibb($keyword){
		$this->db->LIMIT(10);
		$this->db->select('Ref_Rek_Aset5.Nm_Aset5,Ta_KIB_B.*')->from('Ta_KIB_B');
		$this->db->join('Ref_Rek_Aset5', 'Ta_KIB_B.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Ta_KIB_B.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Ta_KIB_B.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Ta_KIB_B.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Ta_KIB_B.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
		$this->db->where('Kd_Bidang',$this->session->userdata('addKd_Bidang'));
		$this->db->where('Kd_Unit',$this->session->userdata('addKd_Unit'));
		$this->db->where('Kd_Sub',$this->session->userdata('addKd_Sub'));
		$this->db->where('Kd_UPB',$this->session->userdata('addKd_UPB'));
        $query = $this->db->get();
        
        return $query->result();
	}
	
	function get_data_id($jenis,$no=''){
        $sql = "SELECT * FROM Tb_Pemanfaatan WHERE 1=1";
        if($no){
            $sql .=" AND Jenis_Dokumen = '{$jenis}' AND No_Dokumen = '{$no}'";
        }else{
            redirect('login');
        }
        return $this->db->query($sql)->row_array();
    }

    function get_rincian($kode){			
		
		$query = "SELECT * FROM Tb_Rincian INNER JOIN
				Ref_Rek_Aset5 ON Tb_Rincian.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 
				AND Tb_Rincian.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 
				AND Tb_Rincian.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 
				AND Tb_Rincian.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 
				AND Tb_Rincian.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5 WHERE 1=1";
		$query .= "AND No_Dokumen = '{$kode}'";
		return $this->db->query($query);
	}


    function laporan_ba($kode){			
		
		$query = "SELECT Ref_Rek_Aset5.Nm_Aset5,COUNT(Tb_Rincian.No_register) as jumlah_register,MIN(Tb_Rincian.No_register) as min_register,
				MAX(Tb_Rincian.No_register) as max_register,
				Tb_Rincian.Kd_Prov, Tb_Rincian.Kd_Kab_Kota, Tb_Rincian.Kd_Bidang, Tb_Rincian.Kd_Unit,
				Tb_Rincian.Kd_Sub, Tb_Rincian.Kd_UPB, Tb_Rincian.Kd_Aset1, Tb_Rincian.Kd_Aset2, Tb_Rincian.Kd_Aset3, 
				Tb_Rincian.Kd_Aset4, Tb_Rincian.Kd_Aset5, Tb_Rincian.Merk, Tb_Rincian.Type,
				DATENAME(yyyy,Tb_Rincian.Tgl_Perolehan) as Tahun,Tb_Rincian.Nomor_Pabrik,Tb_Rincian.Nomor_Rangka,Tb_Rincian.Nomor_Mesin,
				Tb_Rincian.Nomor_Polisi,Tb_Rincian.Kondisi,COUNT(*) as Jumlah,
				SUM(Tb_Rincian.Harga) as Harga, Tb_Rincian.Keterangan

				FROM  Tb_Rincian INNER JOIN
				Ref_Rek_Aset5 ON Tb_Rincian.Kd_Aset1 = Ref_Rek_Aset5.Kd_Aset1 AND Tb_Rincian.Kd_Aset2 = Ref_Rek_Aset5.Kd_Aset2 AND Tb_Rincian.Kd_Aset3 = Ref_Rek_Aset5.Kd_Aset3 AND Tb_Rincian.Kd_Aset4 = Ref_Rek_Aset5.Kd_Aset4 AND Tb_Rincian.Kd_Aset5 = Ref_Rek_Aset5.Kd_Aset5

				WHERE No_Dokumen = '{$kode}'

				GROUP BY Tb_Rincian.Kd_Prov, Tb_Rincian.Kd_Kab_Kota, Tb_Rincian.Kd_Bidang, Tb_Rincian.Kd_Unit,
				Tb_Rincian.Kd_Sub, Tb_Rincian.Kd_UPB, Tb_Rincian.Kd_Aset1, Tb_Rincian.Kd_Aset2, Tb_Rincian.Kd_Aset3, 
				Tb_Rincian.Kd_Aset4, Tb_Rincian.Kd_Aset5,Tb_Rincian.Merk, Tb_Rincian.Type,
				Tb_Rincian.Tgl_Perolehan,Tb_Rincian.Nomor_Pabrik,Tb_Rincian.Nomor_Rangka,Tb_Rincian.Nomor_Mesin,Tb_Rincian.Nomor_Polisi,
				Tb_Rincian.Kondisi,Tb_Rincian.Keterangan,Ref_Rek_Aset5.Nm_Aset5
				ORDER BY  Ref_Rek_Aset5.Nm_Aset5, MIN(Tb_Rincian.No_register)";
		return $this->db->query($query);
	}

	/**
	 * Total Data ba
	 */
	/* 14-05-2015*/
	function total_ba($kode){	
		
		$query = "SELECT COUNT(*) as Jumlah,SUM(Harga) as Harga FROM Tb_Rincian WHERE No_Dokumen = '{$kode}'";
		
        return $this->db->query($query);
	}

	function get_last_momor($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$jenis_dokumen)
	{	
	
		$this->db->select_MAX('No_Dokumen');
		$row = $this->db->get_where($this->table,array('Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,'Kd_Sub' => $kd_sub,
		'Kd_UPB' => $kd_upb,'Jenis_Dokumen' => $jenis_dokumen))->row();
 
        $result = $row->No_Dokumen;
        
        return $result+1;
		
	}

	function hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register,$no_dokumen){
		$this->db->delete("Tb_Rincian", array('Kd_Prov' => $kd_prov,'Kd_Kab_Kota' => $kd_kab,'Kd_Bidang' => $kd_bidang,'Kd_Unit' => $kd_unit,
		'Kd_Sub' => $kd_sub,'Kd_UPB' => $kd_upb,'Kd_Aset1' => $kd_aset1,'Kd_Aset2' => $kd_aset2,'Kd_Aset3' => $kd_aset3,
		'Kd_Aset4' => $kd_aset4,'Kd_Aset5' => $kd_aset5,'No_Register' => $no_register,'No_Dokumen' => $no_dokumen));
	}

	function delete($no_dokumen){
		$this->db->trans_begin();
		$this->db->delete("Tb_Pemanfaatan", array('No_Dokumen' => $no_dokumen));
		$this->db->delete("Tb_Rincian", array('No_Dokumen' => $no_dokumen));
		if($this->db->trans_status()===false){
            
            $this->db->trans_rollback();
            return false;    
            
        }else{
            
            $this->db->trans_complete();
            return true;
        }      
	}	
}

/* End of file Contoh_model.php */
/* Location: ./system/application/models/Contoh_model.php */