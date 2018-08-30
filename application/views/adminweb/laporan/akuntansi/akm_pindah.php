<?php

	$this->load->library('akuntansi/PDF_MC_akm_hapus');
	
	$pdf=new PDF_MC_akm_hapus('P','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(280,5,"REKAP BARANG YANG TELAH DIPINDAHKAN \n ".NM_PEMDA,0,'C');
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
	
	$pdf->SetWidths(array(15,25,65,15,40,40,40,40));
	$pdf->Rowheader();
	$pdf->Ln(3);
	$pdf->RowJudul(array("1", "2", "3", "4", "5", "6", "7", "8"));

	
	/* KIB A  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_kiba 		  = sum_arr($kiba,'Jumlah');
	$nilai_perolehan_kiba = sum_arr($kiba,'Nilai_Perolehan');
	$beban_susut_kiba     = sum_arr($kiba,'Beban_Susut');
	$akm_susut_kiba 	  = sum_arr($kiba,'Akm_Susut');
	$nilai_buku_kiba 	  = sum_arr($kiba,'Nilai_Buku');	
	$pdf->Row(array("1", "", "TANAH",rp($jumlah_kiba),rp($nilai_perolehan_kiba),rp($beban_susut_kiba),rp($akm_susut_kiba),rp($nilai_buku_kiba)));
	foreach ($kiba->result() as $row){
		$kd1 		= sprintf ("%02u", $row->Kd_Aset1);
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$pdf->SetFont('Times','','10');

		$pdf->Row(array("", $kd1.".".$kd2, $row->Nm_Aset2,$row->Jumlah, rp($row->Nilai_Perolehan),rp($row->Beban_Susut),rp($row->Akm_Susut),rp($row->Nilai_Buku)));
		$no++;
	}
	
	/* KIB B  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_kibb 		  = sum_arr($kibb,'Jumlah');
	$nilai_perolehan_kibb = sum_arr($kibb,'Nilai_Perolehan');
	$beban_susut_kibb     = sum_arr($kibb,'Beban_Susut');
	$akm_susut_kibb 	  = sum_arr($kibb,'Akm_Susut');
	$nilai_buku_kibb 	  = sum_arr($kibb,'Nilai_Buku');	
	$pdf->Row(array("2", "", "PERALATAN & MESIN",rp($jumlah_kibb),rp($nilai_perolehan_kibb),rp($beban_susut_kibb),rp($akm_susut_kibb),rp($nilai_buku_kibb)));
	foreach ($kibb->result() as $row){
		$kd1 		= sprintf ("%02u", $row->Kd_Aset1);
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$pdf->SetFont('Times','','10');

		$pdf->Row(array("", $kd1.".".$kd2, $row->Nm_Aset2,$row->Jumlah, rp($row->Nilai_Perolehan),rp($row->Beban_Susut),rp($row->Akm_Susut),rp($row->Nilai_Buku)));
		$no++;
	}

	/* KIB C  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_kibc 		  = sum_arr($kibc,'Jumlah');
	$nilai_perolehan_kibc = sum_arr($kibc,'Nilai_Perolehan');
	$beban_susut_kibc     = sum_arr($kibc,'Beban_Susut');
	$akm_susut_kibc 	  = sum_arr($kibc,'Akm_Susut');
	$nilai_buku_kibc 	  = sum_arr($kibc,'Nilai_Buku');	
	$pdf->Row(array("3", "", "GEDUNG & BANGUNAN",rp($jumlah_kibc),rp($nilai_perolehan_kibc),rp($beban_susut_kibc),rp($akm_susut_kibc),rp($nilai_buku_kibc)));
	foreach ($kibc->result() as $row){
		$kd1 		= sprintf ("%02u", $row->Kd_Aset1);
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$pdf->SetFont('Times','','10');

		$pdf->Row(array("", $kd1.".".$kd2, $row->Nm_Aset2,$row->Jumlah, rp($row->Nilai_Perolehan),rp($row->Beban_Susut),rp($row->Akm_Susut),rp($row->Nilai_Buku)));
		$no++;
	}

	/* KIB D  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_kibd 		  = sum_arr($kibd,'Jumlah');
	$nilai_perolehan_kibd = sum_arr($kibd,'Nilai_Perolehan');
	$beban_susut_kibd     = sum_arr($kibd,'Beban_Susut');
	$akm_susut_kibd 	  = sum_arr($kibd,'Akm_Susut');
	$nilai_buku_kibd 	  = sum_arr($kibd,'Nilai_Buku');	
	$pdf->Row(array("4", "", "JALAN, IRIGASI & JARINGAN",rp($jumlah_kibd),rp($nilai_perolehan_kibd),rp($beban_susut_kibd),rp($akm_susut_kibd),rp($nilai_buku_kibd)));
	foreach ($kibd->result() as $row){
		$kd1 		= sprintf ("%02u", $row->Kd_Aset1);
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$pdf->SetFont('Times','','10');

		$pdf->Row(array("", $kd1.".".$kd2, $row->Nm_Aset2,$row->Jumlah, rp($row->Nilai_Perolehan),rp($row->Beban_Susut),rp($row->Akm_Susut),rp($row->Nilai_Buku)));
		$no++;
	}

	/* KIB E  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_kibe 		  = sum_arr($kibe,'Jumlah');
	$nilai_perolehan_kibe = sum_arr($kibe,'Nilai_Perolehan');
	$beban_susut_kibe     = sum_arr($kibe,'Beban_Susut');
	$akm_susut_kibe 	  = sum_arr($kibe,'Akm_Susut');
	$nilai_buku_kibe 	  = sum_arr($kibe,'Nilai_Buku');	
	$pdf->Row(array("5", "", "ASET TETAP LAINNYA",rp($jumlah_kibe),rp($nilai_perolehan_kibe),rp($beban_susut_kibe),rp($akm_susut_kibe),rp($nilai_buku_kibe)));
	foreach ($kibe->result() as $row){
		$kd1 		= sprintf ("%02u", $row->Kd_Aset1);
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);

		$pdf->SetFont('Times','','10');

		$pdf->Row(array("", $kd1.".".$kd2, $row->Nm_Aset2,$row->Jumlah, rp($row->Nilai_Perolehan),rp($row->Beban_Susut),rp($row->Akm_Susut),rp($row->Nilai_Buku)));
		$no++;
	}


	
	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}
	$pdf->Output("SIMDO_Neraca.pdf","I");
?>