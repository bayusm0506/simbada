<?php

	$this->load->library('PDF_MC_Table_rkendaraan');
	
	$pdf=new PDF_MC_Table_rkendaraan('P','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(280,5,"REKAP KENDARAAN \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(280,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(280,5,$periode,0,'C');
	$pdf->Ln(20);	

	$kb1 = sprintf ("%02u", $nama_upb['Kd_Bidang']);
	$kb2 = sprintf ("%02u", $nama_upb['Kd_Unit']);
	$kb3 = sprintf ("%02u", $nama_upb['Kd_Sub']);
	$kb4 = sprintf ("%02u", $nama_upb['Kd_UPB']);
	$kode_lokasi = KODE_LOKASI.'.'.$kb1.'.'.$kb2.'.'.$kb3.'.'.$kb4;
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
	$pdf->SetWidths(array(15,30,30,80,30,45,50));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7"));
	
	/* RODA 2 */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_roda_2 = sum_arr($kendaraan_roda_2,"Jumlah");
	$harga_roda_2  = sum_arr($kendaraan_roda_2,"Harga");
	$pdf->Row(array("#", "", "RODA 2", "",rp($jumlah_roda_2), rp($harga_roda_2), ""));
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
		$pdf->Row(array($no, $kode_aset, $row->Jumlah_Roda, $row->Nm_Aset5,$jumlah, $harga, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* RODA 3 */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_roda_3 = sum_arr($kendaraan_roda_3,"Jumlah");
	$harga_roda_3  = sum_arr($kendaraan_roda_3,"Harga");
	$pdf->Row(array("#", "", "RODA 3", "",rp($jumlah_roda_3), rp($harga_roda_3), ""));
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
		$pdf->Row(array($no, $kode_aset, $row->Jumlah_Roda, $row->Nm_Aset5,$jumlah, $harga, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* RODA 4 */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_roda_4 = sum_arr($kendaraan_roda_4,"Jumlah");
	$harga_roda_4  = sum_arr($kendaraan_roda_4,"Harga");
	$pdf->Row(array("#", "", "RODA 4", "",rp($jumlah_roda_4), rp($harga_roda_4), ""));
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
		$pdf->Row(array($no, $kode_aset, $row->Jumlah_Roda, $row->Nm_Aset5,$jumlah, $harga, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* RODA 6 */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_roda_6 = sum_arr($kendaraan_roda_6,"Jumlah");
	$harga_roda_6  = sum_arr($kendaraan_roda_6,"Harga");
	$pdf->Row(array("#", "", "RODA 6", "", rp($jumlah_roda_6), rp($harga_roda_6), ""));
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
		$pdf->Row(array($no, $kode_aset, $row->Jumlah_Roda, $row->Nm_Aset5,$jumlah, $harga, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* RODA 8 */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_roda_8 = sum_arr($kendaraan_roda_8,"Jumlah");
	$harga_roda_8  = sum_arr($kendaraan_roda_8,"Harga");
	$pdf->Row(array("#", "", "RODA 8", "", rp($jumlah_roda_8), rp($harga_roda_8), ""));
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
		$pdf->Row(array($no, $kode_aset, $row->Jumlah_Roda, $row->Nm_Aset5,$jumlah, $harga, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* RODA 10 */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_roda_10 = sum_arr($kendaraan_roda_10,"Jumlah");
	$harga_roda_10  = sum_arr($kendaraan_roda_10,"Harga");
	$pdf->Row(array("#", "", "RODA 10", "",rp($jumlah_roda_10), rp($harga_roda_10), ""));
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
		$pdf->Row(array($no, $kode_aset, $row->Jumlah_Roda, $row->Nm_Aset5,$jumlah, $harga, ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}


	$harga_total  = $harga_roda_2+$harga_roda_3+$harga_roda_4+$harga_roda_6+$harga_roda_8+$harga_roda_10;
	$jumlah_total = $jumlah_roda_2+$jumlah_roda_3+$jumlah_roda_4+$jumlah_roda_6+$jumlah_roda_8+$jumlah_roda_10;
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", " T O T A L ", "", "",rp($jumlah_total), rp($harga_total), ""));

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}

	$pdf->Output("SIMDO_RekapKendaraan.pdf","I");
?>