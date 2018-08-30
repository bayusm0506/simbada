<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	echo "<li>".anchor('adminweb/home', 'Home')."</li>";
	echo "<li>".anchor('user', 'Pengaturan')."
	<ul>
			<li>".anchor('user', 'Data User')."</li>       
    </ul></li>";
	echo "<li>".anchor('#', 'Parameter')."
	<ul>
			<li>".anchor('ruang', 'Data Ruang')."</li>
		   	<li>".anchor('dataumum', 'Data Umum UPB')."</li>        
    </ul></li>";
	echo "<li>".anchor('#', 'Entry Data')."
	<ul>
			  <li>".anchor('kiba', 'KIB A | Tanah')."</li>
			  <li>".anchor(current_url().'#', 'KIB B | Peralatan & Mesin')."
					<ul>
						  <li>".anchor('kibb', 'PERALATAN & MESIN')."</li> 
						  <li>".anchor('kendaraan', 'KENDARAAN')."</li>          
					</ul></li> 
		  	<li>".anchor('kibc', 'KIB C | Gedung & Bangunan')."</li>
		   	<li>".anchor('kibd', 'KIB D	| Jalan,Irigasi&Jaringan')."</li>
		    <li>".anchor('kibe', 'KIB E	| Aset Tetap Lainya')."</li>
			<li>".anchor('kibf', 'KIB F	| Konstruksi Dlm Pengerjaan')."</li>          
    </ul></li>";
	echo "<li>".anchor('laporan', 'Laporan')."
	<ul>
			<li>".anchor('#', 'Perencanaan & Pengadaan')."</li>
		   	<li>".anchor('#', 'Penatausahaan')."
					<ul>
						  <li>".anchor('laporan/kib', 'Kartu Inventaris Barang (KIB)')."</li> 
						  <li>".anchor('laporan/kir', 'Kartu Inventaris Ruang (KIR)')."</li>
						  <li>".anchor('laporan/manajemen', 'Manajemen [Mutasi]')."</li>
						  <li>".anchor('laporan/bukuinduk', 'Buku Induk')."</li>";
						  if ($this->session->userdata('lvl') == 01){						  
						  echo "<li>".anchor('laporan/gabungan', 'Gabungan PEMDA')."</li>";
						  } 
						 echo"</ul></li>    
    </ul></li>";
	echo "<li>".anchor('help', 'Help')."</li>";
 
?>
