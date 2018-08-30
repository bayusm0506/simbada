<?php

	$this->load->library('pdf_mc_table_rkodebarang');
	
	$pdf=new PDF_MC_Table_rkodebarang('P','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(280,5,"REKAPITULASI BARANG MILIK DAERAH MENURUT KODE BARANG \n KABUPATEN PROVINSI SUMATERA UTARA",0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(280,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(280,5,$periode,0,'C');
	$pdf->Ln(20);	

	$kode_lokasi = KODE_LOKASI;
	$pdf->UPBTitle2('','','','',$kode_lokasi);


	$pdf->Ln();	
	$tgl=date('Y-m-d');
	
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(15,50,85,30,50,50));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6"));
	
	/* KIB A */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkiba		= number_format($totala->Harga,0,",",".");
	$pdf->Row(array("1", "01", "Tanah", $totala->Jumlah,$totalkiba, ''));
	
	if ($jumlaha > 0){
		foreach ($kiba->result() as $row){
			$kd_aset1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd_aset2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd_aset3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd_aset4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd_aset5 = sprintf ("%02u", $row->Kd_Aset5);
			$harga		= number_format($row->Harga,0,",",".");
			$pdf->SetFont('Times','','10');	
			$pdf->SetWidthsx(array(15,10,10,10,10,10,85,30,50,50));
			$pdf->RowData(array("1", $kd_aset1, $kd_aset2, $kd_aset3,$kd_aset4, $kd_aset5,$row->Nm_Aset5,$row->Jumlah,$harga,ucwords(strtolower($row->Nm_UPB))));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	/* end KIB A */
	
	/* KIB B */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibb		= number_format($totalb->Harga,0,",",".");
	$pdf->Row(array("1", "01", "Peralatan dan Mesin", $totalb->Jumlah,$totalkibb, ''));	
	if ($jumlahb > 0){
		foreach ($kibb->result() as $row){
			$kd_aset1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd_aset2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd_aset3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd_aset4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd_aset5 = sprintf ("%02u", $row->Kd_Aset5);
			$harga		= number_format($row->Harga,0,",",".");
			$pdf->SetFont('Times','','10');	
			$pdf->SetWidthsx(array(15,10,10,10,10,10,85,30,50,50));
			$pdf->RowData(array("1", $kd_aset1, $kd_aset2, $kd_aset3,$kd_aset4, $kd_aset5,$row->Nm_Aset5,$row->Jumlah,$harga,ucwords(strtolower($row->Nm_UPB))));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	/* end KIB B */
	
	/* KIB C */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibc		= number_format($totalc->Harga,0,",",".");
	$pdf->Row(array("1", "03", "Gedung & Bangunan", $totalc->Jumlah,$totalkibc, ''));
	
	if ($jumlahc > 0){
		foreach ($kibc->result() as $row){
			$kd_aset1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd_aset2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd_aset3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd_aset4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd_aset5 = sprintf ("%02u", $row->Kd_Aset5);
			$harga		= number_format($row->Harga,0,",",".");
			$pdf->SetFont('Times','','10');	
			$pdf->SetWidthsx(array(15,10,10,10,10,10,85,30,50,50));
			$pdf->RowData(array("1", $kd_aset1, $kd_aset2, $kd_aset3,$kd_aset4, $kd_aset5,$row->Nm_Aset5,$row->Jumlah,$harga,ucwords(strtolower($row->Nm_UPB))));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	/* end KIB C */
	
	/* KIB D */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibd		= number_format($totald->Harga,0,",",".");
	$pdf->Row(array("1", "04", "Jalan, Irigasi & Jaringan", $totald->Jumlah,$totalkibd, ''));
	
	if ($jumlahd > 0){
		foreach ($kibd->result() as $row){
			$kd_aset1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd_aset2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd_aset3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd_aset4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd_aset5 = sprintf ("%02u", $row->Kd_Aset5);
			$harga		= number_format($row->Harga,0,",",".");
			$pdf->SetFont('Times','','10');	
			$pdf->SetWidthsx(array(15,10,10,10,10,10,85,30,50,50));
			$pdf->RowData(array("1", $kd_aset1, $kd_aset2, $kd_aset3,$kd_aset4, $kd_aset5,$row->Nm_Aset5,$row->Jumlah,$harga,ucwords(strtolower($row->Nm_UPB))));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	/* end KIB D */
	
	/* KIB E */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibe		= number_format($totale->Harga,0,",",".");
	$pdf->Row(array("1", "05", "Aset Tetap Lainya", $totale->Jumlah,$totalkibe, ''));
	
	if ($jumlahe > 0){
		foreach ($kibe->result() as $row){
			$kd_aset1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd_aset2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd_aset3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd_aset4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd_aset5 = sprintf ("%02u", $row->Kd_Aset5);
			$harga		= number_format($row->Harga,0,",",".");
			$pdf->SetFont('Times','','10');	
			$pdf->SetWidthsx(array(15,10,10,10,10,10,85,30,50,50));
			$pdf->RowData(array("1", $kd_aset1, $kd_aset2, $kd_aset3,$kd_aset4, $kd_aset5,$row->Nm_Aset5,$row->Jumlah,$harga,ucwords(strtolower($row->Nm_UPB))));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	/* end KIB E */
	
	/* KIB F */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibf		= number_format($totalf->Harga,0,",",".");
	$pdf->Row(array("1", "06", "Konstruksi Dalam Pekerjaan", $totalf->Jumlah,$totalkibf, ''));
	
	if ($jumlahf > 0){
		foreach ($kibf->result() as $row){
			$kd_aset1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd_aset2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd_aset3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd_aset4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd_aset5 = sprintf ("%02u", $row->Kd_Aset5);
			$harga		= number_format($row->Harga,0,",",".");
			$pdf->SetFont('Times','','10');	
			$pdf->SetWidthsx(array(15,10,10,10,10,10,85,30,50,50));
			$pdf->RowData(array("1", $kd_aset1, $kd_aset2, $kd_aset3,$kd_aset4, $kd_aset5,$row->Nm_Aset5,$row->Jumlah,$harga,ucwords(strtolower($row->Nm_UPB))));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	/* end KIB F */
	
	
	$total_jumlah = $totala->Jumlah + $totalb->Jumlah + $totalc->Jumlah + $totald->Jumlah + $totale->Jumlah + $totalf->Jumlah;
	$harga_total		= number_format($total,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->SetWidths(array(15,50,85,30,50,50));
	$pdf->Row(array("", "T O T A L", "", $total_jumlah,$harga_total, ""));

	$pdf->ttd2('','','','','',$tanggal);

	$pdf->Output("SIMDO_RekapPerKodeBarang.pdf","I");
?>