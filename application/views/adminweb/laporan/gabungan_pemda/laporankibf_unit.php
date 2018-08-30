<?php

	$this->load->library('PDF_MC_Table_kib_f');
	
	$pdf=new PDF_MC_Table_kib_f('L','mm','f4');
	$pdf->setLeftMargin(20);
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"KARTU INVENTARIS BARANG (KIB) \nF. KONSTRUKSI DALAM PENGERJAAN \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');
	$pdf->Ln(20);
	$kode_lokasi = KODE_LOKASI;
	$pdf->UPBTitle2($nama_upb->Nm_bidang,$nama_upb->Nm_unit,$nama_upb->Nm_sub_unit,$nama_upb->Nm_UPB,$kode_lokasi);

	$pdf->Ln();	
	$tgl=date('Y-m-d');
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(10,35,30,30,30,20,35,25,25,25,30,25,30,30,35));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14", "15"));
        
	$no=1;
	$pdf->SetFont('Times','','10');	
	if ($jumlah > 0){
	foreach ($kibf->result() as $row){
		$kd1 = sprintf ("%02u", $row->Kd_Aset1);
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$kd3 = sprintf ("%02u", $row->Kd_Aset3);
		$kd4 = sprintf ("%02u", $row->Kd_Aset4);
		$kd5 = sprintf ("%02u", $row->Kd_Aset5);
		$kodetanah = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
		$tahun 		= date('Y', strtotime($row->Tgl_Perolehan));
		//$tgl_sertifikat 		= date('d/m/Y', strtotime($row->Sertifikat_Tanggal));
		$harga		= number_format($row->Harga,0,",",".");
		//$register	= sprintf('%04d',$row->No_Register);
        $Dokumen_tanggal = date('d/m/Y', strtotime($row->Dokumen_Tanggal));
        $Tgl_Perolehan = date('d/m/Y', strtotime($row->Tgl_Perolehan));
		
		if($row->Kondisi == '1'){
			$kondisi = "Permnanen";
		}else if($row->Kondisi == '2'){
			$kondisi = "Semi Permanen";	
		}else{
			$kondisi = "Darurat";	
		}
		
		$pdf->Row(array(
                            $no, $row->Nm_Aset5, $kondisi, $row->Bertingkat_Tidak, $row->Beton_tidak, $row->Luas_Lantai,
                            $row->Lokasi, $Dokumen_tanggal, $row->Dokumen_Nomor, $Tgl_Perolehan, $row->Status_Tanah, '-',
                            $row->Asal_usul, $harga, $row->Keterangan
                            ));
		$no++;
	}
	}else{
		$pdf->nihil();
	}
	$harga_total		= number_format($total,0,",",".");
	$pdf->SetFont('Times','','10');
	$pdf->Row(array('', '', '','',"", "", "", "","", "", "","Jumlah Harga", "",$harga_total, ""));

	$pdf->ttd2($ta_upb->Nm_Pimpinan,$ta_upb->Nip_Pimpinan,$ta_upb->Jbt_Pimpinan,$ta_upb->Nm_Pengurus,$ta_upb->Nip_Pengurus,$tanggal);
	$pdf->Output("SIMDO_kibf.pdf","I");
?>