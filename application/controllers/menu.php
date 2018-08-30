<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Menu extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('Menu_model');
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>)
	 */
	var $title = 'Menu';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman absen,
	 * jika tidak akan meload halaman login
	 */
	function index()
	{
		if($this->auth->is_logged_in() == TRUE)
		{
			$this->get_all();
		}
		else
		{
			redirect('login');
		}
	}
	
	function get_all()
	{
		
		$this->auth->restrict();
		$data['header'] 	= $this->title;
		$data['title'] 		= $this->title;
		$data['sidebar'] 	= "0";
		
		// Load data
		$query 		= $this->Menu_model->get_all();
		$judul 		= $query->result();
		$num_rows 	= $query->num_rows();
		
		if ($num_rows > 0)
		{
			// Table
			/*Set table template for alternating row 'zebra'*/
			$tmpl = array( 'table_open'    => '<table border="0" cellpadding="0" cellspacing="0" width="100%">',
						  'row_alt_start'  => '<tr class="zebra">',
							'row_alt_end'    => '</tr>'
						  );
			$this->table->set_template($tmpl);

			/*Set table heading */
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('No', 'id menu', 'nama menu', 'Status', 'Actions');
			$i = 0;
			
			foreach ($judul as $row)
			{
				$this->table->add_row(++$i, $row->menu_id, $row->menu_nama,$row->menu_allowed,
										anchor('kategori/update/'.$row->menu_id,'update',array('class' => 'update')).' '.
										anchor('kategori/delete/'.$row->menu_id,'hapus',array('class'=> 'delete','onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
										);
			}
			$data['table'] = $this->table->generate();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan satupun data judul!';
		}		
		
		$data['link'] = array('link_add' => anchor('menu/add/','tambah data', array('class' => ADD)));
		
		// Load view
		$this->template->load('template','adminweb/kategori/kategori',$data);
	
		
		
	}
	
	function add()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Menu > Tambah Data';
		$data['form_action']	= site_url('menu/add_process');
		$data['link'] 			= array('link_back' => anchor('menu','kembali', array('class' => 'back'))
										);
		
		$data['header'] 		= $this->title;
		
		$data['option_menu'] 	= $this->Menu_model->menu_all();
		
		$this->template->set('title','Tambah Berita | Administrator');
		$this->template->load('template','adminweb/menu/menu_form',$data);
	}
	
	function addprocess()
	{
		$data=array(
		'nama_menu'=>$this->input->post('nama_menu'),
		'link'=>$this->input->post('link'),
		'aktif'=>$this->input->post('aktif'),
		'adminmenu'=>$this->input->post('adminmenu'));
		$add = $this->model_admin_menuutama->tambah($data);
		redirect ('adminweb/menuutama');
	}
	
	function editprocess()
	{
		$id_main=$this->input->post('id_main');
		$data=array(
			'nama_menu'=>$this->input->post('nama_menu'),
			'link'=>$this->input->post('link'),
			'aktif'=>$this->input->post('aktif'),
			'adminmenu'=>$this->input->post('adminmenu'));	
		$edit = $this->model_admin_menuutama->edit($id_main,$data);
		redirect ('adminweb/menuutama');
	}
	
	function delprocess()
	{
		$id_main=$this->uri->segment(3);
		$del = $this->model_admin_menuutama->hapus($id_main);
		redirect ('adminweb/menuutama');
	}
	
}