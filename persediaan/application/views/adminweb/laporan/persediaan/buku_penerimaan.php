<?php

	$this->load->library('persediaan/PDF_Buku_Penerimaan');
	
	$pdf=new PDF_Buku_Penerimaan('L','mm','f4');
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
	$kode_lokasi  = "11.02.0.".$kb1.'.'.$kb2.'.'.$kb3.'.'.$kb4;
	$kode_lokasi2 = "11.02.0";

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
	$pdf->SetWidths(array(15,30,50,30,30,30,70,30,30,30,30,30,35));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13"));
	$pdf->SetAligns(array("C", "C", "L", "C","C", "C", "L", "C","R", "R", "C", "C","L"));
	
	/* KIB B */
	$no=1;
	$pdf->SetFont('Arial','B','10');
	$total = 0;
	if ($jumlah > 0){
		$pdf->SetFont('Times','','10');	
		
		foreach ($data->result() as $row){

			$kd1 = sprintf ("%02u", $row->Kd_Aset1);
			$kd2 = sprintf ("%02u", $row->Kd_Aset2);
			$kd3 = sprintf ("%02u", $row->Kd_Aset3);
			$kd4 = sprintf ("%02u", $row->Kd_Aset4);
			$kd5 = sprintf ("%02u", $row->Kd_Aset5);
			$kd6 = sprintf ("%02u", $row->Kd_Aset6);
			$kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5.'.'.$kd6;
			$pdf->Row(array($no,tgl_dmy($row->Tgl_Diterima),$row->Nm_Rekanan,$row->No_Kontrak,$row->Tgl_Kontrak,$kodebarang, $row->Nm_Aset6." ".$row->Spesifikasi, $row->Jumlah." ".$row->Nm_Satuan,rp($row->Harga),rp($row->Jumlah * $row->Harga),$row->No_BA_Pemeriksaan, tgl_dmy($row->Tgl_BA_Pemeriksaan),$row->Keterangan));
			$no++;
			$total += ($row->Jumlah * $row->Harga);
		}
		$pdf->SetFont('Times','B','10');	
		$pdf->Row(array("","","","","","","","","",rp($total),"","",""));
	}else{
		$pdf->nihil2();
	}

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}

	$pdf->Output("SIMDO_Buku_Penerimaan.pdf","I");
?>