<?php

	$this->load->library('akuntansi/PDF_MC_rekap_neraca');
	
	$pdf=new PDF_MC_rekap_neraca('P','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(280,5,"NERACA ASET \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(280,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(280,5,$periode,0,'C');
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
	$pdf->SetWidths(array(15,30,30,150,58));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5"));

	// $total_x = sum_arr($kibd,'NB_Akhir');
	// print_r($total_x); exit();
	
	/* KIB A  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_kiba = sum_arr($kiba,'NB_Akhir');
	$pdf->Row(array("1", "01", "", "TANAH",rp($total_kiba)));
	foreach ($kiba->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$pdf->SetFont('Times','','10');

		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2, rp($row->NB_Akhir)));
		$no++;
	}
	
	/* KIB B  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_kibb = sum_arr($kibb,'NB_Akhir');	
	$pdf->Row(array("2", "02", "", "PERALATAN & MESIN",rp($total_kibb)));
	foreach ($kibb->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$pdf->SetFont('Times','','10');

		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2, rp($row->NB_Akhir)));
		$no++;
	}

	/* KIB C  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_kibc = sum_arr($kibc,'NB_Akhir');	
	$pdf->Row(array("3", "03", "", "GEDUNG & BANGUNAN", rp($total_kibc)));
	foreach ($kibc->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$pdf->SetFont('Times','','10');

		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2, rp($row->NB_Akhir)));

		$no++;
	}

	/* KIB D  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_kibd = sum_arr($kibd,'NB_Akhir');	
	$pdf->Row(array("4", "04", "", "JALAN, IRIGASI & JARINGAN", rp($total_kibd)));
	foreach ($kibd->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$pdf->SetFont('Times','','10');

		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2, rp($row->NB_Akhir)));
		$no++;
	}

	/* KIB E  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_kibe = sum_arr($kibe,'NB_Akhir');
	$pdf->Row(array("5", "05", "", "ASET TETAP LAINNYA", rp($total_kibe)));
	foreach ($kibe->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$pdf->SetFont('Times','','10');

		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2, rp($row->NB_Akhir)));
		$no++;
	}

	/* KIB F  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_kibf = sum_arr($kibf,'NB_Akhir');
	$pdf->Row(array("6", "06", "", "KONSTRUKSI DALAM PEKERJAAN", rp($total_kibf)));
	foreach ($kibf->result() as $row){
		$kd2 		= sprintf ("%02u", '21');

		$pdf->SetFont('Times','','10');

		$pdf->Row(array("", "", $kd2, "Konstruksi Dalam Pekerjaan", rp($row->NB_Akhir)));
		$no++;
	}

	/* AKUMULASI PENYUSUTAN */
	$akm_kibb = sum_arr($kibb,'Akm_Susut');
	$akm_kibc = sum_arr($kibc,'Akm_Susut');
	$akm_kibd = sum_arr($kibd,'Akm_Susut');
	$akm_kibe = sum_arr($kibe,'Akm_Susut');

	$total_akm = $akm_kibb + $akm_kibc + $akm_kibd + $akm_kibe;
	// $total_akm = $akm_kibb;
	// print_r($total_akm); exit();
	$pdf->Akumulasi("AKUMULASI PENYUSUTAN ASET TETAP",rp($total_akm));

	$total_at = ($total_kiba + $total_kibb + $total_kibc + $total_kibd + $total_kibe + $total_kibf) - $total_akm;

	$pdf->Akumulasi("TOTAL ASET TETAP",rp($total_at));

	/* Aset Lainya  */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	
	$RB_kibb = sum_arr($kibb,'RB');
	$RB_kibc = sum_arr($kibc,'RB');
	$RB_kibd = sum_arr($kibd,'RB');
	$RB_kibe = sum_arr($kibe,'RB');
	$total_RB = $RB_kibb + $RB_kibc + $RB_kibd + $RB_kibe;
	// $total_RB = $RB_kibb;
	// print_r($kib_lainnya); exit();

	/* KIB LAINYA - Aset Non Operasional */
	$NonOP_kiba = sum_arr($kiba,'KIB_A_Non_Operasional');
	$NonOP_kibb = sum_arr($kibb,'KIB_B_Non_Operasional');
	$NonOP_kibc = sum_arr($kibc,'KIB_C_Non_Operasional');
	$NonOP_kibd = sum_arr($kibd,'KIB_D_Non_Operasional');
	$NonOP_kibe = sum_arr($kibe,'KIB_E_Non_Operasional');
	
	// print_r($kibc); exit();
	$total_NonOP = $NonOP_kiba + $NonOP_kibb + $NonOP_kibc + $NonOP_kibd + $NonOP_kibe;

	$total_lainnya = $total_RB + $kib_lainnya->NB_Akhir + $total_NonOP; 
	$pdf->Row(array("7", "07", "", "ASET LAINNYA", rp($total_lainnya)));
	// print_r($total_lainnya); exit();
	$pdf->SetFont('Times','','10');
	/* KIB LAINYA - Aset lainnya  */
	$pdf->Row(array("", "", "20", "Aset Lainnya", 0));
	/* KIB LAINYA - Aset Rusak Berat */
	$pdf->Row(array("", "", "21", "Rusak Berat", rp($total_RB)));

	$pdf->Row(array("", "", "22", "Aset Non Operasional (Aset yang dimanfaatkan pihak lain)", rp($total_NonOP)));
	
	/* KIB LAINYA - Aset Renovasi  */
	$pdf->Row(array("", "", "23", "Aset yang dikerjasamakan dengan pihak ke 3", 0));
	/* KIB LAINYA - Aset tak berwujud  */
	$pdf->Row(array("", "", "24", "Aset Tak Berwujud", rp($kib_lainnya->NB_Akhir)));

	// $Akm_NonOP_kibb = sum_arr($kibb,'Akm_Susut_Non_Operasional');
	// $Akm_NonOP_kibc = sum_arr($kibc,'Akm_Susut_Non_Operasional');
	// $Akm_NonOP_kibd = sum_arr($kibd,'Akm_Susut_Non_Operasional');
	// $Akm_NonOP_kibe = sum_arr($kibe,'Akm_Susut_Non_Operasional');
	$akm_aset_lain = $kib_lainnya->Akm_Susut;
	$pdf->Akumulasi("AKUMULASI AMORTISASI",rp($akm_aset_lain));

	$total_al = $total_lainnya - $akm_aset_lain;


	$pdf->Akumulasi("TOTAL ASET LAINYA",rp($total_al));

	$pdf->kosong("");

	$pdf->Akumulasi("TOTAL ASET",rp($total_at+$total_al));
	
	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}
	$pdf->Output("SIMDO_Neraca.pdf","I");
?>