<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cetak extends CI_Controller {
	
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
		$this->auth->restrict();
		$this->load->model('Pemanfaatan_model', '', TRUE);
		$this->load->model('Kibb_model', '', TRUE);
		$this->load->model('Kibc_model', '', TRUE);
		$this->load->model('Kibd_model', '', TRUE);
		$this->load->model('Kibe_model', '', TRUE);
		$this->load->model('Lainnya_model', '', TRUE);

		$this->load->model('Ref_upb_model', '', TRUE);

		$this->load->model('Ref_rek_aset5_model', '', TRUE);
		$this->load->helper('rupiah_helper');
		$this->load->helper('tgl_indonesia_helper');
		$this->load->helper('sanjaya_helper');
	}
	
	var $title = "Cetak";
	
	function index()
	{
			$this->auth->restrict();
			redirect('laporan/kib');
	}
	
	function ba($jenis,$id){
		$no = decrypt_url($id);

		$get  = $this->Pemanfaatan_model->get_data_id($jenis,$no);
		if(!$get)
	        show_error("Anda tidak dapat mengakses halaman ini");

		$data['jumlah_ba']    = $this->Pemanfaatan_model->laporan_ba($get['No_Dokumen'])->num_rows();
		$data['ba']           = $this->Pemanfaatan_model->laporan_ba($get['No_Dokumen']);
		$sql                  = $this->Pemanfaatan_model->total_ba($get['No_Dokumen'])->row();
		$data['total_harga']  = $sql->Harga;
		$data['total_jumlah'] = $sql->Jumlah;
		if($get['Jenis_Dokumen'] == 1){
			$this->load->view('adminweb/cetak/bast',array_merge($get,$data));
		}elseif ($get['Jenis_Dokumen'] == 2) {
			$this->load->view('adminweb/cetak/bapp',array_merge($get,$data));
		}
	}
	/* 13-05-2015 penyusutan */
	function penyusutan(){
		$this->auth->restrict();
		$data['title'] = $this->title;
		$kd_bidang     =  $this->input->post('kd_bidang');
		$kd_unit       =  $this->input->post('kd_unit');
		$kd_sub        =  $this->input->post('kd_sub_unit');
		$kd_upb        =  $this->input->post('kd_upb');
		
		$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
		
		$tahunawal       = $this->input->post('tahunawal');
		$tahunakhir      = $this->input->post('tahunakhir');
		$data['periode'] = date('d M Y', strtotime($this->input->post('tahunawal')))." s/d ".date('d M Y', strtotime($this->input->post('tahunakhir')));
		$tahun           = date('Y', strtotime($this->input->post('tahunakhir')));
		
		$data['tanggal'] =  $this->input->post('tanggal');

		$like = "Tgl_Perolehan BETWEEN '$tahunawal' AND '$tahunakhir'";
		
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row();
		$cek_upb          = $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->num_rows();
		if ($cek_upb > 0){
			$data['ta_upb'] 	= $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$tahun)->row();
		}else{
			$data['ta_upb'] 	= $this->Ta_upb_model->get_data_umum($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$this->session->userdata('tahun_anggaran'))->row();
		}
		
		$data['jumlah_penyusutan'] = $this->Kibb_model->laporan_penyusutan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like)->num_rows();
		$data['penyusutan']        = $this->Kibb_model->laporan_penyusutan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$data['total_penyusutan']  = $this->Kibb_model->total_penyusutan($kd_bidang,$kd_unit,$kd_sub,$kd_upb,$like);
		$this->load->view('adminweb/laporan/penyusutan',$data);
	}

	/* 13-05-2015 history */
	function history($id){
		$exp = decrypt_url($id);
		$arr = explode("/", $exp);

		$kd_prov     = $arr[0];
		$kd_kab      = $arr[1];
		$kd_bidang   = $arr[2];
		$kd_unit     = $arr[3];
		$kd_sub      = $arr[4];
		$kd_upb      = $arr[5];
		$kd_aset1    = $arr[6];
		$kd_aset2    = $arr[7];
		$kd_aset3    = $arr[8];
		$kd_aset4    = $arr[9];
		$kd_aset5    = $arr[10];
		$no_register = $arr[11];

		$get = $this->Kibb_model->get_kibb_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		if(!$get)
	        show_error("Anda tidak dapat mengakses halaman ini");

	    $data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();

	    $data['foto'] 		= $this->Kibb_model->data_foto($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);

	    $data['riwayat'] 	  = $this->Kibb_model->get_last_kapitalisasi($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		
	    $data['nm_aset']	= $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
		$this->load->view('adminweb/cetak/history',array_merge($get,$data));
	}

	/* 13-05-2015 history */
	function kibc_history($id){
		$exp = decrypt_url($id);
		$arr = explode("/", $exp);

		$kd_prov     = $arr[0];
		$kd_kab      = $arr[1];
		$kd_bidang   = $arr[2];
		$kd_unit     = $arr[3];
		$kd_sub      = $arr[4];
		$kd_upb      = $arr[5];
		$kd_aset1    = $arr[6];
		$kd_aset2    = $arr[7];
		$kd_aset3    = $arr[8];
		$kd_aset4    = $arr[9];
		$kd_aset5    = $arr[10];
		$no_register = $arr[11];

		$get = $this->Kibc_model->get_kibc_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,
												  $kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		
		// print_r($get); exit();
		if(!$get)
	        show_error("Anda tidak dapat mengakses halaman ini");

		$data['nama_upb']     = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();
		
		$data['foto']         = $this->Kibc_model->data_foto($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
		
		$data['riwayat'] 	  = $this->Kibc_model->get_last_kapitalisasi($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		
		
		$data['nm_aset']      = $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
		$this->load->view('adminweb/cetak/kibc_history',array_merge($get,$data));
	}

	/* 13-05-2015 history */
	function kibd_history($id){
		$exp = decrypt_url($id);
		$arr = explode("/", $exp);

		$kd_prov     = $arr[0];
		$kd_kab      = $arr[1];
		$kd_bidang   = $arr[2];
		$kd_unit     = $arr[3];
		$kd_sub      = $arr[4];
		$kd_upb      = $arr[5];
		$kd_aset1    = $arr[6];
		$kd_aset2    = $arr[7];
		$kd_aset3    = $arr[8];
		$kd_aset4    = $arr[9];
		$kd_aset5    = $arr[10];
		$no_register = $arr[11];

		$get = $this->Kibd_model->get_kibd_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		if(!$get)
	        show_error("Anda tidak dapat mengakses halaman ini");
		
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();
		
		$data['foto']     = $this->Kibd_model->data_foto($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
		
		$data['riwayat']  = $this->Kibd_model->get_last_kapitalisasi($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		
		
		$data['nm_aset']  = $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
		$this->load->view('adminweb/cetak/kibd_history',array_merge($get,$data));
	}

	function kibe_history($id){
		$exp = decrypt_url($id);
		$arr = explode("/", $exp);

		$kd_prov     = $arr[0];
		$kd_kab      = $arr[1];
		$kd_bidang   = $arr[2];
		$kd_unit     = $arr[3];
		$kd_sub      = $arr[4];
		$kd_upb      = $arr[5];
		$kd_aset1    = $arr[6];
		$kd_aset2    = $arr[7];
		$kd_aset3    = $arr[8];
		$kd_aset4    = $arr[9];
		$kd_aset5    = $arr[10];
		$no_register = $arr[11];

		$get = $this->Kibe_model->get_kibe_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		if(!$get)
	        show_error("Anda tidak dapat mengakses halaman ini");
		
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();
		
		$data['foto']     = $this->Kibe_model->data_foto($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
		
		$data['riwayat']  = $this->Kibe_model->get_last_kapitalisasi($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		
		
		$data['nm_aset']  = $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
		$this->load->view('adminweb/cetak/kibe_history',array_merge($get,$data));
	}

	function lainnya_history($id){
		$exp = decrypt_url($id);
		$arr = explode("/", $exp);

		$kd_prov     = $arr[0];
		$kd_kab      = $arr[1];
		$kd_bidang   = $arr[2];
		$kd_unit     = $arr[3];
		$kd_sub      = $arr[4];
		$kd_upb      = $arr[5];
		$kd_aset1    = $arr[6];
		$kd_aset2    = $arr[7];
		$kd_aset3    = $arr[8];
		$kd_aset4    = $arr[9];
		$kd_aset5    = $arr[10];
		$no_register = $arr[11];

		$get = $this->Lainnya_model->get_lainnya_by_id($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		if(!$get)
	        show_error("Anda tidak dapat mengakses halaman ini");
		
		$data['nama_upb'] = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row_array();
		
		$data['foto']     = $this->Lainnya_model->data_foto($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register);
		
		$data['riwayat']  = $this->Lainnya_model->get_last_kapitalisasi($kd_prov,$kd_kab,$kd_bidang,$kd_unit,$kd_sub,$kd_upb,$kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5,$no_register)->row_array();
		
		
		$data['nm_aset']  = $this->Ref_rek_aset5_model->nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5);
		$this->load->view('adminweb/cetak/lainnya_history',array_merge($get,$data));
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */