<?php
	$this->load->library('akuntansi/PDF_MC_Table_rekon_aset');

	$pdf=new PDF_MC_Table_rekon_aset('L','mm','f4');
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
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('02','1.3.2', 'PERALATAN DAN MESIN','', '', '', '','','',''));
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
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('03','1.3.3', 'GEDUNG DAN BANGUNAN','', '', '', '','','',''));
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
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('04','1.3.4', 'JALAN IRIGASI & JARINGAN','', '', '', '','','',''));
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
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('05','1.3.5', 'ASET TETAP LAINNYA','', '', '', '','','',''));
	$pdf->SetFont('Times','','10');
	foreach ($kibe->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kodebarang = $kd1.'.'.$kd2;

		$pdf->Row(array($no,'', '',$kodebarang, $row->Nm_Aset2, rp($row->Harga), rp($row->NB_Awal),   rp($row->Akm_Susut),   rp($row->Kapitalisasi),  rp($row->NB_Akhir)));
		$no++;
	}

	// $pdf->Akm_Susut("AKUMULASI PENYUSUTAN ASET LAINYA",'xxx');

	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('07','1.3.6', 'ASET LAINNYA','', '', '', '','','',''));
	$pdf->SetFont('Times','','10');
	$pdf->Row(array("1",'', '','07.19', "Aset Tak Berwujud", rp($kib_lainnya->Harga), rp($kib_lainnya->NB_Awal),   rp($kib_lainnya->Akm_Susut),   rp($kib_lainnya->Kapitalisasi),  rp($kib_lainnya->NB_Akhir)));

	$pdf->SetFont('Times','B','10');
	// $pdf->Row(array("", "", "	", "TOTAL KIB B","", "", "", "","", 321, "","", "", 321,"", 321, ""));

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}

	$pdf->Output("SIMDO_Rekap_Penyusutan.pdf","I");
?>