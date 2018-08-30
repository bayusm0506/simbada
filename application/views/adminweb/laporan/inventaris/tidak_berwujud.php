<?php

	$this->load->library('inventarisasi/pdf_mc_non_op');
	
	$pdf=new PDF_MC_Non_Op('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,$title." \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');

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
	$pdf->SetWidths(array(15,30,20,60,30,30,20,25,25,25,20,25,25,40,50));
	if($ttd_pengurus){
		$pdf->Rowheader();
	}else{
		$pdf->Rowheader2();
	}
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14", "15"));
	
	
	/* KIB B */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	// $pdf->Row(array("02", "", "", "PERALATAN DAN MESIN","", "", "", "","", "", "", "", rp($totalb->Jumlah), $totalkibb,  ""));
	$pdf->SetFont('Times','','10');
	if ($jumlah > 0){
	foreach ($kib->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;

		$register = sprintf ("%04u", $row->No_Register);
		
		if ($row->Kondisi == 1){
			$kondisi = 'B';
		}elseif ($row->Kondisi == 2){
			$kondisi = 'KB';
		}else{
			$kondisi = 'RB';
		}
		
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$row->Judul, $row->Pencipta, $row->Bahan,  $row->Asal_usul,thn($row->Tgl_Perolehan), $row->Ukuran, '',$kondisi,'', rp($row->Harga), $row->Keterangan));	
		$no++;
	}
	}else{
		$pdf->nihil2();
	}
	
	

	// $jumlah_total = $totalb->Jumlah+$totalc->Jumlah+$totald->Jumlah+$totald->Jumlah;
	
	// $tot_b = ($totalb->Harga + $totalb->Kapitalisasi + $totalb->Koreksi_Tambah) - ($totalb->Penghapusan + $totalb->Koreksi_Kurang);
	// $tot_c = ($totalc->Harga + $totalc->Kapitalisasi + $totalc->Koreksi_Tambah) - ($totalc->Penghapusan + $totalc->Koreksi_Kurang);
	// $tot_d = ($totald->Harga + $totald->Kapitalisasi + $totald->Koreksi_Tambah) - ($totald->Penghapusan + $totald->Koreksi_Kurang);
	// $tot_e = ($totale->Harga + $totale->Kapitalisasi + $totale->Koreksi_Tambah) - ($totale->Penghapusan + $totale->Koreksi_Kurang);
	// $harga_total	= $tot_b + $tot_c + $tot_d + $tot_e;
	$harga_total = sum_arr($kib,'Harga');	
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", "", "", "TOTAL ","", "", "", "","", "", "", "","", rp($harga_total), ""));

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}
	$pdf->Output("SIMDO_InventarisKondisi.pdf","I");
?>