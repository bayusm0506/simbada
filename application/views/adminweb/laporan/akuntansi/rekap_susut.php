<?php
	$this->load->library('akuntansi/PDF_MC_Table_rekap_penyusutan');

	$pdf=new PDF_MC_Table_rekap_penyusutan('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"REKAPITULASI HASIL PENYUSUTAN ASET TETAP\nPEMERINTAH PROVINSI SUMATERA UTARA",0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');
	$pdf->Ln(20);	

	$kb1 = sprintf ("%02u", $nama_upb['Kd_Bidang']);
	$kb2 = sprintf ("%02u", $nama_upb['Kd_Unit']);
	$kb3 = sprintf ("%02u", $nama_upb['Kd_Sub']);
	$kb4 = sprintf ("%02u", $nama_upb['Kd_UPB']);
	$kode_lokasi  = KODE_LOKASI.'.'.$kb1.'.'.$kb2.'.'.$kb3.'.'.$kb4;
	$kode_lokasi2 = KODE_LOKASI;

	if(($ttd_pengurus) AND ($skpd)){
		$pdf->UPBTitle($nama_upb['Nm_bidang'],$nama_upb['Nm_unit'],$nama_upb['Nm_sub_unit'],'',$kode_lokasi);
	}elseif($ttd_pengurus){
		$pdf->UPBTitle($nama_upb['Nm_bidang'],$nama_upb['Nm_unit'],$nama_upb['Nm_sub_unit'],$nama_upb['Nm_UPB'],$kode_lokasi);
	}else{
		$pdf->UPBTitle2($kode_lokasi2);
	}


	$pdf->Ln();
	$tgl=date('Y-m-d');

	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(15,35,50,35,70,47,47,47,47,47));
	$pdf->Rowheader($this->session->userdata('awal'),$this->session->userdata('akhir'));
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10"));

	/* KIB B */
	$no=1;
	$kibb_tHarga        = sum_arr($kibb,'Harga');
	$kibb_tNB_Awal      = sum_arr($kibb,'NB_Awal');
	$kibb_tAkm_Susut    = sum_arr($kibb,'Akm_Susut');
	$kibb_tKapitalisasi = sum_arr($kibb,'Kapitalisasi');
	$kibb_tNB_Akhir     = sum_arr($kibb,'NB_Akhir');
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('02','1.3.2', 'PERALATAN DAN MESIN','', '', rp($kibb_tHarga), rp($kibb_tNB_Awal),rp($kibb_tAkm_Susut),rp($kibb_tKapitalisasi),rp($kibb_tNB_Akhir)));
	$pdf->SetFont('Times','','10');
	foreach ($kibb->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kodebarang = $kd1.'.'.$kd2;

		$pdf->Row(array($no,'', '',$kodebarang, $row->Nm_Aset2, rp($row->Harga), rp($row->NB_Awal),   rp($row->Akm_Susut),   rp($row->Kapitalisasi),  rp($row->NB_Akhir)));
		$no++;
	}

	/* KIB C */
	$no=1;
	$kibc_tHarga        = sum_arr($kibc,'Harga');
	$kibc_tNB_Awal      = sum_arr($kibc,'NB_Awal');
	$kibc_tAkm_Susut    = sum_arr($kibc,'Akm_Susut');
	$kibc_tKapitalisasi = sum_arr($kibc,'Kapitalisasi');
	$kibc_tNB_Akhir     = sum_arr($kibc,'NB_Akhir');
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('03','1.3.3', 'GEDUNG DAN BANGUNAN','', '', rp($kibc_tHarga), rp($kibc_tNB_Awal),rp($kibc_tAkm_Susut),rp($kibc_tKapitalisasi),rp($kibc_tNB_Akhir)));
	$pdf->SetFont('Times','','10');
	foreach ($kibc->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kodebarang = $kd1.'.'.$kd2;

		$pdf->Row(array($no,'', '',$kodebarang, $row->Nm_Aset2, rp($row->Harga), rp($row->NB_Awal),   rp($row->Akm_Susut),   rp($row->Kapitalisasi),  rp($row->NB_Akhir)));
		$no++;
	}

	/* KIB D */
	$no=1;
	$kibd_tHarga        = sum_arr($kibd,'Harga');
	$kibd_tNB_Awal      = sum_arr($kibd,'NB_Awal');
	$kibd_tAkm_Susut    = sum_arr($kibd,'Akm_Susut');
	$kibd_tKapitalisasi = sum_arr($kibd,'Kapitalisasi');
	$kibd_tNB_Akhir     = sum_arr($kibd,'NB_Akhir');
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('04','1.3.4', 'JALAN IRIGASI & JARINGAN','', '', rp($kibd_tHarga), rp($kibd_tNB_Awal),rp($kibd_tAkm_Susut),rp($kibd_tKapitalisasi),rp($kibd_tNB_Akhir)));
	$pdf->SetFont('Times','','10');
	foreach ($kibd->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kodebarang = $kd1.'.'.$kd2;

		$pdf->Row(array($no,'', '',$kodebarang, $row->Nm_Aset2, rp($row->Harga), rp($row->NB_Awal),   rp($row->Akm_Susut),   rp($row->Kapitalisasi),  rp($row->NB_Akhir)));
		$no++;
	}

	/* KIB E */
	$no=1;
	$kibe_tHarga        = sum_arr($kibe,'Harga');
	$kibe_tNB_Awal      = sum_arr($kibe,'NB_Awal');
	$kibe_tAkm_Susut    = sum_arr($kibe,'Akm_Susut');
	$kibe_tKapitalisasi = sum_arr($kibe,'Kapitalisasi');
	$kibe_tNB_Akhir     = sum_arr($kibe,'NB_Akhir');
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('05','1.3.5', 'ASET TETAP LAINNYA','', '', rp($kibe_tHarga), rp($kibe_tNB_Awal),rp($kibe_tAkm_Susut),rp($kibe_tKapitalisasi),rp($kibe_tNB_Akhir)));
	$pdf->SetFont('Times','','10');
	foreach ($kibe->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kodebarang = $kd1.'.'.$kd2;

		$pdf->Row(array($no,'', '',$kodebarang, $row->Nm_Aset2, rp($row->Harga), rp($row->NB_Awal),   rp($row->Akm_Susut),   rp($row->Kapitalisasi),  rp($row->NB_Akhir)));
		$no++;
	}

	$at_total_Harga        = $kibb_tHarga + $kibc_tHarga + $kibd_tHarga + $kibe_tHarga;
	$at_total_NB_Awal      = $kibb_tNB_Awal + $kibc_tNB_Awal + $kibd_tNB_Awal + $kibe_tNB_Awal;
	$at_total_Akm_Susut    = $kibb_tAkm_Susut + $kibc_tAkm_Susut + $kibd_tAkm_Susut + $kibe_tAkm_Susut;
	$at_total_Kapitalisasi = $kibb_tKapitalisasi + $kibc_tKapitalisasi + $kibd_tKapitalisasi + $kibe_tKapitalisasi;
	$at_total_NB_Akhir     = $kibb_tNB_Akhir + $kibc_tNB_Akhir + $kibd_tNB_Akhir + $kibe_tNB_Akhir;
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('','', 'TOTAL ASET TETAP','', '', rp($at_total_Harga), rp($at_total_NB_Awal),rp($at_total_Akm_Susut),rp($at_total_Kapitalisasi),rp($at_total_NB_Akhir)));

	$pdf->Kosong();
	$pdf->SetFont('Times','B','10');
	
	$lainnya_tHarga        = $kib_lainnya->Harga;
	$lainnya_tNB_Awal      = $kib_lainnya->NB_Awal;
	$lainnya_tAkm_Susut    = $kib_lainnya->Akm_Susut;
	$lainnya_tKapitalisasi = $kib_lainnya->Kapitalisasi;
	$lainnya_tNB_Akhir     = $kib_lainnya->NB_Akhir;
	$pdf->Row(array('07','1.3.6', 'ASET LAINNYA','', '', rp($lainnya_tHarga), rp($lainnya_tNB_Awal),rp($lainnya_tAkm_Susut),rp($lainnya_tKapitalisasi),rp($lainnya_tNB_Akhir)));
	$pdf->SetFont('Times','','10');
	$pdf->Row(array("1",'', '','07.19', "Aset Tak Berwujud", rp($kib_lainnya->Harga), rp($kib_lainnya->NB_Awal),   rp($kib_lainnya->Akm_Susut),   rp($kib_lainnya->Kapitalisasi),  rp($kib_lainnya->NB_Akhir)));

	$total_Harga          = $at_total_Harga + $kib_lainnya->Harga;
	$total_NB_Awal        = $at_total_NB_Awal + $kib_lainnya->NB_Awal;
	$total_Akm_Susut      = $at_total_Akm_Susut + $kib_lainnya->Akm_Susut;
	$total_Kapitalisasi   = $at_total_Kapitalisasi + $kib_lainnya->Kapitalisasi;
	$total_NB_Akhir       = $at_total_NB_Akhir + $kib_lainnya->NB_Akhir;
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('','', 'T O T A L','', '', rp($total_Harga), rp($total_NB_Awal),rp($total_Akm_Susut),rp($total_Kapitalisasi),rp($total_NB_Akhir)));

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}

	$pdf->Output("SIMDO_Rekap_Penyusutan.pdf","I");
?>