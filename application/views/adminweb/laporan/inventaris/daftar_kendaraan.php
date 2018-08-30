<?php

	$this->load->library('PDF_MC_Table_kendaraan');
	
	$pdf=new PDF_MC_Table_kendaraan('L','mm','f4');
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
	$pdf->SetWidths(array(15,30,20,17,40,30,20,18,15,15,25,22,15,18,25,35,50,30));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14", "15","16", "17", "18"));

	/* KIB B - DAFTAR KENDARAAN */
	$no=1;
	$pdf->SetFont('Times','','10');
	if ($jumlahb > 0){
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
		
		if ($row->LastKondisi == 1){
			$kondisi = 'B';
		}elseif ($row->LastKondisi == 2){
			$kondisi = 'KB';
		}else{
			$kondisi = 'RB';
		}
		
		$pdf->Row(array($no, $kodebarang, $register, $row->Jumlah_Roda,$row->Nm_Aset5,$merk, $row->Nomor_Rangka, $row->Nomor_Mesin, $row->Nomor_BPKB, $row->Nomor_Polisi, $row->Asal_usul,$row->Tahun, 'Unit', $kondisi,$row->Jumlah, rp($row->Harga),$row->Pemakai,$row->Keterangan));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	$pdf->SetFont('Times','B','10');
	
	$jumlah_b =  sum_arr($kibb,"Jumlah");
	$harga_b  =  sum_arr($kibb,"Harga");
	$pdf->Row(array("", "", "", "TOTAL ","", "", "", "","", "", "","", "", "",rp($jumlah_b), rp($harga_b), "", ""));


	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}
	$pdf->Output("SIMDO_DaftarKendaraan.pdf","I");
?>