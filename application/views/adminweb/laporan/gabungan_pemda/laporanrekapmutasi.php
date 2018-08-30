<?php

	$this->load->library('PDF_MC_Table_rekapmutasi');
	
	$pdf=new PDF_MC_Table_rekapmutasi('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"REKAPITULASI MUTASI BARANG \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');

	$kode_lokasi = KODE_LOKASI;
	$pdf->UPBTitle2($nama_upb->Nm_bidang,$nama_upb->Nm_unit,$nama_upb->Nm_sub_unit,$nama_upb->Nm_UPB,$kode_lokasi);

	$pdf->Ln();	
	$tgl=date('Y-m-d');
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(15,115,25,45,25,45,25,45,25,45,30));
	$pdf->Rowheader($this->session->userdata('awal'),$this->session->userdata('akhir'));
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11"));
	/* KIB A */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkiba		= number_format($totala->Harga,0,",",".");
	if ($jumlaha > 0){
	foreach ($kiba->result() as $row){
		$kd2 			= sprintf ("%02u", $row->Kd_Aset2);
		$harga_awal		= number_format($row->Harga_awal,0,",",".");
		$harga_tambah	= number_format($row->Harga_tambah,0,",",".");
		$harga_total	= number_format($row->Harga_awal + $row->Harga_tambah,0,",",".");
		
		if ($harga_total == 0){
			$jumlah = '0';
		}else{
			$jumlah = $row->Jumlah_awal + $row->Jumlah_tambah;
		}
		
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array($kd2, $row->Nm_Aset2, $row->Jumlah_awal,  $harga_awal,"0","0",$row->Jumlah_tambah,
		$harga_tambah, $jumlah, $harga_total,""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB B  */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibb		= number_format($totalb->Harga,0,",",".");
	if ($jumlahb > 0){
	foreach ($kibb->result() as $row){
		$kd2 			= sprintf ("%02u", $row->Kd_Aset2);
		$harga_awal		= number_format($row->Harga_awal,0,",",".");
		$harga_tambah	= number_format($row->Harga_tambah,0,",",".");
		$harga_total	= number_format($row->Harga_awal + $row->Harga_tambah,0,",",".");
		
		if ($harga_total == 0){
			$jumlah = '0';
		}else{
			$jumlah = $row->Jumlah_awal + $row->Jumlah_tambah;
		}
		
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array($kd2, $row->Nm_Aset2, $row->Jumlah_awal,  $harga_awal,"0","0",$row->Jumlah_tambah,
		$harga_tambah, $jumlah, $harga_total,""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	} 
	/* KIB C */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibc		= number_format($totala->Harga,0,",",".");
	if ($jumlahc > 0){
	foreach ($kibc->result() as $row){
		$kd2 			= sprintf ("%02u", $row->Kd_Aset2);
		$harga_awal		= number_format($row->Harga_awal,0,",",".");
		$harga_tambah	= number_format($row->Harga_tambah,0,",",".");
		$harga_total	= number_format($row->Harga_awal + $row->Harga_tambah,0,",",".");
		
		if ($harga_total == 0){
			$jumlah = '0';
		}else{
			$jumlah = $row->Jumlah_awal + $row->Jumlah_tambah;
		}
		
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array($kd2, $row->Nm_Aset2, $row->Jumlah_awal,  $harga_awal,"0","0",$row->Jumlah_tambah,
		$harga_tambah, $jumlah, $harga_total,""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	/* KIB D */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibd		= number_format($totala->Harga,0,",",".");
	if ($jumlahd > 0){
	foreach ($kibd->result() as $row){
		$kd2 			= sprintf ("%02u", $row->Kd_Aset2);
		$harga_awal		= number_format($row->Harga_awal,0,",",".");
		$harga_tambah	= number_format($row->Harga_tambah,0,",",".");
		$harga_total	= number_format($row->Harga_awal + $row->Harga_tambah,0,",",".");
		
		if ($harga_total == 0){
			$jumlah = '0';
		}else{
			$jumlah = $row->Jumlah_awal + $row->Jumlah_tambah;
		}
		
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array($kd2, $row->Nm_Aset2, $row->Jumlah_awal,  $harga_awal,"0","0",$row->Jumlah_tambah,
		$harga_tambah, $jumlah, $harga_total,""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	/* KIB E */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibe		= number_format($totala->Harga,0,",",".");
	if ($jumlahe > 0){
	foreach ($kibe->result() as $row){
		$kd2 			= sprintf ("%02u", $row->Kd_Aset2);
		$harga_awal		= number_format($row->Harga_awal,0,",",".");
		$harga_tambah	= number_format($row->Harga_tambah,0,",",".");
		$harga_total	= number_format($row->Harga_awal + $row->Harga_tambah,0,",",".");
		
		if ($harga_total == 0){
			$jumlah = '0';
		}else{
			$jumlah = $row->Jumlah_awal + $row->Jumlah_tambah;
		}
		
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array($kd2, $row->Nm_Aset2, $row->Jumlah_awal,  $harga_awal,"0","0",$row->Jumlah_tambah,
		$harga_tambah, $jumlah, $harga_total,""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	/* KIB F */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibf		= number_format($totala->Harga,0,",",".");
	if ($jumlahf > 0){
	foreach ($kibf->result() as $row){
		$kd2 			= sprintf ("%02u", $row->Kd_Aset2);
		$harga_awal		= number_format($row->Harga_awal,0,",",".");
		$harga_tambah	= number_format($row->Harga_tambah,0,",",".");
		$harga_total	= number_format($row->Harga_awal + $row->Harga_tambah,0,",",".");
		
		if ($harga_total == 0){
			$jumlah = '0';
		}else{
			$jumlah = $row->Jumlah_awal + $row->Jumlah_tambah;
		}
		
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array($kd2, 'KDP -'.$row->Nm_Aset2, $row->Jumlah_awal,  $harga_awal,"0","0",$row->Jumlah_tambah,
		$harga_tambah, $jumlah, $harga_total,""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	$tharga_awal		= number_format($tothargaawal,0,",",".");
	$tharga_tambah		= number_format($tothargatambah,0,",",".");
	$tharga_akhir		= number_format($tothargaakhir,0,",",".");
	$pdf->SetFont('Times','','10');
	$pdf->Row(array('#', "J U M L A H", $jumhargaawal,$tharga_awal,"0", "0", $jumhargatambah,  $tharga_tambah, $jumhargaakhir,$tharga_akhir, ""));

	$pdf->ttd2('','','','','',$tanggal);
	$pdf->Output("SIMDO_kiba.pdf","I");
?>