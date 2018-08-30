<?php

	$this->load->library('PDF_MC_Table_kib_d');
	
	$pdf=new PDF_MC_Table_kib_d('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"KARTU INVENTARIS BARANG (KIB) \n D. JALAN, IRIGASI DAN JARINGAN \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');
	$pdf->Ln(20);
	$kode_lokasi = KODE_LOKASI;
	$pdf->UPBTitle2($nama_upb->Nm_bidang,$nama_upb->Nm_unit,$nama_upb->Nm_sub_unit,$nama_upb->Nm_UPB,$kode_lokasi);

	$pdf->Ln();	
	$tgl=date('Y-m-d');
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(10,60,40,20,30,20,20,20,30,20,20,20,20,20,35,22,33));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14", "15", "16", "17"));

	$no=1;
	$pdf->SetFont('Times','','10');	
	if ($jumlah > 0){
	foreach ($kibd->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
		
		$harga		= number_format($row->Harga,0,",",".");
		$tgl_dokumen 		= date('d/m/Y', strtotime($row->Dokumen_Tanggal));
		$min_register = sprintf ("%04u", $row->min_register);
		$max_register = sprintf ("%04u", $row->max_register);
		if ($row->jumlah_register > 1){
			$register = $min_register." s/d ".$max_register;
		}else{
			$register = $min_register;
		}
		
		if ($row->Kondisi == 1){
			$kondisi = 'Baik';
		}elseif ($row->Kondisi == 2){
			$kondisi = 'Kurang Baik';
		}else{
			$kondisi = 'Rusak Berat';
		}
		
		$pdf->Row(array($no,  $row->Nm_Aset5, $kodebarang, $register,$row->Konstruksi,$row->Panjang, $row->Lebar,$row->Luas,
		$row->Lokasi, $tgl_dokumen, $row->Dokumen_Nomor,$row->Status_Tanah,"",$row->Asal_usul, $harga, $kondisi, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil();
	}
	$harga_total		= number_format($total,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('', "Jumlah Harga", '','',"", "", "", "", "", "", "","", "", "", $harga_total,"", ""));

	$pdf->ttd2($ta_upb->Nm_Pimpinan,$ta_upb->Nip_Pimpinan,$ta_upb->Jbt_Pimpinan,$ta_upb->Nm_Pengurus,$ta_upb->Nip_Pengurus,$tanggal);
	
	$pdf->Output("SIMDO_kibd.pdf","I");
?>