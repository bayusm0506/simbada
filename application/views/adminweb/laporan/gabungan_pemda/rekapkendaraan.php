<?php

	$this->load->library('PDF_MC_Table_rkendaraan');
	
	$pdf=new PDF_MC_Table_rkendaraan('P','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(280,5,"REKAPITULASI KENDARAAN DINAS MENURUT JUMLAH RODA \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(280,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(280,5,$periode,0,'C');

	$pdf->Ln(20);
	$kode_lokasi = KODE_LOKASI;
	$pdf->UPBTitle2($nama_upb->Nm_bidang,$nama_upb->Nm_unit,$nama_upb->Nm_sub_unit,$nama_upb->Nm_UPB,$kode_lokasi);

	$pdf->Ln();	
	$tgl=date('Y-m-d');
	
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(15,30,30,80,30,45,50));
	$pdf->Rowheader2();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7"));
	
	/* RODA 2 */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_roda_2		= number_format($totalkendaraan_roda_2->Harga,0,",",".");
	$pdf->Row(array("", "", "RODA 2", "",$totalkendaraan_roda_2->Jumlah, $total_roda_2, ""));
	if ($jumlahkendaraan_roda_2 > 0){
	foreach ($kendaraan_roda_2->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kode_aset = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
		$harga		= number_format($row->Harga,0,",",".");
		if ($harga == 0){
			$harga 	= '-';
			$jumlah = '-';
		}else{
			$jumlah = $row->Jumlah;
		}
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array($no, $kode_aset, $row->Jumlah_Roda, $row->Nm_Aset5,$jumlah, $harga, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* RODA 3 */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_roda_3		= number_format($totalkendaraan_roda_3->Harga,0,",",".");
	$pdf->Row(array("", "", "RODA 3", "",$totalkendaraan_roda_3->Jumlah, $total_roda_3, ""));
	if ($jumlahkendaraan_roda_3 > 0){
	foreach ($kendaraan_roda_3->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kode_aset = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
		$harga		= number_format($row->Harga,0,",",".");
		if ($harga == 0){
			$harga 	= '-';
			$jumlah = '-';
		}else{
			$jumlah = $row->Jumlah;
		}
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array($no, $kode_aset, $row->Jumlah_Roda, $row->Nm_Aset5,$jumlah, $harga, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* RODA 4 */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_roda_4		= number_format($totalkendaraan_roda_4->Harga,0,",",".");
	$pdf->Row(array("", "", "RODA 4", "",$totalkendaraan_roda_4->Jumlah, $total_roda_4, ""));
	if ($jumlahkendaraan_roda_4 > 0){
	foreach ($kendaraan_roda_4->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kode_aset = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
		$harga		= number_format($row->Harga,0,",",".");
		if ($harga == 0){
			$harga 	= '-';
			$jumlah = '-';
		}else{
			$jumlah = $row->Jumlah;
		}
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array($no, $kode_aset, $row->Jumlah_Roda, $row->Nm_Aset5,$jumlah, $harga, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* RODA 6 */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_roda_6		= number_format($totalkendaraan_roda_6->Harga,0,",",".");
	$pdf->Row(array("", "", "RODA 6", "",$totalkendaraan_roda_6->Jumlah, $total_roda_6, ""));
	if ($jumlahkendaraan_roda_6 > 0){
	foreach ($kendaraan_roda_6->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kode_aset = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
		$harga		= number_format($row->Harga,0,",",".");
		if ($harga == 0){
			$harga 	= '-';
			$jumlah = '-';
		}else{
			$jumlah = $row->Jumlah;
		}
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array($no, $kode_aset, $row->Jumlah_Roda, $row->Nm_Aset5,$jumlah, $harga, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* RODA 8 */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_roda_8		= number_format($totalkendaraan_roda_8->Harga,0,",",".");
	$pdf->Row(array("", "", "RODA 8", "",$totalkendaraan_roda_8->Jumlah, $total_roda_8, ""));
	if ($jumlahkendaraan_roda_8 > 0){
	foreach ($kendaraan_roda_8->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kode_aset = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
		$harga		= number_format($row->Harga,0,",",".");
		if ($harga == 0){
			$harga 	= '-';
			$jumlah = '-';
		}else{
			$jumlah = $row->Jumlah;
		}
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array($no, $kode_aset, $row->Jumlah_Roda, $row->Nm_Aset5,$jumlah, $harga, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* RODA 10 */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_roda_10		= number_format($totalkendaraan_roda_10->Harga,0,",",".");
	$pdf->Row(array("", "", "RODA 10", "",$totalkendaraan_roda_10->Jumlah, $total_roda_10, ""));
	if ($jumlahkendaraan_roda_10 > 0){
	foreach ($kendaraan_roda_10->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kode_aset = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
		$harga		= number_format($row->Harga,0,",",".");
		if ($harga == 0){
			$harga 	= '-';
			$jumlah = '-';
		}else{
			$jumlah = $row->Jumlah;
		}
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array($no, $kode_aset, $row->Jumlah_Roda, $row->Nm_Aset5,$jumlah, $harga, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	$total = $totalkendaraan_roda_2->Harga+$totalkendaraan_roda_3->Harga+$totalkendaraan_roda_4->Harga+$totalkendaraan_roda_6->Harga+$totalkendaraan_roda_8->Harga+$totalkendaraan_roda_10->Harga;
	$jumlah_total = $totalkendaraan_roda_2->Jumlah+$totalkendaraan_roda_3->Jumlah+$totalkendaraan_roda_4->Jumlah+$totalkendaraan_roda_6->Jumlah+$totalkendaraan_roda_8->Jumlah+$totalkendaraan_roda_10->Jumlah;
	$harga_total		= number_format($total,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", " T O T A L ", "", "",$jumlah_total, $harga_total, ""));

	$kepaladaerah 			= "NAMA PIMPINAN";
	$nipkepaladaerah 		= "NIP PIMPINAN";
	$jabatankepaladaerah 	= "JABATAN PIMPINAN";
	$namapengurus 			= "NAMA PPENGURUS";
	$nippengurus 			= "NIP PENGURUS";

	$pdf->ttd2($kepaladaerah,$nipkepaladaerah,$jabatankepaladaerah,$namapengurus,$nippengurus,$tanggal);

	$pdf->Output("SIMDO_RekapKendaraan.pdf","I");
?>