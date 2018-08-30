<?php

	$this->load->library('inventarisasi/PDF_MC_Table_Pemeliharaan');
	
	$pdf=new PDF_MC_Table_Pemeliharaan('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"KARTU PEMELIHARAAN BARANG \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');
	$pdf->Ln(20);	

	$kb1 = sprintf ("%02u", $nama_upb['Kd_Bidang']);
	$kb2 = sprintf ("%02u", $nama_upb['Kd_Unit']);
	$kb3 = sprintf ("%02u", $nama_upb['Kd_Sub']);
	$kb4 = sprintf ("%02u", $nama_upb['Kd_UPB']);
	$kode_lokasi  = KODE_LOKASI.'.'.$kb1.'.'.$kb2.'.'.$kb3.'.'.$kb4;
	$kode_lokasi2 = KODE_LOKASI;

	if(($ttd_pengurus) AND ($skpd)){
		$pdf->UPBTitle($nama_upb['Nm_bidang'],$nama_upb['Nm_unit'],$nama_upb['Nm_sub_unit'],'',$kode_lokasi);
	}elseif($ttd_pengurus){
		$pdf->UPBTitle($nama_upb['Nm_bidang'],$nama_upb['Nm_unit'],$nama_upb['Nm_sub_unit'],$nama_upb['Nm_UPB'],$kode_lokasi);
	}else{
		$pdf->UPBTitle2($kode_lokasi2);
	}

	$pdf->Ln();	
	$tgl=date('Y-m-d');
	
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(15,30,20,60,30,30,20,25,25,30,40,25,40,50));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14"));
	
	/* KIB A */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("01", "", "", "TANAH","", "", "", "","", "", "",  $totala->Jumlah,rp($totala->Harga),""));
	if ($jumlaha > 0){
	$pdf->SetFont('Times','','10');
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
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$row->Alamat, $row->Sertifikat_Nomor,nilai($row->Luas_M2)." M2", $row->Asal_usul,$row->Tahun, tgl_indo($row->Tgl_Dokumen), $row->No_Dokumen, $row->Jumlah,$harga, $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	
	/* KIB B */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$totalkibbb		= number_format($totalb->Harga,0,",",".");	
	$pdf->Row(array("02", "", "", "PERALATAN DAN MESIN","", "", "", "","", "", "",rp($totalb->Jumlah), $totalkibbb,  ""));
	if ($jumlah > 0){
		$pdf->SetFont('Times','','10');
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
		
		
		$harga		= number_format($row->Harga,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$merk, "-", $row->Bahan,  $row->Asal_usul,$row->Tahun, tgl_indo($row->Tgl_Dokumen), $row->No_Dokumen,$row->Jumlah, $harga, $row->Keterangan));		
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	
	/* KIB C */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibbc		= number_format($totalc->Harga,0,",",".");	
	$pdf->Row(array("03", "", "", "GEDUNG DAN BANGUNAN","", "", "", "","", "", "", $totalc->Jumlah, $totalkibbc,""));
	if ($jumlahc > 0){
	$pdf->SetFont('Times','','10');	
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
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$row->Lokasi, $row->Dokumen_Nomor, "-", $row->Asal_usul,$row->Tahun, tgl_indo($row->Tgl_Dokumen), $row->No_Dokumen,$row->Jumlah, $harga, $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB D */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibbd		= number_format($totald->Harga,0,",",".");	
	$pdf->Row(array("04", "", "", "JALAN, IRIGASI DAN JARINGAN","", "", "", "","", "", "", $totald->Jumlah, $totalkibbd,""));
	if ($jumlahd > 0){
	$pdf->SetFont('Times','','10');	
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
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$row->Lokasi, "", "-", $row->Asal_usul,$row->Tahun,tgl_indo($row->Tgl_Dokumen), $row->No_Dokumen, $row->Jumlah, $harga, $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB E */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$totalkibbe		= number_format($totale->Harga,0,",",".");	
	$pdf->Row(array("05", "", "", "ASET TETAP LAINYA","", "", "", "","", "", "", $totale->Jumlah, $totalkibbe, ""));
	if ($jumlahe > 0){
	$pdf->SetFont('Times','','10');	
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
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", "", "-", $row->Asal_usul,$row->Tahun, tgl_indo($row->Tgl_Dokumen), $row->No_Dokumen,$row->Jumlah, $harga, $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	

	// $jumlah_total = $totala->Jumlah+$totalb->Jumlah+$totalc->Jumlah+$totald->Jumlah+$totald->Jumlah+$totalf->Jumlah;
	
	// $tot_a = $totala->Kapitalisasi + $totala->Koreksi_Tambah - $totala->Koreksi_Kurang;
	// $tot_b = $totalb->Harga + $totalb->Kapitalisasi + $totalb->Koreksi_Tambah - $totalb->Koreksi_Kurang;
	// $tot_c = $totalc->Harga + $totalc->Kapitalisasi + $totalc->Koreksi_Tambah - $totalc->Koreksi_Kurang;
	// $tot_d = $totald->Harga + $totald->Kapitalisasi + $totald->Koreksi_Tambah - $totald->Koreksi_Kurang;
	// $tot_e = $totale->Harga + $totale->Kapitalisasi + $totale->Koreksi_Tambah - $totale->Koreksi_Kurang;
	// $tot_f = $totalf->Harga;
	// $harga_total	= $tot_a + $tot_b + $tot_c + $tot_d + $tot_e + $tot_f;

	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", "", "", "TOTAL ","", "", "", "","", "", "",rp(12), rp(21), ""));

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}

	$pdf->Output("SIMDO_Inventaris.pdf","I");
?>