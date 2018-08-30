<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Privilege_model extends CI_Model{
    
    public function check_privilege($id){
	
	    return $this->db->limit(1)->get_where('akses_user',array('jabatan_id'=>$id))->num_rows();
	
	}
	public function get_role($modul,$id){
	
	    $sql = "SELECT * FROM akses_user WHERE modul = '%s' AND jabatan_id = %d ";
		$q = $this->db->query(sprintf($sql,$modul,$id));
		if($q->num_rows() > 0)
			return $q->row_array();
		else 
			return $q = array('view'=>'','add'=>'','edit'=>'','remove'=>'');
	}

    private function _olah_modul($data){
	
		$module = $this->modul(); 
		$m = array();
		$n = array();
		foreach($data['data'] as $page_id=>$x){
			
			$m[] = $page_id;
				
		}
		
		foreach($module as $mdl){
		  
			if(!in_array($mdl['const'],$m)){
				
				$n[]= $mdl['const'];
			}
			
			if(isset($mdl['anak'])){
		    
		        foreach($mdl['anak'] as $k){
		            
		            if(!in_array($k['const'],$m)){
		               $n[]= $k['const'];
		            }
		        }   
		    }
		
		}
		
		return $n;
	}
	
	
    public function save($data){

        $arr = array();

		foreach($data['data'] as $page_id=> $page_akses){
			
			if(!isset($page_akses['view']))	$page_akses['view']    = 0; 
			if(!isset($page_akses['add']))	$page_akses['add']     = 0;
			if(!isset($page_akses['edit']))	$page_akses['edit']    = 0;
			if(!isset($page_akses['remove']))$page_akses['remove'] = 0; 
			if(!isset($page_akses['cetak']))$page_akses['cetak']   = 0; 
			
			$tmp = array(
					'jabatan_id' => $data['jabatan_id'],
					'modul'      => $page_id,
					'view'       => $page_akses['view'],
					'add'        => $page_akses['add'],
					'edit'       => $page_akses['edit'],
					'remove'     => $page_akses['remove'],
					'cetak'      => $page_akses['cetak'],
			);
			
			$arr[] = $tmp;		
		}

		/*take CONST where the modul is not checked (add,view etc) nya*/
        $n  = $this->_olah_modul($data);
		$vv = array();
		foreach($n as $o){
			$vv[]= array(
						'jabatan_id' => $data['jabatan_id'],
						'modul'      => $o,
						'view'       => 0,
						'add'        => 0,
						'edit'       => 0,
						'remove'     => 0,
						'cetak'      => 0,
					);
		}

		$arr_data = array_merge($arr,$vv);
		$last = $this->db->query("SELECT MAX(id_akses) as last_id FROM akses_user")->row();

		/*remove first if exist*/
		$this->db->trans_begin();

		$this->db->delete('akses_user',array('jabatan_id'=>$data['jabatan_id']));
		
		$nourut=1;
		foreach ($arr_data as $arr){
			$tmp = array(
							'id_akses'   => $last->last_id+$nourut,
							'jabatan_id' => $arr['jabatan_id'],
							'modul'      => $arr['modul'],
							'[add]'      => $arr['add'],
							'[view]'     => $arr['view'],
							'edit'       => $arr['edit'],
							'remove'     => $arr['remove'],
							'cetak'      => $arr['cetak']
	                );
			$this->db->insert('akses_user', $tmp);
			$nourut++;
		}

		if($this->db->trans_status()===false){
            
            $this->db->trans_rollback();
            return false;    
            
        }else{
            
            $this->db->trans_complete();
            return true;
        }   
    }
    


    /*COnfigure modul*/
	public function modul(){
	
		$module= array(
					array(
					
					    'const'=>'PENGATURAN',
					    'induk'=>true,
						'name'=>'Pengaturan',
						'anak'=> array(
						    array(
						        'const'=>'IDENTITAS',
						        'name'=>'Identitas'
						    ),
						    array(
						        'const'=>'JABATAN',
						        'name'=>'Jabatan'
						    ),
						    array(
						        'const'=>'USER',
						        'name'=>'User'
						    ),
						 )
					),

					array(
					
					    'const'=>'PARAMETER',
					    'induk'=>true,
						'name'=>'PARAMETER',
						'anak'=> array(
						    array(
						        'const'=>'DATA_RUANG',
						        'name'=>'Data Ruang'
						    ),
						    array(
						        'const'=>'DATA_UMUM',
						        'name'=>'Data Umum'
						    ),
						    array(
						        'const'=>'KEBIJAKAN',
						        'name'=>'Kebijakan Akuntansi'
						    ),
						 )
					),
					array(
					    'const'=>'DATA',
					    'induk'=>true,
						'name'=>'DATA',
						'anak'=> array(
						    
						    array(
						    
						        'const'=>'PENGADAAN',
						        'name'=>'Pengadaan'
						    ),
						    array(
						    
						        'const'=>'KIB_A',
						        'name'=>'KIB A. Tanah'
						    ),
						    array(
						    
						        'const'=>'RIWAYAT_KIB_A',
						        'name'=>' - Riwayat KIB A. Tanah'
						    ),
						    array(
						    
						        'const'=>'KIB_B',
						        'name'=>'KIB B. Peralatan & Mesin'
						    ),
						    array(
						    
						        'const'=>'RIWAYAT_KIB_B',
						        'name'=>' - Riwayat KIB B. Peralatan & Mesin'
						    ),
						    array(
						    
						        'const'=>'KIB_C',
						        'name'=>'KIB C. Gedung & Bangunan'
						    ),
						    array(
						        'const'=>'RIWAYAT_KIB_C',
						        'name'=>' - Riwayat KIB C. Gedung & Bangunan'
						    ),
						    array(
						    
						        'const'=>'KIB_D',
						        'name'=>'KIB D. Jalan, Irigasi & Jaringan'
						    ),
						    array(
						    
						        'const'=>'RIWAYAT_KIB_D',
						        'name'=>' - Riwayat KIB D. Jalan, Irigasi & Jaringan'
						    ),
						    array(
						        'const'=>'KIB_E',
						        'name'=>'KIB E. Aset Tetap Lainya'
						    ),
						    array(
						        'const'=>'RIWAYAT_KIB_E',
						        'name'=>' - Riwayat KIB E. Aset Tetap Lainya'
						    ),
						    array(
						    
						        'const'=>'KIB_F',
						        'name'=>'KIB F. Konstruksi Dalam Pengerjaan'
						    ),
						    array(
						    
						        'const'=>'RIWAYAT_KIB_F',
						        'name'=>' - Riwayat KIB F. Konstruksi Dalam Pengerjaan'
						    ),
						    array(
						    
						        'const'=>'KIB_L',
						        'name'=>'Aset Lainya'
						    ),
						    array(
						    
						        'const'=>'RIWAYAT_KIB_L',
						        'name'=>'- Riwayat Aset Lainya'
						    ),
						  	array(

						        'const'=>'PEMANFAATAN',
						        'name'=>'Pemanfaatan'
						    ),
						    array(

						        'const'=>'PENGGUNAAN',
						        'name'=>'Penggunaan'
						    ),
						 )
					),
					array(
					    'const'=>'PENGHAPUSAN',
					    'induk'=>true,
						'name'=>'Penghapusan',
						'anak'=> array(
						    
						    array(
						        'const'=>'USUL_PENGHAPUSAN',
						        'name'=>'Usul Penghapusan'
						    ),
						    array(
						        'const'=>'SK_PENGHAPUSAN',
						        'name'=>'SK Penghapusan'
						    ),
						 )
					),
					array(
					    'const'=>'LAPORAN_PENATAUSAHAAN',
					    'induk'=>true,
						'name'=>'LAPORAN PENATAUSAHAAN',
						'anak'=> array(
						    
						    array(
						        'const'=>'KIB',
						        'name'=>'Kartu Inventaris Barang'
						    ),
						    array(
						        'const'=>'KIR',
						        'name'=>'Kartu Inventaris Ruangan'
						    ),
						 )
					),
					array(
					    'const'=>'LAPORAN_INVENTARIS',
					    'induk'=>true,
						'name'=>'LAPORAN INVENTARISASI',
						'anak'=> array(
						    array(
						    
						        'const'=>'MUTASI',
						        'name'=>'Laporan Mutasi'
						    ),
						    array(
						    
						        'const'=>'REKAP_MUTASI',
						        'name'=>'Rekap Mutasi'
						    ),
						    array(
						    
						        'const'=>'L_KONDISI',
						        'name'=>'Laporan Aset Menurut Kondisi'
						    ),
						    array(
						    
						        'const'=>'BI',
						        'name'=>'Buku Inventaris'
						    ),
						    array(
						    
						        'const'=>'RBI',
						        'name'=>'Rekap Buku Inventaris'
						    ),
						 )
					),
					
					array(
					    'const'=>'LAPORAN_AKUNTANSI',
					    'induk'=>true,
						'name'=>'LAPORAN AKUNTANSI',
						'anak'=> array(
						   
						    array(
						    
						        'const'=>'REKAP_NERACA',
						        'name'=>'Rekap Neraca Aset'
						    ),
						    array(
						    
						        'const'=>'EKSTRA',
						        'name'=>'Ekstrakomptabel'
						    ),
						    array(
						    
						        'const'=>'INTRA',
						        'name'=>'Intrakomptabel'
						    ),
						    array(
						    
						        'const'=>'SUSUT_B',
						        'name'=>'Penyusutan KIB B'
						    ),
						    array(
						    
						        'const'=>'SUSUT_C',
						        'name'=>'Penyusutan KIB C'
						    ),
						    array(
						    
						        'const'=>'SUSUT_D',
						        'name'=>'Penyusutan KIB D'
						    ),
						    array(
						    
						        'const'=>'SUSUT_E',
						        'name'=>'Penyusutan KIB E'
						    ),
						    array(
						    
						        'const'=>'SUSUT_L',
						        'name'=>'Penyusutan Aset Lainya'
						    ),
						    array(
						    
						        'const'=>'REKAP_SUSUT',
						        'name'=>'Rekap Penyusutan'
						    ),
						 )
					),


					array(
					    'const'=>'CHATTING',
						'name'=>'Chatting'
					),

					
		); 
		
		return $module;
	}
}
