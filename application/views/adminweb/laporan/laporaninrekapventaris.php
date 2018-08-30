<?php

	$this->load->library('PDF_MC_Table_rinventaris');
	
	$pdf=new PDF_MC_Table_rinventaris('P','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(280,5,"REKAPITULASI BUKU INVENTARIS \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(280,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(280,5,$periode,0,'C');
	$pdf->Ln(20);	

	$kb1 = sprintf ("%02u", $nama_upb->Kd_Bidang);
	$kb2 = sprintf ("%02u", $nama_upb->Kd_Unit);
	$kb3 = sprintf ("%02u", $nama_upb->Kd_Sub);
	$kb4 = sprintf ("%02u", $nama_upb->Kd_UPB);
	$kode_lokasi = KODE_LOKASI.'.'.$kb1.'.'.$kb2.'.'.$kb3.'.'.$kb4;
	$pdf->UPBTitle($nama_upb->Nm_bidang,$nama_upb->Nm_unit,$nama_upb->Nm_sub_unit,$nama_upb->Nm_UPB,$kode_lokasi);


	$pdf->Ln();	
	$tgl=date('Y-m-d');
	
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(15,30,30,80,30,45,50));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7"));
	
	/* KIB A */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$rev_totala = ($totala->Harga + $totala->Kapitalisasi + $totala->Koreksi ) - $totala->Penghapusan;
	$totalkiba		= number_format($rev_totala,0,",",".");
	$pdf->Row(array("1", "01", "", "TANAH",$totala->Jumlah, $totalkiba, ""));
	if ($jumlaha > 0){
	foreach ($kiba->result() as $row){
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$harga		= number_format($row->Harga + $row->Tambah_Kapitalisasi - $row->Kurang_Penghapusan,0,",",".");
		if ($harga == 0){
			$harga 	= '-';
			$jumlah = '-';
		}else{
			$jumlah = $row->Jumlah;
		}
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2,$jumlah, $harga, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB B  */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibb		= number_format($totalb->Total,0,",",".");
	$pdf->Row(array("2", "02", "", "PERALATAN & MESIN",$totalb->Jumlah, $totalkibb, ""));
	if ($jumlahb > 0){
	foreach ($kibb->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);
		$harga		= number_format($row->Harga + $row->Tambah_Kapitalisasi - $row->Kurang_Penghapusan,0,",",".");
		if ($harga == 0){
			$harga 	= '-';
			$jumlah = '-';
		}else{
			$jumlah = $row->Jumlah;
		}
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2,$jumlah, $harga, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	} 
	
	/* KIB C  */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibc		= number_format($totalc->Total,0,",",".");
	$pdf->Row(array("3", "03", "", "GEDUNG & BANGUNAN",$totalc->Jumlah, $totalkibc, ""));
	if ($jumlahc > 0){
	foreach ($kibc->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);
		$harga		= number_format($row->Harga + $row->Tambah_Kapitalisasi - $row->Kurang_Penghapusan,0,",",".");
		if ($harga == 0){
			$harga 	= '-';
			$jumlah = '-';
		}else{
			$jumlah = $row->Jumlah;
		}
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2,$jumlah, $harga, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	} 
	
	/* KIB D  */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibd		= number_format($totald->Harga + $totald->Tambah_Kapitalisasi,0,",",".");
	$pdf->Row(array("4", "04", "", "JALAN, IRIGASI & JARINGAN",$totald->Jumlah, $totalkibd, ""));
	if ($jumlahd > 0){
	foreach ($kibd->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);
		$harga		= number_format($row->Harga + $row->Tambah_Kapitalisasi,0,",",".");
		if ($harga == 0){
			$harga 	= '-';
			$jumlah = '-';
		}else{
			$jumlah = $row->Jumlah;
		}
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2,$jumlah, $harga, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	} 
	
	/* KIB E  */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibe		= number_format($totale->Harga,0,",",".");
	$pdf->Row(array("5", "05", "", "ASET TETAP LAINNYA",$totale->Jumlah, $totalkibe, ""));
	if ($jumlahe > 0){
	foreach ($kibe->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);
		$harga		= number_format($row->Harga,0,",",".");
		if ($harga == 0){
			$harga 	= '-';
			$jumlah = '-';
		}else{
			$jumlah = $row->Jumlah;
		}
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2,$jumlah, $harga, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	} 
	
	/* KIB F  */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibf		= number_format($totalf->Harga,0,",",".");
	$pdf->Row(array("6", "06", "", "KONSTRUKSI DALAM PENGERJAAN",$totalf->Jumlah, $totalkibf, ""));
	if ($jumlahf > 0){
	foreach ($kibf->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);
		$harga		= number_format($row->Harga,0,",",".");
		if ($harga == 0){
			$harga 	= '-';
			$jumlah = '-';
		}else{
			$jumlah = $row->Jumlah;
		}
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2,$jumlah, $harga, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* KIB LAINYA  */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$total_lain		= number_format($total_lainnya->Harga,0,",",".");
	$pdf->Row(array("7", "07", "", "ASET LAINYA",$total_lainnya->Jumlah, $total_lain, ""));
	if ($jumlah_lainnya > 0){
	foreach ($kib_lainnya->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);
		$harga		= number_format($row->Harga,0,",",".");
		if ($harga == 0){
			$harga 	= '-';
			$jumlah = '-';
		}else{
			$jumlah = $row->Jumlah;
		}
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2,$jumlah, $harga, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	} 


	$total_jumlah = $totala->Jumlah + $totalb->Jumlah + $totalc->Jumlah + $totald->Jumlah + $totale->Jumlah + $totalf->Jumlah + $total_lainnya->Jumlah;
	$total 		  = $rev_totala + $totalb->Total + $totalc->Total + $totald->Total + $totale->Harga + $totalf->Harga + $total_lainnya->Harga;
	$harga_total		= number_format($total,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", " T O T A L ", "", "",$total_jumlah, $harga_total, ""));

	$pdf->ttd($ta_upb->Nm_Pimpinan,$ta_upb->Nip_Pimpinan,$ta_upb->Jbt_Pimpinan,$ta_upb->Nm_Pengurus,$ta_upb->Nip_Pengurus,$tanggal);

	$pdf->Output("SIMDO_RekapInventaris.pdf","I");
?>