<?php

	$this->load->library('gabungan_pemda/PDF_MC_Table_kib_a_unit');
	
	$pdf=new PDF_MC_Table_kib_a_unit('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"KARTU INVENTARIS BARANG (KIB) \n A . TANAH \n KABUPATEN PROVINSI SUMATERA UTARA",0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');
	$pdf->Ln(20);	
	
	$kode_lokasi = KODE_LOKASI;
	$pdf->UPBTitle($nama_upb->Nm_bidang,$nama_upb->Nm_unit,$nama_upb->Nm_sub_unit,$nama_upb->Nm_UPB,$kode_lokasi);

	$pdf->Ln();	
	$tgl=date('Y-m-d');
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(10,60,40,20,30,20,40,35,30,20,40,30,30,35));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14"));
	$no=1;
	$luas=0;
	$pdf->SetFont('Times','','10');	
	if ($jumlah > 0){
		foreach ($kiba->result() as $row){
			$kd1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd5 = sprintf ("%02u", $row->Kd_Aset5);
			$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
			$tahun 		= date('Y', strtotime($row->Tgl_Perolehan));
			$tgl_sertifikat 		= date('d/m/Y', strtotime($row->Sertifikat_Tanggal));
			$harga		= number_format($row->Harga,0,",",".");
			$register	= sprintf('%04d',$row->No_Register);
			
			$pdf->Row(array($no,  $row->Nm_Aset5, $kodebarang, $register,$row->Luas_M2, $tahun, $row->Alamat,$row->Hak_Tanah,
			$tgl_sertifikat, $row->Sertifikat_Nomor, $row->Penggunaan, $row->Asal_usul, $harga, $row->Nm_UPB));
			$no++;
			$luas += $row->Luas_M2;
		}
	}else{
		$pdf->nihil();
	}
	$harga_total		= number_format($total,0,",",".");
	$luas				= number_format($luas,0,",",".");
	$pdf->SetFont('Times','','10');
	$pdf->Row(array('', '', '','',$luas." M2", "", "", "","", "", "Jumlah Harga", "",$harga_total, ""));

	$pdf->ttd($ta_upb->Nm_Pimpinan,$ta_upb->Nip_Pimpinan,$ta_upb->Jbt_Pimpinan,$ta_upb->Nm_Pengurus,$ta_upb->Nip_Pengurus,$tanggal);
	$pdf->Output("SIMDO_kiba.pdf","I");
?>