<?php

	$this->load->library('akuntansi/PDF_MC_Table_Intra');
	
	$pdf=new PDF_MC_Table_Intra('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,$title."\n ".NM_PEMDA,0,'C');
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
	$pdf->SetWidths(array(10,60,40,30,30,40,25,35,20,40,20,20,20,50));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14"));
	
	/* KIB B */
	$no=1;
	$pdf->SetFont('Arial','B','10');
	$jumlah_kibb = sum_arr($kibb,'Jumlah');
	$harga_kibb  = sum_arr($kibb,'Harga');
	$B_kibb      = sum_arr($kibb,'Baik');
	$KB_kibb     = sum_arr($kibb,'Kurang_baik');
	$RB_kibb     = sum_arr($kibb,'Rusak');

	$pdf->Row(array("02", "PERALATAN DAN MESIN", "", "","", "", "", "",nilai($jumlah_kibb), rp($harga_kibb), nilai($B_kibb), nilai($KB_kibb),nilai($RB_kibb), ""));		
	if ($jumlahb > 0){
		$pdf->SetFont('Times','','10');	
		foreach ($kibb->result() as $row){
			$harga		= rp($row->Harga);
			$kd1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd5 = sprintf ("%02u", $row->Kd_Aset5);
			$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
			
			$pdf->Row(array($no, $row->Nm_Aset5, $row->Merk, $row->Nomor_Pabrik,$row->CC, $row->Bahan,$row->Tahun,$kodebarang,$row->Jumlah,
			$harga, $row->Baik, $row->Kurang_baik,$row->Rusak, $row->Keterangan));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}

	/* KIB C */
	$no=1;
	$pdf->SetFont('Arial','B','10');
	$jumlah_kibc = sum_arr($kibc,'Jumlah');
	$harga_kibc  = sum_arr($kibc,'Harga');
	$B_kibc      = sum_arr($kibc,'Baik');
	$KB_kibc     = sum_arr($kibc,'Kurang_baik');
	$RB_kibc     = sum_arr($kibc,'Rusak');
	$pdf->Row(array("03", "GEDUNG & BANGUNAN", "", "","", "", "", "",nilai($jumlah_kibc), rp($harga_kibc), nilai($B_kibc), nilai($KB_kibc),nilai($RB_kibc), ""));
	if ($jumlahc > 0){
		$pdf->SetFont('Times','','10');	
		foreach ($kibc->result() as $row){
			$harga		= rp($row->Harga);
			$kd1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd5 = sprintf ("%02u", $row->Kd_Aset5);
			$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
			
			$pdf->Row(array($no, $row->Nm_Aset5, '', '','', '','',$kodebarang,$row->Jumlah,
			$harga, $row->Baik, $row->Kurang_baik,$row->Rusak, $row->Keterangan));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB D */
	$no=1;
	$pdf->SetFont('Arial','B','10');
	$jumlah_kibd = sum_arr($kibd,'Jumlah');
	$harga_kibd  = sum_arr($kibd,'Harga');
	$B_kibd      = sum_arr($kibd,'Baik');
	$KB_kibd     = sum_arr($kibd,'Kurang_baik');
	$RB_kibd     = sum_arr($kibd,'Rusak');
	$pdf->Row(array("04", "JALAN, IRIGASI & JARINGAN", "", "","", "", "", "",nilai($jumlah_kibd), rp($harga_kibd), nilai($B_kibd), nilai($KB_kibd),nilai($RB_kibd), ""));
	if ($jumlahd > 0){
		$pdf->SetFont('Times','','10');	
		foreach ($kibd->result() as $row){
			$harga		= rp($row->Harga);
			$kd1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd5 = sprintf ("%02u", $row->Kd_Aset5);
			$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
			
			$pdf->Row(array($no, $row->Nm_Aset5, '', '','', '','',$kodebarang, $row->Jumlah,
			$harga, $row->Baik, $row->Kurang_baik,$row->Rusak, $row->Keterangan));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}
	
	/* KIB E */
	$no=1;
	$pdf->SetFont('Arial','B','10');
	$jumlah_kibe = sum_arr($kibe,'Jumlah');
	$harga_kibe  = sum_arr($kibe,'Harga');
	$B_kibe      = sum_arr($kibe,'Baik');
	$KB_kibe     = sum_arr($kibe,'Kurang_baik');
	$RB_kibe     = sum_arr($kibe,'Rusak');	
	$pdf->Row(array("05", "ASET TETAP LAINYA", "", "","", "", "", "",nilai($jumlah_kibe), rp($harga_kibe), nilai($B_kibe), nilai($KB_kibe),nilai($RB_kibe), ""));
	if ($jumlahe > 0){
		$pdf->SetFont('Times','','10');	
		foreach ($kibe->result() as $row){
			$harga		= rp($row->Harga);
			$kd1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd5 = sprintf ("%02u", $row->Kd_Aset5);
			$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
			
			$pdf->Row(array($no, $row->Nm_Aset5, '', '','', '','',$kodebarang, $row->Jumlah,
			$harga, $row->Baik, $row->Kurang_baik,$row->Rusak, $row->Keterangan));
			$no++;
		}
	}else{
		$pdf->nihil2();
	}

	$jumlah_total = $jumlah_kibb + $jumlah_kibc + $jumlah_kibd + $jumlah_kibe;
	$harga_total  = $harga_kibb + $harga_kibc + $harga_kibd + $harga_kibe;
	$B_total      = $B_kibb + $B_kibc + $B_kibd + $B_kibe;
	$KB_total     = $KB_kibb + $KB_kibc + $KB_kibd + $KB_kibe;
	$RB_total     = $RB_kibb + $RB_kibc + $RB_kibd + $RB_kibe;

	$pdf->SetFont('Arial','B','10');
	$pdf->Row(array('', 'Jumlah Harga', '','',"", "", "", "", nilai($jumlah_total), rp($harga_total), nilai($B_total), nilai($KB_total),nilai($RB_total), ""));

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}

	$pdf->Output("SIMDO_Intrakomptabel.pdf","I");
?>