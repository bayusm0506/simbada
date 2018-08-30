<?php

	$this->load->library('PDF_MC_Table_KIR');
	
	$pdf=new PDF_MC_Table_KIR('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"KARTU INVENTARIS RUANGAN (KIR)\n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,$periode,0,'C');
	
	$kb1 = sprintf ("%02u", $nama_upb['Kd_Bidang']);
	$kb2 = sprintf ("%02u", $nama_upb['Kd_Unit']);
	$kb3 = sprintf ("%02u", $nama_upb['Kd_Sub']);
	$kb4 = sprintf ("%02u", $nama_upb['Kd_UPB']);
	$kode_lokasi  = KODE_LOKASI.'.'.$kb1.'.'.$kb2.'.'.$kb3.'.'.$kb4;
	$kode_lokasi2 = KODE_LOKASI;
	$pdf->UPBTitle($nama_upb['Nm_bidang'],$nama_upb['Nm_unit'],$nama_upb['Nm_sub_unit'],$nama_upb['Nm_UPB'],$kode_lokasi,$ta_ruang->Nm_Ruang);


	$pdf->Ln();	
	$tgl=date('Y-m-d');
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(10,60,40,30,30,40,25,35,20,30,30,30,60));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13"));
	$no=1;	
	if ($jumlah > 0){
		$pdf->SetFont('Times','','10');	
		foreach ($kir->result() as $row){
			$kd1          = sprintf ("%02u", $row->Kd_Aset1);
			$kd2          = sprintf ("%02u", $row->Kd_Aset2);
			$kd3          = sprintf ("%02u", $row->Kd_Aset3);
			$kd4          = sprintf ("%02u", $row->Kd_Aset4);
			$kd5          = sprintf ("%02u", $row->Kd_Aset5);
			$kodebarang   = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
			
			$min_register = sprintf ("%04u", $row->min_register);
			$max_register = sprintf ("%04u", $row->max_register);
			if ($row->Jumlah > 1){
				$register = $min_register." s/d ".$max_register;
			}else{
				$register = $min_register;
			}
			
			$pdf->Row(array($no, $row->Nm_Aset5, $row->Merk, $row->Nomor_Pabrik,$row->CC, $row->Bahan,$row->Tahun,$kodebarang,$register, $row->Baik, $row->Kurang_baik,$row->Rusak, $row->Keterangan));
			$no++;
		}
	}else{
		$pdf->nihil();
	}

	// $harga_total	= number_format($total->Harga,0,",",".");
	// $pdf->SetFont('Times','B','10');
	// $pdf->Row(array('', 'Jumlah Harga', '','',"", "", "", "",$total->Jumlah." Unit", "", "",'', ""));

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}
	
	$pdf->Output("SIMDO_kir.pdf","I");
?>