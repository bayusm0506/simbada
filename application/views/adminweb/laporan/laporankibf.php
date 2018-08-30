<?php

	$this->load->library('PDF_MC_Table_Kib_F');
	
	$pdf=new PDF_MC_Table_Kib_F('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"KARTU INVENTARIS BARANG (KIB) \n F. KONSTRUKSI DALAM PENGERJAAN \n ".NM_PEMDA,0,'C');
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
	$pdf->SetWidths(array(10,55,30,30,30,20,45,25,25,25,30,25,30,30,30));
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
		
		$pdf->Row(array($no, $row->Nm_Aset5, $kondisi, $row->Bertingkat_Tidak, $row->Beton_tidak, $row->Luas_Lantai,
                            $row->Lokasi, $Dokumen_tanggal, $row->Dokumen_Nomor, $Tgl_Perolehan, $row->Status_Tanah, '-',
                            $row->Asal_usul, rp($row->Harga), $row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil();
	}
	$jumlah_total =  sum_arr($kibf,"Jumlah");
	$harga_total  =  sum_arr($kibf,"Harga");
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('', rp($jumlah_total)." Unit", '','',"", "", "", "","", "", "","Jumlah Harga", "",rp($harga_total), ""));

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}

	$pdf->Output("SIMDO_kibf.pdf","I");
?>