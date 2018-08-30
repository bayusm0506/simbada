<?php

	$this->load->library('PDF_MC_Table_ekstra');
	
	$pdf=new PDF_MC_Table_ekstra('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"DAFTAR BARANG INTRAKOMPTABEL\n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');
	$pdf->Ln(20);	

	$kb1 = sprintf ("%02u", $nama_upb->Kd_Bidang);
	$kb2 = sprintf ("%02u", $nama_upb->Kd_Unit);
	$kb3 = sprintf ("%02u", $nama_upb->Kd_Sub);
	$kb4 = sprintf ("%02u", $nama_upb->Kd_UPB);
	$kode_lokasi = "11.02.0.".$kb1.'.'.$kb2.'.'.$kb3.'.'.$kb4;
	$pdf->UPBTitle($nama_upb->Nm_bidang,$nama_upb->Nm_unit,$nama_upb->Nm_sub_unit,$nama_upb->Nm_UPB,$kode_lokasi);

	$pdf->Ln();	
	$tgl=date('Y-m-d');
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(10,60,40,30,30,40,25,35,20,40,20,20,20,50));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14"));
	
	/* kib a */
	$no=1;
	$pdf->SetFont('Times','','10');
	$totalkiba		= number_format($total_intrakomptabel_kiba,0,",",".");
	$pdf->Row(array("01", "TANAH", "", "","", "", "", "","", $totalkiba, "", "","", ""));	
	if ($jumlah_intrakomptabel_kiba > 0){
		$pdf->SetFont('Times','','10');	
		foreach ($intrakomptabel_kiba->result() as $row){
			$harga		= number_format($row->Harga,0,",",".");
			$kd1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd5 = sprintf ("%02u", $row->Kd_Aset5);
			$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
			
			$pdf->Row(array($no, $row->Nm_Aset5, '', $row->Sertifikat_Nomor,$row->Luas_M2, $row->Alamat,$row->Tahun,$kodebarang,$row->Jumlah_Data,
			$harga, '', '','', $row->Keterangan));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB B */
	$no=1;
	$pdf->SetFont('Times','','10');
	$totalkibb		= number_format($total_intrakomptabel_kibb,0,",",".");	
	$pdf->Row(array("02", "PERALATAN DAN MESIN", "", "","", "", "", "","", $totalkibb, "", "","", ""));		
	if ($jumlah_intrakomptabel_kibb > 0){
		$pdf->SetFont('Times','','10');	
		foreach ($intrakomptabel_kibb->result() as $row){
			$harga		= number_format($row->Harga,0,",",".");
			$kd1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd5 = sprintf ("%02u", $row->Kd_Aset5);
			$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
			
			$pdf->Row(array($no, $row->Nm_Aset5, $row->Merk, $row->Nomor_Pabrik,$row->CC, $row->Bahan,$row->Tahun,$kodebarang,$row->Jumlah_Data,
			$harga, $row->Baik, $row->Kurang_baik,$row->Rusak, $row->Keterangan));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}

	/* KIB C */
	$no=1;
	$pdf->SetFont('Times','','10');
	$totalkibc		= number_format($total_intrakomptabel_kibc,0,",",".");	
	$pdf->Row(array("03", "GEDUNG & BANGUNAN", "", "","", "", "", "","", $totalkibc, "", "","", ""));		
	if ($jumlah_intrakomptabel_kibc > 0){
		$pdf->SetFont('Times','','10');	
		foreach ($intrakomptabel_kibc->result() as $row){
			$harga		= number_format($row->Harga,0,",",".");
			$kd1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd5 = sprintf ("%02u", $row->Kd_Aset5);
			$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
			
			$pdf->Row(array($no, $row->Nm_Aset5, '', '','', '','',$kodebarang,$row->Jumlah_Data,
			$harga, '', '','', $row->Nm_UPB));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB D */
	$no=1;
	$pdf->SetFont('Times','','10');
	$totalkibd		= number_format($total_intrakomptabel_kibd,0,",",".");	
	$pdf->Row(array("04", "JALAN, IRIGASI & JARINGAN", "", "","", "", "", "","", $totalkibd, "", "","", ""));		
	if ($jumlah_intrakomptabel_kibd > 0){
		$pdf->SetFont('Times','','10');	
		foreach ($intrakomptabel_kibd->result() as $row){
			$harga		= number_format($row->Harga,0,",",".");
			$kd1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd5 = sprintf ("%02u", $row->Kd_Aset5);
			$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
			
			$pdf->Row(array($no, $row->Nm_Aset5, '', '','', '','',$kodebarang,$row->Jumlah_Data,
			$harga, '', '','', $row->Nm_UPB));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB E */
	$no=1;
	$pdf->SetFont('Times','','10');
	$totalkibe		= number_format($total_intrakomptabel_kibe,0,",",".");	
	$pdf->Row(array("05", "ASET TETAP LAINYA", "", "","", "", "", "","", $totalkibe, "", "","", ""));		
	if ($jumlah_intrakomptabel_kibe > 0){
		$pdf->SetFont('Times','','10');	
		foreach ($intrakomptabel_kibe->result() as $row){
			$harga		= number_format($row->Harga,0,",",".");
			$kd1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd5 = sprintf ("%02u", $row->Kd_Aset5);
			$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
			
			$pdf->Row(array($no, $row->Nm_Aset5, '', '','', '','',$kodebarang,$row->Jumlah_Data,
			$harga, '', '','', $row->Nm_UPB));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}

	$harga_total	= number_format($total,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('', 'Jumlah Harga', '','',"", "", "", "","", $harga_total, "", "",'', ""));

	$pdf->ttd($ta_upb->Nm_Pimpinan,$ta_upb->Nip_Pimpinan,$ta_upb->Jbt_Pimpinan,$ta_upb->Nm_Pengurus,$ta_upb->Nip_Pengurus,$tanggal);
	
	$pdf->Output("SIMDO_Intrakomptabel.pdf","I");
?>