<?php

	$this->load->library('PDF_MC_Table_kendaraan');
	
	$pdf=new PDF_MC_Table_kendaraan('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"DAFTAR KENDARAAN DINAS \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');
	$pdf->Ln(20);
	$kode_lokasi = KODE_LOKASI;
	$pdf->UPBTitle2($nama_upb->Nm_bidang,$nama_upb->Nm_unit,$nama_upb->Nm_sub_unit,$nama_upb->Nm_UPB,$kode_lokasi);

	$pdf->Ln();	
	$tgl=date('Y-m-d');
	
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(15,30,20,17,40,30,20,18,15,15,25,22,15,18,25,35,50,30));
	$pdf->Rowheader2();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14", "15","16", "17", "18"));
	
	/* KIB B */
	$no=1;
	$pdf->SetFont('Times','','10');
	$total_kendaraan		= number_format($count->Harga,0,",",".");	
	$pdf->Row(array("#", "", "", " ", "","", "", "", "","", "", "", "","", $count->Jumlah, $total_kendaraan,"", ""));
	if ($jumlah_kendaraan > 0){
	foreach ($kendaraan->result() as $row){
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
		
		$harga		= number_format($row->Harga,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Jumlah_Roda,$row->Nm_Aset5,$merk, $row->Nomor_Rangka, $row->Nomor_Mesin, $row->Nomor_BPKB, $row->Nomor_Polisi, $row->Asal_usul,$row->Tahun, 'Unit', $kondisi,$row->Jumlah, $harga,$row->Pemakai,$row->Nm_UPB));		
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", "", "", "TOTAL ","", "", "", "","", "", "","", "", "",$count->Jumlah, $total_kendaraan, "", ""));

	$kepaladaerah 			= "NAMA PIMPINAN";
	$nipkepaladaerah 		= "NIP PIMPINAN";
	$jabatankepaladaerah 	= "JABATAN PIMPINAN";
	$namapengurus 			= "NAMA PPENGURUS";
	$nippengurus 			= "NIP PENGURUS";
	

	$pdf->ttd2($kepaladaerah,$nipkepaladaerah,$jabatankepaladaerah,$namapengurus,$nippengurus,$tanggal);

	$pdf->Output("SIMDO_Kendaraan_induk.pdf","I");
?>