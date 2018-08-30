<?php

	$this->load->library('PDF_MC_Table_inventaris');
	
	$pdf=new PDF_MC_Table_inventaris('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"BUKU INVENTARIS \n ".NM_PEMDA,0,'C');
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
	$pdf->SetWidths(array(15,30,20,60,30,30,20,25,25,25,20,25,25,40,50));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14", "15"));
	
	/* KIB A */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_a =  sum_arr($kiba,"Jumlah");
	$harga_a  =  sum_arr($kiba,"Harga");
	$pdf->Row(array("01", "", "", "TANAH","", "", "", "","", "", "", "", rp($jumlah_a),rp($harga_a),""));
	$pdf->SetFont('Times','','10');
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
		
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", $row->Sertifikat_Nomor, "-", $row->Asal_usul,$row->Tahun, $row->Luas_M2, "M2", "-",$row->Jumlah,rp($row->Harga), $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	
	/* KIB B */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_b =  sum_arr($kibb,"Jumlah");
	$harga_b  =  sum_arr($kibb,"Harga");
	$pdf->Row(array("02", "", "", "PERALATAN DAN MESIN","", "", "", "","", "", "", "", rp($jumlah_b),rp($harga_b),""));
	$pdf->SetFont('Times','','10');
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
		
		if ($row->LastKondisi == 1){
			$kondisi = 'Baik';
		}elseif ($row->LastKondisi == 2){
			$kondisi = 'Kurang Baik';
		}else{
			$kondisi = 'Rusak Berat';
		}
		
		$harga		= number_format($row->Harga,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$merk, "-", $row->Bahan,  $row->Asal_usul,$row->Tahun, $row->CC, '',$kondisi,$row->Jumlah,rp($row->Harga), $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	
	/* KIB C */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_c =  sum_arr($kibc,"Jumlah");
	$harga_c  =  sum_arr($kibc,"Harga");
	$pdf->Row(array("03", "", "", "GEDUNG DAN BANGUNAN","", "", "", "","", "", "", "",rp($jumlah_c),rp($harga_c),""));
	$pdf->SetFont('Times','','10');
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
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", $row->Dokumen_Nomor, "-", $row->Asal_usul,$row->Tahun, $row->Luas_Lantai,"M2", "-",$row->Jumlah,rp($row->Harga), $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB D */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_d =  sum_arr($kibd,"Jumlah");
	$harga_d  =  sum_arr($kibd,"Harga");
	$pdf->Row(array("04", "", "", "JALAN, IRIGASI DAN JARINGAN","", "", "", "","", "", "", "", rp($jumlah_d),rp($harga_d),""));
	$pdf->SetFont('Times','','10');
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
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", "", "-", $row->Asal_usul,$row->Tahun, $row->Luas,"M2", "-", $row->Jumlah,rp($row->Harga), $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB E */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_e =  sum_arr($kibe,"Jumlah");
	$harga_e  =  sum_arr($kibe,"Harga");
	$pdf->Row(array("05", "", "", "ASET TETAP LAINYA","", "", "", "","", "", "", "", rp($jumlah_e),rp($harga_e),""));
	$pdf->SetFont('Times','','10');
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
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", "", "-", $row->Asal_usul,$row->Tahun, '-', "", "-", $row->Jumlah,rp($row->Harga), $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB F */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_f =  sum_arr($kibf,"Jumlah");
	$harga_f  =  sum_arr($kibf,"Harga");
	$pdf->Row(array("06", "", "", "KONSTRUKSI DALAM PENGERJAAN","", "", "", "","", "", "", "", rp($jumlah_f),rp($harga_f),""));
	$pdf->SetFont('Times','','10');
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
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", "", "-", $row->Asal_usul,tgl_dmy($row->Tgl_Perolehan), '-',"", "-", $row->Jumlah,rp($row->Harga), $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* KIB L */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_l =  sum_arr($kibl,"Jumlah");
	$harga_l  =  sum_arr($kibl,"Harga");
	$pdf->Row(array("07", "", "", "ASET TAK BERWUJUD","", "", "", "","", "", "", "", rp($jumlah_l),rp($harga_l),""));
	$pdf->SetFont('Times','','10');
	if ($jumlahl > 0){
	foreach ($kibl->result() as $row){
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
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", "", "-", $row->Asal_usul,$row->Tahun, '-', "", "-", $row->Jumlah,rp($row->Harga), $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	$jumlah_total = $jumlah_a + $jumlah_b + $jumlah_c + $jumlah_d + $jumlah_e + $jumlah_f + $jumlah_l;
	$harga_total  = $harga_a + $harga_b + $harga_c + $harga_d + $harga_e + $harga_f + $harga_l;

	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", "", "", "TOTAL ","", "", "", "","", "", "", "",rp($jumlah_total), rp($harga_total), ""));

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}

	$pdf->Output("SIMDO_Buku_Inventaris.pdf","I");
?>