<?php

	$this->load->library('PDF_MC_Table_rekap');
	
	$pdf=new PDF_MC_Table_rekap('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"REKAPITULASI ASET/KEKAYAAN DAERAH PER UPB \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');
	$pdf->Ln(20);	

	$kb1 = sprintf ("%02u", $nama_upb['Kd_Bidang']);
	$kb2 = sprintf ("%02u", $nama_upb['Kd_Unit']);
	$kb3 = sprintf ("%02u", $nama_upb['Kd_Sub']);
	$kb4 = sprintf ("%02u", $nama_upb['Kd_UPB']);
	$kode_lokasi  = KODE_LOKASI.'.'.$kb1.'.'.$kb2.'.'.$kb3.'.'.$kb4;
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
	$pdf->SetWidths(array(10,25,75,40,40,40,40,40,40,40,50));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9","10","11"));

	/* REKAP DATA */
	$no=1;
	$pdf->SetFont('Times','','10');

	for ($a=0;$a<$jumlah;$a++){
			$kd1 = sprintf ("%02u", $response[$a]['cell'][1]);
			$kd2 = sprintf ("%02u", $response[$a]['cell'][2]);
			$kd3 = sprintf ("%02u", $response[$a]['cell'][3]);
			$kode = $kd1.'.'.$kd2.'.'.$kd3;
			
			$arr_a[] = $totala = $response[$a]['cell'][5];
			$arr_b[] = $totalb = $response[$a]['cell'][6];
			$arr_c[] = $totalc = $response[$a]['cell'][7];
			$arr_d[] = $totald = $response[$a]['cell'][8];
			$arr_e[] = $totale = $response[$a]['cell'][9];
			$arr_f[] = $totalf = $response[$a]['cell'][10];
			$arr_l[] = $totall = $response[$a]['cell'][11];
			$totalperskpd 	= $response[$a]['cell'][5] + $response[$a]['cell'][6] + $response[$a]['cell'][7] + $response[$a]['cell'][8] + $response[$a]['cell'][9] + $response[$a]['cell'][10] + $response[$a]['cell'][11];
			$pdf->Row(array($response[$a]['cell'][0], $kode, $response[$a]['cell'][4], rp($totala), rp($totalb), rp($totalc), rp($totald), rp($totale), rp($totalf), rp($totall), rp($totalperskpd)));

	}
	
	$total_arr_a   =  array_sum($arr_a);
	$total_arr_b   =  array_sum($arr_b);
	$total_arr_c   =  array_sum($arr_c);
	$total_arr_d   =  array_sum($arr_d);
	$total_arr_e   =  array_sum($arr_e);
	$total_arr_f   =  array_sum($arr_f);
	$total_arr_l   =  array_sum($arr_l);
	$total_arr_all =  $total_arr_a + $total_arr_b + $total_arr_c + $total_arr_d + $total_arr_e + $total_arr_f + $total_arr_l;
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("#", "", "T O T A L", rp($total_arr_a), rp($total_arr_b), rp($total_arr_c), rp($total_arr_d), rp($total_arr_e), rp($total_arr_f), rp($total_arr_l), rp($total_arr_all)));
	
	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}

	$pdf->Output("SIMDO_RekapTotalSKPD.pdf","I");
?>