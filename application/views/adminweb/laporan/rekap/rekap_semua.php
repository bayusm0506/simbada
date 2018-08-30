<?php

	$this->load->library('PDF_MC_Table_rekap');
	
	$pdf=new PDF_MC_Table_rekap('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"REKAPITULASI ASET \n ".NM_PEMDA,0,'C');
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
	$pdf->SetWidths(array(10,25,75,40,40,40,40,40,40,40,50));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9","10","11"));
	
	$pdf->SetAligns(array("C","L","L","R","R","R","R","R","R","R","R"));
	
	/* REKAP DATA */

	for ($a=0;$a<$jumlah;$a++){
			if(!empty($response[$a]['cell'][0])){
				$pdf->SetFont('Times','B','10');
				$pdf->Row(array($response[$a]['cell'][0], $response[$a]['cell'][1], $response[$a]['cell'][2], $response[$a]['cell'][3], $response[$a]['cell'][4], $response[$a]['cell'][5], $response[$a]['cell'][6], $response[$a]['cell'][7], $response[$a]['cell'][8], $response[$a]['cell'][9], $response[$a]['cell'][10]));
			}else{
				$pdf->SetFont('Times','','10');
				$pdf->Row(array($response[$a]['cell'][0], $response[$a]['cell'][1], $response[$a]['cell'][2], $response[$a]['cell'][3], $response[$a]['cell'][4], $response[$a]['cell'][5], $response[$a]['cell'][6], $response[$a]['cell'][7], $response[$a]['cell'][8], $response[$a]['cell'][9], $response[$a]['cell'][10]));
			}
	}
	
	// $pdf->SetFont('Times','B','10');
	// $pdf->Row(array("#", "", "T O T A L", $arr_a, $arr_b, $arr_c, $arr_d, $arr_e, $arr_f, $arr_l, array_sum($arr_t)));
	
	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}

	$pdf->Output("SIMDO_RekapTotalSKPD.pdf","I");
?>