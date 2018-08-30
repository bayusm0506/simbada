<?php
	$this->load->library('cetak/PDF_MC_Table_penyusutan_satuan');

	$pdf=new PDF_MC_Table_penyusutan_satuan('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"DAFTAR PENYUSUTAN PERSATUAN BARANG\nKIB D. JALAN, IRIGASI & JARINGAN\nPEMERINTAH PROVINSI SUMATERA UTARA",0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN ".$this->session->userdata("tahun_anggaran"),0,'C');
	$pdf->Ln(10);	

	$kb1 = sprintf ("%02u", $nama_upb['Kd_Bidang']);
	$kb2 = sprintf ("%02u", $nama_upb['Kd_Unit']);
	$kb3 = sprintf ("%02u", $nama_upb['Kd_Sub']);
	$kb4 = sprintf ("%02u", $nama_upb['Kd_UPB']);
	$kode_lokasi  = KODE_LOKASI.'.'.$kb1.'.'.$kb2.'.'.$kb3.'.'.$kb4;
	$kode_lokasi2 = KODE_LOKASI;

	$pdf->UPBTitle($nama_upb['Nm_bidang'],$nama_upb['Nm_unit'],$nama_upb['Nm_sub_unit'],'',$kode_lokasi);

	$pdf->Ln();
	$tgl=date('Y-m-d');

	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(15,30,20,50,25,20,45,45,45,45,25,45,30));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13"));

	/* KIB B */
	$no=1;
	$pdf->SetFont('Times','','10');
	
	$last_kondisi   = 1;
	$mf             = $Masa_Manfaat;
	$update_mf      = 2;
	$update_sisa_mf = 1;
	$update_hr      = 1;
	$akm_susut      = 0;
	$update_nb      = 0;
	$no=0;
	while ($update_mf > 1) {

		$curr_thn 	 = thn($Tgl_Perolehan)+$no;

		$k = cek_kapitalisasi_kib_d($Kd_Prov,$Kd_Kab_Kota,$Kd_Bidang,$Kd_Unit,$Kd_Sub,$Kd_UPB,$Kd_Aset1,$Kd_Aset2,$Kd_Aset3,$Kd_Aset4,$Kd_Aset5,$No_Register,$curr_thn)->row();


		if($k){
			$k_masa_manfaat = $k->Masa_Manfaat;
			$k_harga = $k->Harga;
		}else{
			$k_masa_manfaat = 0;
			$k_harga = 0;
		}


		$cek_kondisi = cek_kondisi_kib_d($Kd_Prov,$Kd_Kab_Kota,$Kd_Bidang,$Kd_Unit,$Kd_Sub,$Kd_UPB,$Kd_Aset1,$Kd_Aset2,$Kd_Aset3,$Kd_Aset4,$Kd_Aset5,$No_Register,$curr_thn);

		if($cek_kondisi){
			$last_kondisi = $cek_kondisi;
			$cek_kondisi  = $last_kondisi;
		}else{
			$cek_kondisi  = $last_kondisi;
		}

		if($cek_kondisi == 3){
			$susut     = 0;
			$update_hr = 0;
			$update_nb = 0;
		}

		if($no==0){
			$nama_aset       = $Nm_Aset5;
			$tahun_perolehan = thn($Tgl_Perolehan);
			$masa_manfaat = $Masa_Manfaat;
			$Keterangan = $Keterangan;
			$update_mf      = $mf+$k_masa_manfaat;
			$update_hr      = $LastHarga+$k_harga;
			$LastHarga		= $LastHarga;
			$susut          = $update_hr/$update_mf;
			$akm_susut      = $susut;
			$update_sisa_mf = $update_mf - 1;
			$update_nb      = $update_hr - $susut;

		}else{
			$nama_aset = '';
			$tahun_perolehan = "";
			$masa_manfaat = "";
			$Keterangan = "";
			$update_mf      = ($update_sisa_mf+$k_masa_manfaat) > $mf ? $mf : ($update_sisa_mf+$k_masa_manfaat);
			$update_hr      = $update_nb + $k_harga;
			$LastHarga		= $update_nb;
			$susut          = $update_hr/$update_mf;
			$akm_susut      = $akm_susut+$susut;
			$update_sisa_mf = $update_mf - 1;
			$update_nb      = $update_hr - $susut;
		}

	$pdf->Row(array($no, "",$No_Register, $nama_aset,$tahun_perolehan,$masa_manfaat, rp($update_hr), rp($k_harga), rp($susut), rp($akm_susut), rp($update_sisa_mf),rp($update_nb),$Keterangan));

	$no++;
	}

	$pdf->SetFont('Times','B','10');
	//$pdf->Row(array("", "", "	", "TOTAL KIB B","", "", "", "","", 321, "","", "", 321,"", 321, ""));

	$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,'');

	$pdf->Output("SIMDO_KIB_D_Penyusutan.pdf","I");
?>