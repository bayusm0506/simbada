<?php
class Pengeluaran extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
		// if(!$this->general->privilege_check('KIB_B',VIEW))
		//     $this->general->no_access();
		// $this->auth->clean_session('KIB_B');
		$this->load->model('Penerimaan_model', '', TRUE);
		$this->load->model('Pengeluaran_model', '', TRUE);
		$this->load->model('Ta_upb_model', '', TRUE);
		$this->load->model('Pemilik_model', '', TRUE);
		$this->load->model('Ref_upb_model', '', TRUE);
		$this->load->model('Chain_model', '', TRUE);
		$this->load->model('Sub_unit_model', '', TRUE);
		$this->load->model('Ref_rek_aset5_model', '', TRUE);

		$this->load->model('Ref_SSH_model', '', TRUE);

		$this->load->model('Ta_ruang_model', '', TRUE);  
	  	$this->load->model('Model_chain', '', TRUE);  
	  	$this->load->model('Ref_penyusutan_model', '', TRUE); 
		$this->load->helper('rupiah_helper');
		$this->load->helper('tgl_indonesia_helper');
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>)
	 */
	var $limit = 10; 
	var $title = ' Persediaan - Barang Keluar';
	
	/**
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		if ($this->session->userdata('lvl') == 03){
			$this->upb();
		}else{
			$this->get_data_upb();
		}
	}
	
	
	/**
	 * Tampilkan semua data skpd
	 */
	function get_data_upb()
	{
		$data['form_cari']	= site_url('pengeluaran/cari');
		$data['link_kib']	= site_url('pengeluaran/listupb');
		
		$data['header'] 	= "Pilih data SKPD";
		
		$data['title'] 		= $this->title;
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'upb');
			
		$data['query']		= $this->Sub_unit_model->sub_unit();
		
		$data['link'] = array('link_add' => anchor('pengeluaran/add/','tambah data', array('class' => ADD)));

		$this->template->load('template','adminweb/listupb/subunit',$data);
	}
	
	
	/**
	 * Tampilkan semua data upb yang dipilih
	 */
	function listupb($bidang,$unit,$sub)
	{
		$s 		= $this->input->get('s', TRUE);	
		
		$data['form_cari']	= current_URL();
		$data['link_kib']	= site_url('pengeluaran/upb');
		
		$data['header'] 	= "Pilih data UPB".$s;
		
		$data['title'] 		= $this->title;
		
		$data['option_q'] 	= array(''=>'- Pilih -','Nama UPB'=>'Nama UPB');
			
		$data['query']		= $this->Ref_upb_model->upb($bidang,$unit,$sub,$s);
		
		
		$data['link'] = array('link_add' => anchor('pengeluaran/add/','tambah data', array('class' => ADD)));

		$this->template->load('template','adminweb/listupb/upb',$data);
	}
	
	
	
	/**
	 * Tampilkan semua data persediaan
	 */
	function upb($kd_bidang='',$kd_unit='',$kd_sub='',$kd_upb='')
	{
		if ($this->session->userdata('lvl') != '03'){
			$this->auth->cek_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		}else{
			$kd_bidang 	=  $this->session->userdata('kd_bidang');
			$kd_unit 	=  $this->session->userdata('kd_unit');
			$kd_sub 	=  $this->session->userdata('kd_sub_unit');
			$kd_upb 	=  $this->session->userdata('kd_upb');
		}
		
		$q        = $this->session->userdata('q');
		$s        = $this->session->userdata('s');	
		$tanggal1 = $this->session->userdata('tanggal1');
		$tanggal2 = $this->session->userdata('tanggal2');	
		
		$kb 	=  $this->session->userdata('kd_bidang');
		$ku 	=  $this->session->userdata('kd_unit');
		$ks 	=  $this->session->userdata('kd_sub_unit');
		$kupb 	=  $this->session->userdata('kd_upb');
		$thn 	=  $this->session->userdata('tahun_anggaran');
		

		if (empty($tanggal1) AND empty($tanggal2) AND empty($q) AND empty($s)){
			$like 	= "AND Tgl_Pengeluaran LIKE '%$thn%'";
			$judul 	= "Tahun pembukuan ".$thn;
		}elseif (!empty($tanggal1) AND !empty($tanggal2)  AND empty($q) AND empty($s)){
			$like 	= "AND Tgl_Pengeluaran BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2);
		}elseif ($q=='all'){
			$like 	= "";
			$judul 	= "Semua Data Persediaan";
		}elseif (empty($tanggal1) AND empty($tanggal2) AND !empty($q) AND !empty($s)){
			$like 	= "AND $q LIKE '%$s%'";
			$judul 	= "Pencarian dengan ".cari($q)." $s";
		}else{
			$like 	= "AND $q LIKE '%$s%' AND Tgl_Pengeluaran BETWEEN '$tanggal1' AND '$tanggal2'";
			$judul 	= "Tanggal Pembelian ".tgl_indo($tanggal1)." s/d ".tgl_indo($tanggal2)." pencarian dengan ".cari($q)." $s";
		}
		
		$page		= $this->input->get('per_page', TRUE);
		
		$this->session->set_userdata('curl', current_url());
		
		if(empty($page)){
			$offset = 0;	
		}else{
			$offset = $page;
		}

		
		if ($this->session->userdata('lvl') == 01){
			$where = " AND a.Kd_Bidang=$kd_bidang AND a.Kd_Unit=$kd_unit AND a.Kd_Sub=$kd_sub AND a.Kd_UPB=$kd_upb";
		}elseif ($this->session->userdata('lvl') == 02){
			$where = " AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kd_upb";
		}else{
			$where = " AND a.Kd_Bidang=$kb AND a.Kd_Unit=$ku AND a.Kd_Sub=$ks AND a.Kd_UPB=$kupb";
		}
		
		$data['title'] 		= $this->title;
		$data['judul'] 		= $judul;
		
		$this->session->set_userdata('addKd_Bidang', $kd_bidang);
		$this->session->set_userdata('addKd_Unit', $kd_unit);
		$this->session->set_userdata('addKd_Sub', $kd_sub);
		$this->session->set_userdata('addKd_UPB', $kd_upb);
		$this->session->set_userdata('sess_out', md5(date("m/d/Y H:i:s")));
		

		$nmupb				= $this->Ref_upb_model->nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		$data['query'] 		= $this->Pengeluaran_model->get_page($this->limit, $offset, $where, $like);
		$num_rows 			= $this->Pengeluaran_model->count_data($where,$like)->Jumlah;
		$total 				= $this->Pengeluaran_model->count_data($where,$like)->Total;
		
		$data['header'] 	= $this->title.' | '.$nmupb.' | Total Harga = Rp.'.rp($total).',-';
		$data['jumlah'] 	= $num_rows;
		
		$data['offset']		= $offset;
		$data['form_cari']	= current_URL();

		$data['option_q']   = pencarian_persediaan_barang_keluar(); 
		
		if ($num_rows > 0)
		{
			$config['base_url'] 	= current_URL().'?';
			$config['total_rows'] 	= $num_rows;
			$config['per_page'] 	= $this->limit;
			$config['uri_segment'] 	= $offset;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$data['pagination'] 	= $this->pagination->create_links();
		}else{
			$data['message'] = 'Tidak ditemukan data Persediaan!';
		}		
		$this->template->load('template','adminweb/pengeluaran/pengeluaran',$data);
	}
	
	
	/**
	 * Pindah ke halaman tambah persediaan
	 */
	function add()
	{	
		// if(!$this->general->privilege_check('KIB_B',ADD))
		//     $this->general->no_access();

		$data['Kd_Prov']     = $this->session->userdata('kd_prov');
		$data['Kd_Kab_Kota'] = $this->session->userdata('kd_kab_kota');
		$data['Kd_Bidang']   = $this->session->userdata('addKd_Bidang');
		$data['Kd_Unit']     = $this->session->userdata('addKd_Unit');
		$data['Kd_Sub']      = $this->session->userdata('addKd_Sub');
		$data['Kd_UPB']      = $this->session->userdata('addKd_UPB');
	
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Pengeluaran > Tambah Pengeluaran';
		$data['form_action']	= site_url('pengeluaran/kontrak_proses');
		$nmupb					= $this->Ref_upb_model->nama_upb($this->session->userdata('addKd_Bidang'),
																 $this->session->userdata('addKd_Unit'),
																 $this->session->userdata('addKd_Sub'),
																 $this->session->userdata('addKd_UPB'));								
		$data['header'] 		= $this->title.' ('.$nmupb.')';
		$this->template->load('template','adminweb/pengeluaran/pengeluaran_addform',$data);
	}
	
	/**
	 * Proses tambah data persediaan
	 */
	function kontrak_proses()
	{
			// echo $this->input->post('Kd_Bidang'); exit();
			$data = $this->input->post(NULL, TRUE);
			
			// print_r($data); exit();
			$sql = $this->Penerimaan_model->save($data);

			if ($sql){
				$this->session->set_flashdata('message', 'Satu data Kontrak berhasil ditambah !');
				redirect('pengeluaran/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'
										.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
			}else{
				$this->session->set_flashdata('message', 'Satu data Kontrak berhasil ditambah !');
				redirect('pengeluaran/upb/'.$this->session->userdata('addKd_Bidang').'/'.$this->session->userdata('addKd_Unit').'/'
										.$this->session->userdata('addKd_Sub').'/'.$this->session->userdata('addKd_UPB'));
				}
	}


	
	public function set()
	{

			$session_data = array(
				'q'        => $this->input->post('q'),
				's'        => clean($this->input->post('s')),
				'tanggal1' => $this->input->post('tanggal1'),
				'tanggal2' => $this->input->post('tanggal2')
			);
			$this->session->set_userdata($session_data);

			header('location:'.$this->session->userdata('curl'));
	}

	public function filter()
	{
			$session_data = array(
				'riwayat'  => $this->input->post('riwayat')
			);
			
			$this->session->set_userdata($session_data);
			header('location:'.$this->session->userdata('curl'));
	}

	
	/**
	 * Hapus data KIB B
	 */
	function hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)
	{
		// if(!$this->general->privilege_check('KIB_B','remove'))
		//     $this->general->no_access();

		$this->Penerimaan_model->hapus($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
		redirect('persediaan');
	}
		
	function lookup()
	{
		$page  = $this->input->get('page');
		$limit = $this->input->get('rows');
		$sidx  = $this->input->get('sidx');
		$sord  = $this->input->get('sord');
		
		if(!$sidx) $sidx=1;
		
		
		$where = ""; 
		$searchField 	= isset($_GET['searchField']) ? $_GET['searchField'] : false;
		$searchString 	= isset($_GET['searchString']) ? $_GET['searchString'] : false;
		if ($_GET['_search'] == 'true') {
			$where = "$searchField like '%$searchString%'";
		}
	        		
		$count = $this->Ref_rek_aset5_model->count_persediaan($where);
		
		$count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;
		
		$data1 = $this->Ref_rek_aset5_model->get_persediaan($where, $sidx, $sord, $limit, $start)->result();
	
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		$i=0;
		foreach($data1 as $line){
		$responce->rows[$i]['Kd_Aset1']  = $line->Kd_Aset1;
		$responce->rows[$i]['cell'] 	 = array('',$line->Kd_Aset1,$line->Kd_Aset2,$line->Kd_Aset3,$line->Kd_Aset4,$line->Kd_Aset5,$line->Nm_Aset5);
		$i++;
		}
		echo json_encode($responce);
	}
	
	
	
	function json(){
        $keyword = $this->input->post('term');
        $data['response'] = 'false'; 
        $query = $this->Penerimaan_model->json_stok_penerimaan($keyword); 
        if( ! empty($query) )
        {
            $data['response'] = 'true';
            $data['message'] = array();
            $tmp_data = array();
            foreach( $query as $row )
            {

					$ka1         = sprintf ("%02u", $row->Kd_Aset1);
					$ka2         = sprintf ("%02u", $row->Kd_Aset2);
					$ka3         = sprintf ("%02u", $row->Kd_Aset3);
					$ka4         = sprintf ("%02u", $row->Kd_Aset4);
					$ka5         = sprintf ("%02u", $row->Kd_Aset5);
					$ka6         = sprintf ("%02u", $row->Kd_Aset6);
					$kode_barang = $ka1.'.'.$ka2.'.'.$ka3.'.'.$ka4.'.'.$ka5.'.'.$ka6;

            	if(!in_array($kode_barang, $tmp_data)) {
	                $data['message'][] = array(
	                                        'id1'=>$row->Kd_Aset1,
											'id2'=>$row->Kd_Aset2,
											'id3'=>$row->Kd_Aset3,
											'id4'=>$row->Kd_Aset4,
											'id5'=>$row->Kd_Aset5,
											'id6'=>$row->Kd_Aset6,
											'id7'=>$row->No_Register,
											'id8'=>$row->No_ID,
											'harga'=>nilai($row->Harga),
											'stok'=>$row->Stok,
											'nm_satuan'=>$row->Nm_Satuan,
	                                        'value' => $row->Nm_Aset6." ".$row->Spesifikasi,
	                                        ''
	                                     );

                  array_push($tmp_data, $kode_barang);
	            }
            }
        }

        if('IS_AJAX')
        {
            echo json_encode($data);

        }
        else
        {
            $this->load->view('pengeluaran/index',$data);
        }
	}

	function detail($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id)
	{
		$data['title']         = $this->title;
		$data['h2_title']      = 'Penerimaan > Detail';
		$data['form_action']   = site_url('pengeluaran/detail');
		$data['header']        = $this->title;
		
		$data['option_satuan'] = $this->Ref_SSH_model->SatuanList();

		$jumlah = $this->Penerimaan_model->get_penerimaan_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id)->num_rows();
					
		if ($jumlah > 0){
				$get = $this->Penerimaan_model->get_penerimaan_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id)->row_array();
													  
				$data['rincian'] 	= $this->Penerimaan_model->penerimaan_rinc($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id);

				$this->template->load('template','adminweb/penerimaan/detail',array_merge($data,$get));				
		}else{
			echo "tidak ada data";	
		}
	}

	function addbarang()
	{
		
		$Kd_Prov     = $this->input->post('Kd_Prov');
		$Kd_Kab_Kota = $this->input->post('Kd_Kab_Kota');
		$Kd_Bidang   = $this->input->post('Kd_Bidang');
		$Kd_Unit     = $this->input->post('Kd_Unit');
		$Kd_Sub      = $this->input->post('Kd_Sub');
		$Kd_UPB      = $this->input->post('Kd_UPB');
		$No_ID       = $this->input->post('No_ID');
		$Kd_Aset1    = $this->input->post('Kd_Aset1');
		$Kd_Aset2    = $this->input->post('Kd_Aset2');
		$Kd_Aset3    = $this->input->post('Kd_Aset3');
		$Kd_Aset4    = $this->input->post('Kd_Aset4');
		$Kd_Aset5    = $this->input->post('Kd_Aset5');
		$Kd_Aset6    = $this->input->post('Kd_Aset6');
		$No_Register = $this->input->post('No_Register');

		$last_idkeluar  = $this->Pengeluaran_model->get_last_idkeluar($Kd_Prov,$Kd_Kab_Kota,$Kd_Bidang,$Kd_Unit,$Kd_Sub,$Kd_UPB,$No_ID,$Kd_Aset1,$Kd_Aset2,$Kd_Aset3,$Kd_Aset4,$Kd_Aset5,$Kd_Aset6,$No_Register);

		$data = array(
					'Kd_Prov'         => $Kd_Prov,
					'Kd_Kab_Kota'     => $Kd_Kab_Kota,
					'Kd_Bidang'       => $Kd_Bidang,
					'Kd_Unit'         => $Kd_Unit,
					'Kd_Sub'          => $Kd_Sub,
					'Kd_UPB'          => $Kd_UPB,
					'No_ID'           => $No_ID,
					'Kd_Aset1'        => $Kd_Aset1,
					'Kd_Aset2'        => $Kd_Aset2,
					'Kd_Aset3'        => $Kd_Aset3,
					'Kd_Aset4'        => $Kd_Aset4,
					'Kd_Aset5'        => $Kd_Aset5,
					'Kd_Aset6'        => $Kd_Aset6,
					'No_Register'     => $No_Register,
					'Id_Pengeluaran'  => $last_idkeluar,
					'Tgl_Pengeluaran' => $this->input->post('Tgl_Pengeluaran'),
					'Kepada'          => $this->input->post('Kepada'),
					'No_BAST'         => $this->input->post('No_BAST'),
					'Tgl_BAST'        => $this->input->post('Tgl_BAST'),
					'Jumlah'          => $this->input->post('Jumlah'),
					'Keterangan'      => $this->input->post('Keterangan'),
					'Log_entry'       => date("Y-m-d H:i:s"),
					'Log_User'        => $this->session->userdata('username'),
					'Sess_Out'        => $this->session->userdata('sess_out')
			);
		$insert = $this->db->insert('Ta_Pengeluaran', $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_list_transaksi()
	{
		$query 	= $this->Pengeluaran_model->pengeluaran_rinc();

			$data 	= array();
			$no     = 1;
		foreach ($query->result() as $items){
			$row    = array();

			$ka1         = sprintf ("%02u", $items->Kd_Aset1);
			$ka2         = sprintf ("%02u", $items->Kd_Aset1);
			$ka3         = sprintf ("%02u", $items->Kd_Aset3);
			$ka4         = sprintf ("%02u", $items->Kd_Aset4);
			$ka5         = sprintf ("%02u", $items->Kd_Aset5);
			$ka6         = sprintf ("%02u", $items->Kd_Aset6);
			$kode_barang = $ka1.'.'.$ka2.'.'.$ka3.'.'.$ka4.'.'.$ka5.'.'.$ka6;

			$row[]  = $no;
			$row[]  = $kode_barang;
			$row[]  = $items->Nm_Aset6." ".$items->Spesifikasi;
			$row[]  = "<center>".$items->No_Register."</center>";
			$row[]  = "<center>".rp($items->Jumlah)." ".$items->Nm_Satuan."</center>";
			$row[]  = "<center>".tgl_dmy($items->Tgl_Pengeluaran)."</center>";
			$row[]  = '<a href="javascript:void()" style="color:rgb(255,128,128);
					  text-decoration:none" onclick="deletebarang('.$items->Kd_Prov.','.$items->Kd_Kab_Kota.','.$items->Kd_Bidang.','.$items->Kd_Unit.','.$items->Kd_Sub.','.$items->Kd_UPB.','.$items->No_ID.','.$items->Kd_Aset1.','.$items->Kd_Aset2.','.$items->Kd_Aset3.','.$items->Kd_Aset4.','.$items->Kd_Aset5.','.$items->Kd_Aset6.','.$items->No_Register.','.$items->Id_Pengeluaran.')">
					  <i class="fa fa-close"></i> Delete</a>';
			$data[] = $row;
			$no++;
		}

		$output = array(
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	/**
	 * Menghapus dengan ajax post
	 */
	function deletebarang($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6,$no_register,$id_pengeluaran){

		// if(!$this->general->privilege_check('KIB_B',REMOVE))
		 //    $this->general->no_access();

		if ($this->session->userdata('lvl') == 01){
			$kd_bidang	=  $kd_bidang;
			$kd_unit	=  $kd_unit;
			$kd_sub		=  $kd_sub;
			$kd_upb		=  $kd_upb;	
		}elseif ($this->session->userdata('lvl') == 02){
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $kd_upb;
		}else{
			$kd_bidang	=  $this->session->userdata('kd_bidang');
			$kd_unit	=  $this->session->userdata('kd_unit');
			$kd_sub		=  $this->session->userdata('kd_sub_unit');
			$kd_upb		=  $this->session->userdata('kd_upb');
		}
			
		$this->Pengeluaran_model->hapus($kd_prov,$kd_kab_kota,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$no_id,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$kd_aset6,$no_register,$id_pengeluaran);
		echo json_encode(array("status" => TRUE));
	}

	function select_q(){
			
			$cek = $this->input->post('q');
			if($cek == "Harga"){
				$this->load->view('adminweb/form/harga');
			}else{
				echo "<input type='text' placeholder='Isi disini' name='s' id='cari' class='input-medium'>";
			}
	}


}

/* End of file persediaan.php */
/* Location: ./system/application/controllers/persediaan.php */