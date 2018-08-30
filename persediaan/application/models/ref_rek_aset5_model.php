<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_rek_aset5_model extends CI_Model{
	
	var $table = 'Ref_Rek_Aset5';
	
	/**
	 * Konvert nama Aset
	 */
	function nama_aset($kd_aset1,$kd_aset2,$kd_aset3,$kd_aset4,$kd_aset5)
	{	
		$this->db->select('Nm_Aset5');
		$this->db->where('Kd_Aset1',$kd_aset1);
		$this->db->where('Kd_Aset2',$kd_aset2);
		$this->db->where('Kd_Aset3',$kd_aset3);
		$this->db->where('Kd_Aset4',$kd_aset4);
		$this->db->where('Kd_Aset5',$kd_aset5);
        $this->db->from($this->table);
		$sql = $this->db->get();
		foreach ($sql->result() as $row)
		{
			$result = $row->Nm_Aset5;
		}
		return $result;
	}
	
	function json_all($keyword){
		$this->db->LIMIT(10);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	function count_all($like) {
		$like != '' ? $this->db->like($like) : '';
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_all($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }
		

		if ($like != ''){
		return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM    (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}
	
	function json_kiba($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1',1);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	function count_kiba($like) {
		$like != '' ? $this->db->like($like) : '';
		$this->db->where('Kd_Aset1',1);
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_kiba($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }
		

		if ($like != ''){
		return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '1' AND $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '1') AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}
	
	function json_kibb($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1',2);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
		
	function count_kibb($like) {
		$like != '' ? $this->db->like($like) : '';
		$this->db->where('Kd_Aset1',2);
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_kibb($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }
		
		if ($like != ''){
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '2' AND Kd_Aset2 <> '3' AND $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE  Kd_Aset1 = '2' AND Kd_Aset2 <> '3') AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}
	
	function json_kendaraan($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1',2);
		$this->db->where('Kd_Aset2',3);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	function count_kendaraan($like) {
		$like != '' ? $this->db->like($like) : '';
		$this->db->where('Kd_Aset1',2);
		$this->db->where('Kd_Aset2',3);
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_kendaraan($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }
		
		if ($like != ''){
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '2' AND Kd_Aset2 = '3' AND $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE  Kd_Aset1 = '2' AND Kd_Aset2 = '3') AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}
	
	function json_kibc($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1',3);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	
	function count_kibc($like) {
		$like != '' ? $this->db->like($like) : '';
		$this->db->where('Kd_Aset1',3);
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_kibc($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }

		if ($like != ''){
		return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '3' AND $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '3') AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}
	
	function json_kibd($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1',4);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	
	function count_kibd($like) {
		$like != '' ? $this->db->like($like) : '';
		$this->db->where('Kd_Aset1',4);
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_kibd($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }
	
		
		if ($like != ''){
		return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '4' AND $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE  Kd_Aset1 = '4') AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}
	
	function json_kibe($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1',5);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	
	function count_kibe($like) {
		$like != '' ? $this->db->like($like) : '';
		$this->db->where('Kd_Aset1',5);
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_kibe($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }
	
		
		if ($like != ''){
		return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '5' AND $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE  Kd_Aset1 = '5') AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}
	
	
	function json_kibf($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1 != 2 AND Kd_Aset1 != 5');
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
	
	function count_kibf($like) {
		$like != '' ? $this->db->like($like) : '';
		$this->db->where('Kd_Aset1 != 2 AND Kd_Aset1 != 5');
        return $this->db->count_all_results('Ref_Rek_Aset5');
    }
	
	
	function get_kibf($like, $sidx, $sord, $limit, $start) {
		
		if($start == 0  or !isset($start)){
            $first = 1;
            $last  = $limit;
        }else{
            $first = $start+1;
            $last  = $first + ($limit -1);
        }

		if ($like != ''){
		return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '3' AND $like) AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}else{
			return $this->db->query("SELECT     no_urut,* FROM         (SELECT     *, ROW_NUMBER() OVER (ORDER BY Kd_Aset1) AS no_urut
		FROM    Ref_Rek_Aset5 WHERE Kd_Aset1 = '3') AS Ref_Rek_Aset5 WHERE no_urut BETWEEN $first AND $last");
		}

	}


	function json_lainnya($keyword){
		$this->db->LIMIT(10);
		$this->db->where('Kd_Aset1',7);
		$this->db->select('*')->from('Ref_Rek_Aset5');
        $this->db->like('Nm_Aset5',$keyword);
        $query = $this->db->get();    
        
        return $query->result();
	}
	
}