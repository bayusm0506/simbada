<?php

	$this->load->library('PDF_MC_Table_mutasi');
	
	$pdf=new PDF_MC_Table_mutasi('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"LAPORAN MUTASI BARANG \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');

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
	$pdf->SetWidths(array(10,25,15,50,20,20,20,20,20,20,20,15,15,20,15,20,15,30,15,35,20));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14", "15", "16", "17", "18", "19", "20", "21"));
	
	/* KIB A */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_mutasi_kurang_a  = sum_arr($kiba,"Mutasi_Kurang");
	$jumlah_mutasi_kurang_a = sum_arr($kiba,"Jumlah_Kurang");
	$total_mutasi_tambah_a  = sum_arr($kiba,"Mutasi_Tambah");
	$jumlah_mutasi_tambah_a = sum_arr($kiba,"Jumlah_Tambah");
	$total_jumlah_a         = $jumlah_mutasi_tambah_a - $jumlah_mutasi_kurang_a;	
	$total_harga_a          = $total_mutasi_tambah_a - $total_mutasi_kurang_a;
	$pdf->Row(array("01", "", "", "TANAH","", "", "", "","", "", "", "","", "", rp($jumlah_mutasi_kurang_a), rp($total_mutasi_kurang_a), rp($jumlah_mutasi_tambah_a), rp($total_mutasi_tambah_a), rp($total_jumlah_a), rp($total_harga_a), ""));
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
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", $row->Sertifikat_Nomor, "-", $row->Asal_usul,$row->Tahun, $row->Luas_M2,
		 "M2", "-","-", "-", $row->Jumlah_Kurang, rp($row->Mutasi_Kurang),$row->Jumlah_Tambah, rp($row->Mutasi_Tambah), $row->Jumlah_Tambah, rp($row->Mutasi_Tambah - $row->Mutasi_Kurang), $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	
	/* KIB B */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_mutasi_kurang_b  = sum_arr($kibb,"Mutasi_Kurang");
	$jumlah_mutasi_kurang_b = sum_arr($kibb,"Jumlah_Kurang");
	$total_mutasi_tambah_b  = sum_arr($kibb,"Mutasi_Tambah");
	$jumlah_mutasi_tambah_b = sum_arr($kibb,"Jumlah_Tambah");
	$total_jumlah_b         = $jumlah_mutasi_tambah_b - $jumlah_mutasi_kurang_b;	
	$total_harga_b          = $total_mutasi_tambah_b - $total_mutasi_kurang_b;
	$pdf->Row(array("02", "", "", "PERALATAN DAN MESIN","", "", "", "","", "", "", "","", "", rp($jumlah_mutasi_kurang_b), rp($total_mutasi_kurang_b), rp($jumlah_mutasi_tambah_b), rp($total_mutasi_tambah_b), rp($total_jumlah_b), rp($total_harga_b), ""));
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
		
		if ($row->Kondisi == 1){
			$kondisi = 'B';
		}elseif ($row->Kondisi == 2){
			$kondisi = 'KB';
		}else{
			$kondisi = 'RB';
		}
		
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$merk, "-", $row->Bahan,  $row->Asal_usul,$row->Tahun, $row->CC, '',
		$kondisi,"-", "-", $row->Jumlah_Kurang, rp($row->Mutasi_Kurang),$row->Jumlah_Tambah, rp($row->Mutasi_Tambah), $row->Jumlah_Tambah - $row->Jumlah_Kurang, rp($row->Mutasi_Tambah - $row->Mutasi_Kurang), $row->Keterangan));		
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	
	/* KIB C */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_mutasi_kurang_c  = sum_arr($kibc,"Mutasi_Kurang");
	$jumlah_mutasi_kurang_c = sum_arr($kibc,"Jumlah_Kurang");
	$total_mutasi_tambah_c  = sum_arr($kibc,"Mutasi_Tambah");
	$jumlah_mutasi_tambah_c = sum_arr($kibc,"Jumlah_Tambah");
	$total_jumlah_c         = $jumlah_mutasi_tambah_c - $jumlah_mutasi_kurang_c;	
	$total_harga_c          = $total_mutasi_tambah_c - $total_mutasi_kurang_c;
	$pdf->Row(array("03", "", "", "GEDUNG DAN BANGUNAN","", "", "", "","", "", "", "","", "", rp($jumlah_mutasi_kurang_c), rp($total_mutasi_kurang_c), rp($jumlah_mutasi_tambah_c), rp($total_mutasi_tambah_c), rp($total_jumlah_c), rp($total_harga_c), ""));
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

		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", $row->Dokumen_Nomor, "-", $row->Asal_usul,$row->Tahun, $row->Luas_Lantai,
		 "M2", "-","-", "-", $row->Jumlah_Kurang, rp($row->Mutasi_Kurang),$row->Jumlah_Tambah, rp($row->Mutasi_Tambah), $row->Jumlah_Tambah, rp($row->Mutasi_Tambah - $row->Mutasi_Kurang), $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB D */
	$no=1;	
	$pdf->SetFont('Times','B','10');
	$total_mutasi_kurang_d  = sum_arr($kibd,"Mutasi_Kurang");
	$jumlah_mutasi_kurang_d = sum_arr($kibd,"Jumlah_Kurang");
	$total_mutasi_tambah_d  = sum_arr($kibd,"Mutasi_Tambah");
	$jumlah_mutasi_tambah_d = sum_arr($kibd,"Jumlah_Tambah");
	$total_jumlah_d         = $jumlah_mutasi_tambah_d - $jumlah_mutasi_kurang_d;	
	$total_harga_d          = $total_mutasi_tambah_d - $total_mutasi_kurang_d;
	$pdf->Row(array("04", "", "", "JALAN, IRIGASI DAN JARINGAN","", "", "", "","", "", "", "","", "", rp($jumlah_mutasi_kurang_d), rp($total_mutasi_kurang_d), rp($jumlah_mutasi_tambah_d), rp($total_mutasi_tambah_d), rp($total_jumlah_d), rp($total_harga_d), ""));
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
		
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", "", "-", $row->Asal_usul,$row->Tahun, $row->Luas, "M2", "-","-", "-", $row->Jumlah_Kurang, rp($row->Mutasi_Kurang),$row->Jumlah_Tambah, rp($row->Mutasi_Tambah), $row->Jumlah_Tambah, rp($row->Mutasi_Tambah - $row->Mutasi_Kurang), $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB E */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_mutasi_kurang_e  = sum_arr($kibe,"Mutasi_Kurang");
	$jumlah_mutasi_kurang_e = sum_arr($kibe,"Jumlah_Kurang");
	$total_mutasi_tambah_e  = sum_arr($kibe,"Mutasi_Tambah");
	$jumlah_mutasi_tambah_e = sum_arr($kibe,"Jumlah_Tambah");
	$total_jumlah_e         = $jumlah_mutasi_tambah_e - $jumlah_mutasi_kurang_e;	
	$total_harga_e          = $total_mutasi_tambah_e - $total_mutasi_kurang_e;
	$pdf->Row(array("05", "", "", "ASET TETAP LAINYA","", "", "", "","", "", "", "","", "", rp($jumlah_mutasi_kurang_e), rp($total_mutasi_kurang_e), rp($jumlah_mutasi_tambah_e), rp($total_mutasi_tambah_e), rp($total_jumlah_e), rp($total_harga_e), ""));
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

		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", "", "-", '',$row->Tahun, '-',"", "-","-", "-",$row->Jumlah_Kurang, rp($row->Mutasi_Kurang),$row->Jumlah_Tambah, rp($row->Mutasi_Tambah), $row->Jumlah_Tambah, rp($row->Mutasi_Tambah - $row->Mutasi_Kurang), $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB F */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_mutasi_kurang_f  = sum_arr($kibf,"Mutasi_Kurang");
	$jumlah_mutasi_kurang_f = sum_arr($kibf,"Jumlah_Kurang");
	$total_mutasi_tambah_f  = sum_arr($kibf,"Mutasi_Tambah");
	$jumlah_mutasi_tambah_f = sum_arr($kibf,"Jumlah_Tambah");
	$total_jumlah_f         = $jumlah_mutasi_tambah_f - $jumlah_mutasi_kurang_f;	
	$total_harga_f          = $total_mutasi_tambah_f - $total_mutasi_kurang_f;
	$pdf->Row(array("06", "", "", "KONSTRUKSI DALAM PENGERJAAN","", "", "", "","", "", "", "","", "", rp($jumlah_mutasi_kurang_f), rp($total_mutasi_kurang_f), rp($jumlah_mutasi_tambah_f), rp($total_mutasi_tambah_f), rp($total_jumlah_f), rp($total_harga_f), ""));
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
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", "", "-", '',$row->Tahun, '-',"", "-","-", "-",$row->Jumlah_Kurang, rp($row->Mutasi_Kurang),$row->Jumlah_Tambah, rp($row->Mutasi_Tambah), $row->Jumlah_Tambah, rp($row->Mutasi_Tambah - $row->Mutasi_Kurang), $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* Aset Lainnya */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_mutasi_kurang_l  = sum_arr($kibl,"Mutasi_Kurang");
	$jumlah_mutasi_kurang_l = sum_arr($kibl,"Jumlah_Kurang");
	$total_mutasi_tambah_l  = sum_arr($kibl,"Mutasi_Tambah");
	$jumlah_mutasi_tambah_l = sum_arr($kibl,"Jumlah_Tambah");
	$total_jumlah_l         = $jumlah_mutasi_tambah_l - $jumlah_mutasi_kurang_l;	
	$total_harga_l          = $total_mutasi_tambah_l - $total_mutasi_kurang_l;
	$pdf->Row(array("06", "", "", "Aset Lainnya","", "", "", "","", "", "", "","", "", rp($jumlah_mutasi_kurang_l), rp($total_mutasi_kurang_l), rp($jumlah_mutasi_tambah_l), rp($total_mutasi_tambah_l), rp($total_jumlah_l), rp($total_harga_l), ""));
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
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,"-", "", "-", '',$row->Tahun, '-',"", "-","-", "-",$row->Jumlah_Kurang, rp($row->Mutasi_Kurang),$row->Jumlah_Tambah, rp($row->Mutasi_Tambah), $row->Jumlah_Tambah, rp($row->Mutasi_Tambah - $row->Mutasi_Kurang), $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	$total_mutasi_kurang  = $total_mutasi_kurang_a + $total_mutasi_kurang_b + $total_mutasi_kurang_c + $total_mutasi_kurang_d + $total_mutasi_kurang_e + $total_mutasi_kurang_l;
	$jumlah_mutasi_kurang = $jumlah_mutasi_kurang_a + $jumlah_mutasi_kurang_b + $jumlah_mutasi_kurang_c + $jumlah_mutasi_kurang_d + $jumlah_mutasi_kurang_e + $jumlah_mutasi_kurang_l;
	$total_mutasi_tambah  = $total_mutasi_tambah_a + $total_mutasi_tambah_b + $total_mutasi_tambah_c + $total_mutasi_tambah_d + $total_mutasi_tambah_e + $total_mutasi_tambah_f + + $total_mutasi_tambah_l;
	$jumlah_mutasi_tambah = $jumlah_mutasi_tambah_a + $jumlah_mutasi_tambah_b + $jumlah_mutasi_tambah_c + $jumlah_mutasi_tambah_d + $jumlah_mutasi_tambah_e + $jumlah_mutasi_tambah_f + $jumlah_mutasi_tambah_l;
	
	$total_mutasi         = $total_mutasi_tambah - $total_mutasi_kurang;
	$jumlah_mutasi        = $jumlah_mutasi_tambah - $jumlah_mutasi_kurang;

	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", "", "", "TOTAL ","", "", "", "","", "", "", "","", "", rp($jumlah_mutasi_kurang), rp($total_mutasi_kurang), rp($jumlah_mutasi_tambah), rp($total_mutasi_tambah), rp($jumlah_mutasi), rp($total_mutasi), ""));
	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}

	$pdf->Output("SIMDO_Mutasi.pdf","I");
?>