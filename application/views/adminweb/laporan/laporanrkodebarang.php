<?php

	$this->load->library('pdf_mc_table_rkodebarang');
	
	$pdf=new PDF_MC_Table_rkodebarang('P','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(280,5,"REKAPITULASI BARANG MILIK DAERAH MENURUT KODE BARANG \n ".strtoupper(getInfoPemda('Nm_Pemda')),0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(280,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(280,5,$periode,0,'C');
	$pdf->Ln(20);	

	$kb1 = sprintf ("%02u", $nama_upb->Kd_Bidang);
	$kb2 = sprintf ("%02u", $nama_upb->Kd_Unit);
	$kb3 = sprintf ("%02u", $nama_upb->Kd_Sub);
	$kb4 = sprintf ("%02u", $nama_upb->Kd_UPB);
	$kode_lokasi = "12.02.14.".$kb1.'.'.$kb2.'.'.$kb3.'.'.$kb4;
	$pdf->UPBTitle($nama_upb->Nm_bidang,$nama_upb->Nm_unit,$nama_upb->Nm_sub_unit,$nama_upb->Nm_UPB,$kode_lokasi);


	$pdf->Ln();	
	$tgl=date('Y-m-d');
	
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(15,50,85,30,50,50));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6"));
	
	/* KIB A */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$rev_totala = ($total_kiba->Harga + $total_kiba->Kapitalisasi + $total_kiba->Koreksi ) - $total_kiba->Penghapusan;
	$totalkiba		= number_format($rev_totala,0,",",".");
	$pdf->Row(array("1", "01", "Tanah", $total_kiba->Jumlah,$totalkiba, ''));
	
	if ($jumlah_kiba > 0){
		foreach ($kiba->result() as $row){
			$kd_aset1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd_aset2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd_aset3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd_aset4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd_aset5 = sprintf ("%02u", $row->Kd_Aset5);
			$harga		= number_format($row->Harga,0,",",".");
			$pdf->SetFont('Times','','10');	
			$pdf->SetWidthsx(array(15,10,10,10,10,10,85,30,50,50));
			$pdf->RowData(array($no, $kd_aset1, $kd_aset2, $kd_aset3,$kd_aset4, $kd_aset5,$row->Nm_Aset5,$row->Jumlah,$harga,''));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	/* end KIB A */
	
	/* KIB B */
	$no=1;
	$jlh=0;
	$pdf->SetFont('Times','B','10');	
	$totalkibb		= number_format($total_kibb->Harga,0,",",".");
	$pdf->Row(array("1", "02", "Peralatan & Mesin", $total_kibb->Jumlah,$totalkibb, ''));
	
	if ($jumlah_kibb > 0){
		foreach ($kibb->result() as $row){
			$kd_aset1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd_aset2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd_aset3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd_aset4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd_aset5 = sprintf ("%02u", $row->Kd_Aset5);
			$harga		= number_format($row->Harga,0,",",".");
			$pdf->SetFont('Times','','10');	
			$pdf->SetWidthsx(array(15,10,10,10,10,10,85,30,50,50));
			$pdf->RowData(array($no, $kd_aset1, $kd_aset2, $kd_aset3,$kd_aset4, $kd_aset5,$row->Nm_Aset5,$row->Jumlah,$harga,''));
			$no++;
			$jlh++;
		}
	}else{
		$pdf->nihil2();
	}
	/* end KIB B */
	
	/* KIB C */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibc		= number_format($total_kibc->Harga,0,",",".");
	$pdf->Row(array("1", "03", "Gedung & Bangunan", $total_kibc->Jumlah,$totalkibc, ''));
	
	if ($jumlah_kibc > 0){
		foreach ($kibc->result() as $row){
			$kd_aset1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd_aset2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd_aset3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd_aset4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd_aset5 = sprintf ("%02u", $row->Kd_Aset5);
			$harga		= number_format($row->Harga,0,",",".");
			$pdf->SetFont('Times','','10');	
			$pdf->SetWidthsx(array(15,10,10,10,10,10,85,30,50,50));
			$pdf->RowData(array($no, $kd_aset1, $kd_aset2, $kd_aset3,$kd_aset4, $kd_aset5,$row->Nm_Aset5,$row->Jumlah,$harga,''));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	/* end KIB C */
	
	/* KIB D */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibd		= number_format($total_kibd->Harga,0,",",".");
	$pdf->Row(array("1", "04", "Jalan, Irigasi & Jaringan", $total_kibd->Jumlah,$totalkibd, ''));
	
	if ($jumlah_kibd > 0){
		foreach ($kibd->result() as $row){
			$kd_aset1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd_aset2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd_aset3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd_aset4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd_aset5 = sprintf ("%02u", $row->Kd_Aset5);
			$harga		= number_format($row->Harga,0,",",".");
			$pdf->SetFont('Times','','10');	
			$pdf->SetWidthsx(array(15,10,10,10,10,10,85,30,50,50));
			$pdf->RowData(array($no, $kd_aset1, $kd_aset2, $kd_aset3,$kd_aset4, $kd_aset5,$row->Nm_Aset5,$row->Jumlah,$harga,''));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	/* end KIB D */
	
	/* KIB E */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibe		= number_format($total_kibe->Harga,0,",",".");
	$pdf->Row(array("1", "05", "Aset Tetap Lainya", $total_kibe->Jumlah,$totalkibe, ''));
	
	if ($jumlah_kibe > 0){
		foreach ($kibe->result() as $row){
			$kd_aset1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd_aset2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd_aset3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd_aset4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd_aset5 = sprintf ("%02u", $row->Kd_Aset5);
			$harga		= number_format($row->Harga,0,",",".");
			$pdf->SetFont('Times','','10');	
			$pdf->SetWidthsx(array(15,10,10,10,10,10,85,30,50,50));
			$pdf->RowData(array($no, $kd_aset1, $kd_aset2, $kd_aset3,$kd_aset4, $kd_aset5,$row->Nm_Aset5,$row->Jumlah,$harga,''));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	/* end KIB E */
	
	/* KIB F */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibf		= number_format($total_kibf->Harga,0,",",".");
	$pdf->Row(array("1", "06", "Konstruksi Dalam Pekerjaan", $total_kibf->Jumlah,$totalkibf, ''));
	
	if ($jumlah_kibf > 0){
		foreach ($kibf->result() as $row){
			$kd_aset1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd_aset2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd_aset3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd_aset4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd_aset5 = sprintf ("%02u", $row->Kd_Aset5);
			$harga		= number_format($row->Harga,0,",",".");
			$pdf->SetFont('Times','','10');	
			$pdf->SetWidthsx(array(15,10,10,10,10,10,85,30,50,50));
			$pdf->RowData(array($no, $kd_aset1, $kd_aset2, $kd_aset3,$kd_aset4, $kd_aset5,$row->Nm_Aset5,$row->Jumlah,$harga,''));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	/* end KIB F */
	
	
	$total_jumlah = $total_kiba->Jumlah + $total_kibb->Jumlah + $total_kibc->Jumlah + $total_kibd->Jumlah + $total_kibe->Jumlah + $total_kibf->Jumlah;
	$total 		  = $rev_totala + $total_kibb->Harga + $total_kibc->Harga + $total_kibd->Harga + $total_kibe->Harga + $total_kibf->Harga;
	$harga_total		= number_format($total,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->SetWidths(array(15,50,85,30,50,50));
	$pdf->Row(array("", "T O T A L", "", $jumlah,$harga_total, ""));

	$pdf->ttd($ta_upb->Nm_Pimpinan,$ta_upb->Nip_Pimpinan,$ta_upb->Jbt_Pimpinan,$ta_upb->Nm_Pengurus,$ta_upb->Nip_Pengurus,$tanggal);

	$pdf->Output("SIMDO_RekapPerKodeBarang.pdf","I");
?>