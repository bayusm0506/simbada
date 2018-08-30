<?php

	$this->load->library('PDF_MC_Table_rekapskpd');
	
	$pdf=new PDF_MC_Table_rekapskpd('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"REKAPITULASI ASET/KEKAYAAN DAERAH PER UPB \n DI LINGKUNGAN PEMERINTAH ".strtoupper(getKabKota("Nm_Kab_Kota")),0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');


	$pdf->Ln();	
	$tgl=date('Y-m-d');
	
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(10,30,80,45,45,45,45,45,45,50));
	$pdf->RowheaderUPB();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10"));
	
	
	/* REKAP DATA */
	$no=1;
	$pdf->SetFont('Times','','10');

	for ($a=0;$a<$jumlah;$a++){
			$kd1  = sprintf ("%02u", $response[$a]['cell'][1]);
			$kd2  = sprintf ("%02u", $response[$a]['cell'][2]);
			$kd3  = sprintf ("%02u", $response[$a]['cell'][3]);
			$kd4  = sprintf ("%02u", $response[$a]['cell'][0]);
			$kode = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4;
			
			$totala			= number_format($response[$a]['cell'][5],0,",",".");
			$totalb			= number_format($response[$a]['cell'][6],0,",",".");
			$totalc			= number_format($response[$a]['cell'][7],0,",",".");
			$totald			= number_format($response[$a]['cell'][8],0,",",".");
			$totale			= number_format($response[$a]['cell'][9],0,",",".");
			$totalf			= number_format($response[$a]['cell'][10],0,",",".");
			$totalperskpd 	= number_format($response[$a]['cell'][5] + $response[$a]['cell'][6] + $response[$a]['cell'][7] + $response[$a]['cell'][8] + $response[$a]['cell'][9] + $response[$a]['cell'][10],0,",",".");
			$pdf->Row(array($response[$a]['cell'][0], $kode, $response[$a]['cell'][4], $totala, $totalb, $totalc, $totald, $totale, $totalf, $totalperskpd));
	}
	
	$jumlaha2		= number_format($jumlaha,0,",",".");
	$jumlahb2		= number_format($jumlahb,0,",",".");
	$jumlahc2		= number_format($jumlahc,0,",",".");
	$jumlahd2		= number_format($jumlahd,0,",",".");
	$jumlahe2		= number_format($jumlahe,0,",",".");
	$jumlahf2		= number_format($jumlahf,0,",",".");
	$jumlahall2		= number_format($jumlahall,0,",",".");
	
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("#", "", "T O T A L", $jumlaha2, $jumlahb2, $jumlahc2, $jumlahd2, $jumlahe2, $jumlahf2, $jumlahall2));
	
	$kepaladaerah 			= "NAMA PIMPINAN";
	$nipkepaladaerah 		= "NIP PIMPINAN";
	$jabatankepaladaerah 	= "JABATAN PIMPINAN";
	$namapengurus 			= "NAMA PPENGURUS";
	$nippengurus 			= "NIP PENGURUS";
	

	$pdf->ttd2($kepaladaerah,$nipkepaladaerah,$jabatankepaladaerah,$namapengurus,$nippengurus,$tanggal);

	$pdf->Output("SIMDO_RekapTotalUPB.pdf","I");
?>