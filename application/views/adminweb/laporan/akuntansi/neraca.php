<?php

	$this->load->library('PDF_MC_Neraca');
	
	$pdf=new PDF_MC_Neraca('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"NERACA ASET \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');

	$kb1 = sprintf ("%02u", $nama_upb['Kd_Bidang']);
	$kb2 = sprintf ("%02u", $nama_upb['Kd_Unit']);
	$kb3 = sprintf ("%02u", $nama_upb['Kd_Sub']);
	$kb4 = sprintf ("%02u", $nama_upb['Kd_UPB']);
	$kode_lokasi = KODE_LOKASI.'.'.$kb1.'.'.$kb2.'.'.$kb3.'.'.$kb4;
	$kode_lokasi2 = KODE_LOKASI;
	if($ttd_pengurus){
	$pdf->UPBTitle($nama_upb['Nm_bidang'],$nama_upb['Nm_unit'],$nama_upb['Nm_sub_unit'],$nama_upb['Nm_UPB'],$kode_lokasi);
	}else{
		$pdf->UPBTitle2($nama_upb['Nm_bidang'],$nama_upb['Nm_unit'],$nama_upb['Nm_sub_unit'],$nama_upb['Nm_UPB'],$kode_lokasi2);
	}

	$pdf->Ln();	
	$tgl=date('Y-m-d');
	
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(20,30,30,70,45,45,45,45,45,65));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8", "9", "10"));

	/* KIB A  */
	$no=1;

	$pdf->SetFont('Times','B','10');	
	$kiba_total_intra      = number_format($totala->intra,0,",",".");
	$kiba_total_ekstra     = number_format($totala->ekstra,0,",",".");
	$kiba_total_rb         = number_format($totala->RB,0,",",".");
	$kiba_total_Susut      = number_format($totala->Susut,0,",",".");
	$kiba_total_nb         = $totala->intra - $totala->RB - $totala->Susut;
	$kiba_total_nilai_buku = number_format($kiba_total_nb,0,",",".");
	$pdf->Row(array("1", "01", "", "TANAH",$kiba_total_intra,$kiba_total_ekstra, $kiba_total_rb, $kiba_total_Susut, $kiba_total_nilai_buku, ""));
	if ($jumlah_kiba > 0){
	foreach ($kiba->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$nb = $row->intra - $row->RB - $row->Susut;
		$pdf->SetFont('Times','','10');

		$intra  = number_format($row->intra,0,",",".");
		$ekstra = number_format($row->ekstra,0,",",".");
		$rb     = number_format($row->RB,0,",",".");
		$Susut  = number_format($row->Susut,0,",",".");

		$nilai_buku	= number_format($nb,0,",",".");

		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2, $intra,$ekstra, $rb, $Susut, $nilai_buku, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB B  */
	$no=1;

	$pdf->SetFont('Times','B','10');	
	$kibb_total_intra      = number_format($totalb->intra,0,",",".");
	$kibb_total_ekstra     = number_format($totalb->ekstra,0,",",".");
	$kibb_total_rb         = number_format($totalb->RB,0,",",".");
	$kibb_total_Susut      = number_format($totalb->Susut,0,",",".");
	$kibb_total_nb         = $totalb->intra - $totalb->RB - $totalb->Susut;
	$kibb_total_nilai_buku = number_format($kibb_total_nb,0,",",".");
	$pdf->Row(array("2", "02", "", "PERALATAN & MESIN",$kibb_total_intra,$kibb_total_ekstra, $kibb_total_rb, $kibb_total_Susut, $kibb_total_nilai_buku, ""));
	if ($jumlah_kibb > 0){
	foreach ($kibb->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$nb = $row->intra - $row->RB - $row->Susut;
		$pdf->SetFont('Times','','10');

		$intra  = number_format($row->intra,0,",",".");
		$ekstra = number_format($row->ekstra,0,",",".");
		$rb     = number_format($row->RB,0,",",".");
		$Susut  = number_format($row->Susut,0,",",".");

		$nilai_buku	= number_format($nb,0,",",".");

		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2, $intra,$ekstra, $rb, $Susut, $nilai_buku, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* KIB C  */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$kibc_total_intra      = number_format($totalc->intra,0,",",".");
	$kibc_total_ekstra     = number_format($totalc->ekstra,0,",",".");
	$kibc_total_rb         = number_format($totalc->RB,0,",",".");
	$kibc_total_Susut      = number_format($totalc->Susut,0,",",".");
	$kibc_total_nb         = $totalc->intra - $totalc->RB - $totalc->Susut;
	$kibc_total_nilai_buku = number_format($kibc_total_nb,0,",",".");
	$pdf->Row(array("3", "03", "", "GEDUNG & BANGUNAN", $kibc_total_intra,$kibc_total_ekstra, $kibc_total_rb, $kibc_total_Susut, $kibc_total_nilai_buku, ""));
	if ($jumlah_kibc > 0){
	foreach ($kibc->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$nb = $row->intra - $row->RB - $row->Susut;
		$pdf->SetFont('Times','','10');

		$intra  = number_format($row->intra,0,",",".");
		$ekstra = number_format($row->ekstra,0,",",".");
		$rb     = number_format($row->RB,0,",",".");
		$Susut  = number_format($row->Susut,0,",",".");

		$nilai_buku	= number_format($nb,0,",",".");

		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2, $intra,$ekstra, $rb, $Susut, $nilai_buku, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}


	/* KIB D  */
	$no=1;

	$pdf->SetFont('Times','B','10');	
	$kibd_total_intra      = number_format($totald->intra,0,",",".");
	$kibd_total_ekstra     = number_format($totald->ekstra,0,",",".");
	$kibd_total_rb         = number_format($totald->RB,0,",",".");
	$kibd_total_Susut      = number_format($totald->Susut,0,",",".");
	$kibd_total_nb         = $totald->intra - $totald->RB - $totald->Susut;
	$kibd_total_nilai_buku = number_format($kibd_total_nb,0,",",".");
	$pdf->Row(array("4", "04", "", "JALAN, IRIGASI & JARINGAN", $kibd_total_intra,$kibd_total_ekstra, $kibd_total_rb, $kibd_total_Susut, $kibd_total_nilai_buku, ""));
	if ($jumlah_kibd > 0){
	foreach ($kibd->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$nb = $row->intra - $row->RB - $row->Susut;
		$pdf->SetFont('Times','','10');

		$intra  = number_format($row->intra,0,",",".");
		$ekstra = number_format($row->ekstra,0,",",".");
		$rb     = number_format($row->RB,0,",",".");
		$Susut  = number_format($row->Susut,0,",",".");

		$nilai_buku	= number_format($nb,0,",",".");

		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2, $intra,$ekstra, $rb, $Susut, $nilai_buku, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* KIB E  */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$kibe_total_intra      = number_format($totale->intra,0,",",".");
	$kibe_total_ekstra     = number_format($totale->ekstra,0,",",".");
	$kibe_total_rb         = number_format($totale->RB,0,",",".");
	$kibe_total_Susut      = number_format($totale->Susut,0,",",".");
	$kibe_total_nb         = $totale->intra - $totale->RB - $totale->Susut;
	$kibe_total_nilai_buku = number_format($kibe_total_nb,0,",",".");
	$pdf->Row(array("5", "05", "", "ASET TETAP LAINNYA", $kibe_total_intra,$kibe_total_ekstra, $kibe_total_rb, $kibe_total_Susut, $kibe_total_nilai_buku, ""));
	if ($jumlah_kibe > 0){
	foreach ($kibe->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$nb = $row->intra - $row->RB - $row->Susut;
		$pdf->SetFont('Times','','10');

		$intra  = number_format($row->intra,0,",",".");
		$ekstra = number_format($row->ekstra,0,",",".");
		$rb     = number_format($row->RB,0,",",".");
		$Susut  = number_format($row->Susut,0,",",".");

		$nilai_buku	= number_format($nb,0,",",".");

		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2, $intra,$ekstra, $rb, $Susut, $nilai_buku, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* KIB F  */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$kibf_total_intra      = number_format($totalf->intra,0,",",".");
	$kibf_total_ekstra     = number_format($totalf->ekstra,0,",",".");
	$kibf_total_rb         = number_format($totalf->RB,0,",",".");
	$kibf_total_Susut      = number_format($totalf->Susut,0,",",".");
	$kibf_total_nb         = $totalf->intra - $totalf->RB - $totalf->Susut;
	$kibf_total_nilai_buku = number_format($kibf_total_nb,0,",",".");
	$pdf->Row(array("6", "06", "", "KONSTRUKSI DALAM PEKERJAAN", $kibf_total_intra,$kibf_total_ekstra, $kibf_total_rb, $kibf_total_Susut, $kibf_total_nilai_buku, ""));
	if ($jumlah_kibf > 0){
	foreach ($kibf->result() as $row){
		$kd2 		= sprintf ("%02u", '06');

		$nb = $row->intra - $row->RB - $row->Susut;
		$pdf->SetFont('Times','','10');

		$intra  = number_format($row->intra,0,",",".");
		$ekstra = number_format($row->ekstra,0,",",".");
		$rb     = number_format($row->RB,0,",",".");
		$Susut  = number_format($row->Susut,0,",",".");

		$nilai_buku	= number_format($nb,0,",",".");

		$pdf->Row(array("", "", $kd2, "Konstruksi Dalam Pekerjaan", $intra,$ekstra, $rb, $Susut, $nilai_buku, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	$total_intra      =  number_format($totala->intra + $totalb->intra + $totalc->intra + $totald->intra + $totale->intra + $totalf->intra,0,",",".");
	$total_ekstra     =  number_format($totala->ekstra + $totalb->ekstra + $totalc->ekstra + $totald->ekstra + $totale->ekstra + $totalf->ekstra,0,",",".");
	$total_rb         =  number_format($totala->RB + $totalb->RB + $totalc->RB + $totald->RB + $totale->RB + $totalf->RB,0,",",".");
	$total_Susut      =  number_format($totala->Susut + $totalb->Susut + $totalc->Susut + $totald->Susut + $totale->Susut + $totalf->Susut,0,",",".");
	$total_nilai_buku =  number_format($kiba_total_nb + $kibb_total_nb + $kibc_total_nb + $kibd_total_nb + $kibe_total_nb + $kibf_total_nb,0,",",".");

	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", " T O T A L ", "", "",$total_intra, $total_ekstra, $total_rb, $total_Susut, $total_nilai_buku, ""));

	if($ttd_pengurus){
		$pdf->ttd($ta_upb->Nm_Pimpinan,$ta_upb->Nip_Pimpinan,$ta_upb->Jbt_Pimpinan,$ta_upb->Nm_Pengurus,$ta_upb->Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($ta_upb->Nm_Sekda,$ta_upb->Nip_Sekda,$ta_upb->Jbt_Sekda,$ta_upb->Nm_Ka_Keu,$ta_upb->Nip_Ka_Keu,$tanggal);
	}
	$pdf->Output("SIMDO_Neraca.pdf","I");
?>