<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminweb extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('Ref_upb_model', '', TRUE);
		$this->load->model('Kiba_model', '', TRUE);
		$this->load->model('Kibb_model', '', TRUE);
		$this->load->model('Kibc_model', '', TRUE);
		$this->load->model('Kibd_model', '', TRUE);
		$this->load->model('Kibe_model', '', TRUE);
		$this->load->model('Kibf_model', '', TRUE);
		$this->load->helper('rupiah_helper');
	}
	
	/**
	 * Memeriksa user state
	 */
	function index()
	{
			$this->auth->restrict();
			redirect('adminweb/home');
	}
	
	
	function home(){
			$this->auth->restrict();
			$data['contents'] = "oke";
			$data['sidebar']  = "0";
			$kd_bidang        = $this->session->userdata('kd_bidang');
			$kd_unit          = $this->session->userdata('kd_unit');
			$kd_sub           = $this->session->userdata('kd_sub_unit');
			$kd_upb           = $this->session->userdata('kd_upb');
			$thn              = $this->session->userdata('tahun_anggaran');
			
			if ($this->session->userdata('lvl') == 01){
				$where = "";
			}elseif ($this->session->userdata('lvl') == 02){
				$where = "AND Kd_Bidang=$kd_bidang AND Kd_Unit=$kd_unit AND Kd_Sub=$kd_sub AND Kd_UPB=$kd_upb";
			}else{
				$where = "AND Kd_Bidang=$kd_bidang AND Kd_Unit=$kd_unit AND Kd_Sub=$kd_sub AND Kd_UPB=$kd_upb";
			}
				$where .= "AND Tgl_Perolehan like '%{$thn}%'";
			
			$this->auth->cek_dataumum($kd_bidang,$kd_unit,$kd_sub,$kd_upb);
			$nama_upb = $this->Ref_upb_model->get_nama_upb($kd_bidang,$kd_unit,$kd_sub,$kd_upb)->row();
			$data['kd_kab_kota'] 	= '<b>('.$nama_upb->Kd_Bidang.')</b>'.$nama_upb->Nm_bidang;
			$data['nama_bidang'] 	= '<b>('.$nama_upb->Kd_Bidang.')</b>'.$nama_upb->Nm_bidang;
			$data['nama_unit']		= '<b>('.$nama_upb->Kd_Unit.')</b>'.$nama_upb->Nm_unit;
			$data['nama_sub_unit'] 	= '<b>('.$nama_upb->Kd_Sub.')</b>'.$nama_upb->Nm_sub_unit;
			$data['nama_upb'] 		= '<b>('.$nama_upb->Kd_UPB.')</b>'.$nama_upb->Nm_UPB;
			$data['header'] 		= "Home ".$nama_upb->Nm_UPB;
			$this->session->set_userdata('nama_upb',$nama_upb->Nm_UPB);
			$this->template->set('title','Welcome To Administrator ');
			$this->template->load('template','home',$data);
			
	}

	function total_kib(){
		$this->auth->restrict();

		$data['kiba'] 			= $this->Kiba_model->total_kiba();
		$data['kibb'] 			= $this->Kibb_model->total_kibb();
		$data['kibc'] 			= $this->Kibc_model->total_kibc();
		$data['kibd'] 			= $this->Kibd_model->total_kibd();
		$data['kibe'] 			= $this->Kibe_model->total_kibe();
		$data['kibf'] 			= $this->Kibf_model->total_kibf();

		$this->load->view('adminweb/total',$data);
	}
	
}