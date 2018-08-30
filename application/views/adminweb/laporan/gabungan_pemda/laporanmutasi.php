<?php

	$this->load->library('PDF_MC_Table_mutasi');
	
	$pdf=new PDF_MC_Table_mutasi('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"LAPORAN MUTASI BARANG \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');

	$kode_lokasi = KODE_LOKASI;
	$pdf->UPBTitle2($nama_upb->Nm_bidang,$nama_upb->Nm_unit,$nama_upb->Nm_sub_unit,$nama_upb->Nm_UPB,$kode_lokasi);


	$pdf->Ln();	
	$tgl=date('Y-m-d');
	
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(10,25,15,50,20,20,20,20,20,20,20,15,15,20,15,20,15,30,15,35,20));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14", "15", "16", "17", "18", "19", "20", "21"));
	
	/* KIB A */
	$no=1;
	$pdf->SetFont('Times','','10');	
	$totalkiba		= number_format($totala->Harga,0,",",".");
	$pdf->Row(array("01", "", "", "TANAH","", "", "", "","", "", "", "","", "", "", "", $totala->Jumlah,$totalkiba,$totala->Jumlah, $totalkiba, ""));
	if ($jumlaha > 0){
	foreach ($kiba->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;

		$min_register = sprintf ("%04u", $row->min_register);
		$max_register = sprintf ("%04u", $row->max_register);
		if ($row->jumlah_register > 1){
			$register = $min_register." s/d ".$max_register;
		}else{
			$register = $min_register;
		}
		$harga		= number_format($row->Harga,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", $row->Sertifikat_Nomor, "-", $row->Asal_usul,$row->Tahun, $row->Luas_M2,
		 "M2", "-","-", "-", "15", "16", $row->Jumlah, $harga, $row->Jumlah, $harga, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	
	/* KIB B */
	$no=1;
	$pdf->SetFont('Times','','10');
	$totalkibb		= number_format($totalb->Harga,0,",",".");	
	$pdf->Row(array("02", "", "", "PERALATAN DAN MESIN","", "", "", "","", "", "", "","", "", "", "", $totalb->Jumlah,$totalkibb,$totalb->Jumlah, $totalkibb, ""));
	if ($jumlah > 0){
	foreach ($kibb->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;

		$min_register = sprintf ("%04u", $row->min_register);
		$max_register = sprintf ("%04u", $row->max_register);
		if ($row->jumlah_register > 1){
			$register = $min_register." s/d ".$max_register;
		}else{
			$register = $min_register;
		}

		if (empty($row->Merk) AND empty($row->Type)){
			$merk = '-';
		}else{
			$merk = $row->Merk.' / '.$row->Type;
		}
		
		if ($row->Kondisi == 1){
			$kondisi = 'B';
		}elseif ($row->Kondisi == 2){
			$kondisi = 'KB';
		}else{
			$kondisi = 'RB';
		}
		
		$harga		= number_format($row->Harga,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$merk, "-", $row->Bahan,  $row->Asal_usul,$row->Tahun, $row->CC, '',
		$kondisi,"-", "-", "-", "-",$row->Jumlah, $harga, $row->Jumlah, $harga, $row->Nm_UPB));		
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	
	/* KIB C */
	$no=1;
	$pdf->SetFont('Times','','10');	
	$totalkibc		= number_format($totalc->Harga,0,",",".");	
	$pdf->Row(array("03", "", "", "GEDUNG DAN BANGUNAN","", "", "", "","", "", "", "","", "", "", "", $totalc->Jumlah,$totalkibc,$totalc->Jumlah, $totalkibc, ""));
	if ($jumlahc > 0){
	foreach ($kibc->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;

		$min_register = sprintf ("%04u", $row->min_register);
		$max_register = sprintf ("%04u", $row->max_register);
		if ($row->jumlah_register > 1){
			$register = $min_register." s/d ".$max_register;
		}else{
			$register = $min_register;
		}
		$harga		= number_format($row->Harga,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", $row->Dokumen_Nomor, "-", $row->Asal_usul,$row->Tahun, $row->Luas_Lantai,
		 "M2", "-","-", "-", "-", "-", $row->Jumlah, $harga, $row->Jumlah, $harga, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB D */
	$no=1;
	$pdf->SetFont('Times','','10');	
	$totalkibd		= number_format($totald->Harga,0,",",".");	
	$pdf->Row(array("04", "", "", "JALAN, IRIGASI DAN JARINGAN","", "", "", "","", "", "", "","", "", "", "",$totald->Jumlah,$totalkibd,$totald->Jumlah, $totalkibd, ""));
	if ($jumlahd > 0){
	foreach ($kibd->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
		
		$min_register = sprintf ("%04u", $row->min_register);
		$max_register = sprintf ("%04u", $row->max_register);
		if ($row->jumlah_register > 1){
			$register = $min_register." s/d ".$max_register;
		}else{
			$register = $min_register;
		}
		$harga		= number_format($row->Harga,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", "", "-", $row->Asal_usul,$row->Tahun, $row->Luas,
		 "M2", "-","-", "-", "15", "16", $row->Jumlah, $harga, $row->Jumlah, $harga, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB E */
	$no=1;
	$pdf->SetFont('Times','','10');	
	$totalkibe		= number_format($totale->Harga,0,",",".");	
	$pdf->Row(array("05", "", "", "ASET TETAP LAINYA","", "", "", "","", "", "", "","", "", "", "", $totale->Jumlah,$totalkibe,$totale->Jumlah, $totalkibe, ""));
	if ($jumlahe > 0){
	foreach ($kibe->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
		$min_register = sprintf ("%04u", $row->min_register);
		$max_register = sprintf ("%04u", $row->max_register);
		if ($row->jumlah_register > 1){
			$register = $min_register." s/d ".$max_register;
		}else{
			$register = $min_register;
		}
		$harga		= number_format($row->Harga,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", "", "-", $row->Asal_usul,$row->Tahun, '-',
		 "", "-","-", "-", "-", "-", $row->Jumlah, $harga, $row->Jumlah, $harga, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB F */
	$no=1;
	$pdf->SetFont('Times','','10');	
	$totalkibf		= number_format($totalf->Harga,0,",",".");	
	$pdf->Row(array("06", "", "", "KONSTRUKSI DALAM PENGERJAAN","", "", "", "","", "", "", "","", "", "", "",$totalf->Jumlah,$totalkibf,$totalf->Jumlah, $totalkibf, ""));
	if ($jumlahf > 0){
	foreach ($kibf->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
		$min_register = sprintf ("%04u", $row->min_register);
		$max_register = sprintf ("%04u", $row->max_register);
		if ($row->jumlah_register > 1){
			$register = $min_register." s/d ".$max_register;
		}else{
			$register = $min_register;
		}
		$harga		= number_format($row->Harga,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", "", "-", $row->Asal_usul,$row->Tahun, '-',
		 "", "-","-", "-", "-", "-", $row->Jumlah, $harga, $row->Jumlah, $harga, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	$harga_total		= number_format($total,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", "", "", "TOTAL ","", "", "", "","", "", "", "","", "", "", "", $jumlah, $harga_total,  $jumlah, $harga_total, ""));

	$pdf->ttd2('','','','','',$tanggal);

	$pdf->Output("SIMDO_Mutasi.pdf","I");
?>