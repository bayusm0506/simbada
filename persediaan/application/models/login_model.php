<?php
/**
 * Login_model Class
 *
 * @author	Awan Pribadi Basuki <awan_pribadi@yahoo.com>
 */
class Login_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct();
	}
	
	var $table = 'web_users';
	
	/**
	 * Cek tabel user, apakah ada user dengan username dan password tertentu
	 */
	function check_user($username, $password)
	{
		$pass	= md5($password);
		$query 	= $this->db->get_where($this->table, array('username' => $username, 'password' => $pass), 1, 0);
		
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{				
				$client['username'] 	= $row->username;
				$client['password'] 	= $row->password;
				$client['nama_lengkap'] = $row->nama_lengkap;
				$client['email'] 		= $row->email;
				$client['no_telp'] 		= $row->no_telp;
				$client['level'] 		= $row->level;
				$client['blokir'] 		= $row->blokir;
				$client['kd_prov'] 		= $row->kd_prov;
				$client['kd_kab_kota'] 	= $row->kd_kab_kota;
				$client['kd_bidang'] 	= $row->kd_bidang;
				$client['kd_unit'] 		= $row->kd_unit;
				$client['kd_sub_unit'] 	= $row->kd_sub_unit;
				$client['kd_upb'] 		= $row->kd_upb;
				$client['login'] 		= TRUE;				
			}
			return $client;
		}
		else
		{
			return FALSE;
		}
	}
	
	
	function id_session($username, $password)
	{
		$sid_lama = session_id();
		session_regenerate_id();
		$sid_baru = session_id();
  		mysql_query("UPDATE web_users SET id_session='$sid_baru' WHERE username='$username'");
	}
	
}

/* End of file login_model.php */ 
/* Location: ./system/application/model/login_model.php */