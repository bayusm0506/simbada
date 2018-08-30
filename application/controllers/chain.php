<?php
class Chain extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model('Model_chain', '', TRUE);
	}
	
	function index(){		
		$data['option_bidang'] = $this->Model_chain->getBidangList();
		$this->load->view('chain/index',$data);
	}
	
	function select_unit(){{
			$data['kb'] = $this->input->post('kd_bidang');
        	$data['option_unit'] = $this->Model_chain->getUnitList();	
			$this->load->view('adminweb/chain/unit',$data);
			}
		
	}
	function select_sub_unit(){
			$data['ku'] = $this->input->post('kd_unit');
			$data['kb'] = $this->input->post('kd_bidang');
        	$data['option_sub_unit'] = $this->Model_chain->getSubUnitList();		
			$this->load->view('adminweb/chain/subunit',$data);
          
		
	}
	
	function select_upb(){
           	$data['ks'] = $this->input->post('kd_sub_unit');
			$data['ku'] = $this->input->post('kd_unit');
			$data['kb'] = $this->input->post('kd_bidang');
        	$data['option_upb'] = $this->Model_chain->getUpbList();		
			$this->load->view('adminweb/chain/upb',$data);
	}
	
	function select_ruang(){
			$data['ks']           = $this->input->post('kd_sub_unit');
			$data['ku']           = $this->input->post('kd_unit');
			$data['kb']           = $this->input->post('kd_bidang');
			$data['kupb']         = $this->input->post('kd_upb');
			$data['option_ruang'] = $this->Model_chain->getRuangList();		
			$this->load->view('adminweb/chain/ruang',$data);		
	}
        
    function submit(){
        echo "Bidang ID = ".$this->input->post("kd_bidang");
        echo "<br>";
        echo "Unit ID = ".$this->input->post("kd_unit");
		echo "<br>";
        echo "Sub Unit = ".$this->input->post("kd_sub_unit");
		echo "<br>";
        echo "UPB = ".$this->input->post("kd_upb");
    }

    function select_kegiatan(){
			$data['option_kegiatan'] = $this->Model_chain->getKegiatanList();	
			$this->load->view('adminweb/listprokeg/kegiatan',$data);
		
	}

}
?>
