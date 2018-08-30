<?php

	$this->load->library('gabungan_pemda/PDF_MC_Table_kib_c_unit');
	
	$pdf=new PDF_MC_Table_kib_c_unit('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"KARTU INVENTARIS BARANG (KIB) \n C . GEDUNG DAN BANGUNAN \n KABUPATEN PROVINSI SUMATERA UTARA",0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');
	$pdf->Ln(20);
	$kode_lokasi = KODE_LOKASI;
	$pdf->UPBTitle($nama_upb->Nm_bidang,$nama_upb->Nm_unit,$nama_upb->Nm_sub_unit,$nama_upb->Nm_UPB,$kode_lokasi);

	$pdf->Ln();	
	$tgl=date('Y-m-d');
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(10,60,40,20,30,25,25,20,30,20,20,15,20,20,20,35,30));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14", "15", "16", "17"));

	$no=1;
	$pdf->SetFont('Times','','10');	
	if ($jumlah > 0){
		foreach ($kibc->result() as $row){
			$kodebarang = $row->Kd_Aset1.'.'.$row->Kd_Aset2.'.'.$row->Kd_Aset3.'.'.$row->Kd_Aset4.'.'.$row->Kd_Aset5;
			
			$min_register = sprintf ("%04u", $row->min_register);
			$max_register = sprintf ("%04u", $row->max_register);
			if ($row->jumlah_register > 1){
				$register = $min_register." s/d ".$max_register;
			}else{
				$register = $min_register;
			}
			
			if ($row->Kondisi == 1){
				$kondisi = 'B';
			}elseif ($row->Kondisi == 2){
				$kondisi = 'KB';
			}else{
				$kondisi = 'RB';
			}
			
			//$tahun 		= date('Y', strtotime($row->Tgl_Perolehan));
			$tgl_dokumen 		= date('d/m/Y', strtotime($row->Dokumen_Tanggal));
			$harga		= number_format($row->Harga,0,",",".");
			//$register	= sprintf('%04d',$row->No_Register);
			/*
			
*/			$pdf->Row(array($no, $row->Nm_Aset5, $kodebarang, $register,$kondisi,$row->Bertingkat_Tidak, $row->Beton_Tidak, $row->Luas_Lantai,
							$row->Lokasi,$tgl_dokumen, $row->Dokumen_Nomor,'', $row->Status_Tanah,'',$row->Asal_usul, $harga, $row->Nm_UPB));
			$no++;
		}
	}else{
		$pdf->nihil();
	}

	$harga_total		= number_format($total,0,",",".");
	$pdf->SetFont('Times','','10');
	$pdf->Row(array('', "Jumlah Harga", '','',"", "", "", "", "", "", "","", "", "", "",$harga_total, ""));

	$pdf->ttd($ta_upb->Nm_Pimpinan,$ta_upb->Nip_Pimpinan,$ta_upb->Jbt_Pimpinan,$ta_upb->Nm_Pengurus,$ta_upb->Nip_Pengurus,$tanggal);
	
	$pdf->Output("SIMDO_kibc.pdf","I");
?>