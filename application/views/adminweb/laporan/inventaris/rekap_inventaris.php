<?php

	$this->load->library('PDF_MC_Table_rinventaris');
	
	$pdf=new PDF_MC_Table_rinventaris('P','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(280,5,"REKAPITULASI BUKU INVENTARIS \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(280,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(280,5,$periode,0,'C');
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
	$pdf->SetWidths(array(15,30,30,80,30,45,50));
	if($ttd_pengurus){
		$pdf->Rowheader();
	}else{
		$pdf->Rowheader(); /*untuk sementara sama dulu*/
	}
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7"));
	
	/* KIB A */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$jumlah_a =  sum_arr($kiba,"Jumlah");
	$harga_a  =  sum_arr($kiba,"Harga");
	$pdf->Row(array("1", "01", "", "TANAH",rp($jumlah_a), rp($harga_a), ""));
	if ($jumlaha > 0){
	foreach ($kiba->result() as $row){
		$kd2 = sprintf ("%02u", $row->Kd_Aset2);
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2,rp($row->Jumlah), rp($row->Harga), ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* KIB B  */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$jumlah_b =  sum_arr($kibb,"Jumlah");
	$harga_b  =  sum_arr($kibb,"Harga");
	$pdf->Row(array("2", "02", "", "PERALATAN & MESIN",rp($jumlah_b), rp($harga_b), ""));
	if ($jumlahb > 0){
	foreach ($kibb->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2,rp($row->Jumlah), rp($row->Harga), ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	} 
	
	/* KIB C  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_c =  sum_arr($kibc,"Jumlah");
	$harga_c  =  sum_arr($kibc,"Harga");	
	$pdf->Row(array("3", "03", "", "GEDUNG & BANGUNAN",rp($jumlah_c), rp($harga_c), ""));
	if ($jumlahc > 0){
	foreach ($kibc->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2,rp($row->Jumlah), rp($row->Harga), ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	} 
	
	/* KIB D  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_d =  sum_arr($kibd,"Jumlah");
	$harga_d  =  sum_arr($kibd,"Harga");	
	$pdf->Row(array("4", "04", "", "JALAN, IRIGASI & JARINGAN",rp($jumlah_d), rp($harga_d), ""));
	if ($jumlahd > 0){
	foreach ($kibd->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2,rp($row->Jumlah), rp($row->Harga), ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	} 
	
	/* KIB E  */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$jumlah_e =  sum_arr($kibe,"Jumlah");
	$harga_e  =  sum_arr($kibe,"Harga");
	$pdf->Row(array("5", "05", "", "ASET TETAP LAINNYA",rp($jumlah_e), rp($harga_e), ""));
	if ($jumlahe > 0){
	foreach ($kibe->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2,rp($row->Jumlah), rp($row->Harga), ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	} 
	
	/* KIB F  */
	$no=1;
	$pdf->SetFont('Times','B','10');	
	$jumlah_f =  sum_arr($kibf,"Jumlah");
	$harga_f  =  sum_arr($kibf,"Harga");
	$pdf->Row(array("6", "06", "", "KONSTRUKSI DALAM PENGERJAAN",rp($jumlah_f), rp($harga_f), ""));
	if ($jumlahf > 0){
	foreach ($kibf->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2,rp($row->Jumlah), rp($row->Harga), ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* KIB LAINYA  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$jumlah_l =  sum_arr($kibl,"Jumlah");
	$harga_l  =  sum_arr($kibl,"Harga");
	$pdf->Row(array("7", "07", "", "ASET LAINYA",rp($jumlah_l), rp($harga_l), ""));
	if ($jumlahl > 0){
	foreach ($kibl->result() as $row){
		$kd2 		= sprintf ("%02u", $row->Kd_Aset2);
		$pdf->SetFont('Times','','10');	
		$pdf->Row(array("", "", $kd2, $row->Nm_Aset2,rp($row->Jumlah), rp($row->Harga), ""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	} 

	$harga_total		= $harga_a +  $harga_b +  $harga_c +  $harga_d +  $harga_e +  $harga_f +  $harga_l; 
	$jumlah_total		= $jumlah_a +  $jumlah_b +  $jumlah_c +  $jumlah_d +  $jumlah_e +  $jumlah_f + $jumlah_l; 
	
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", " T O T A L ", "", "",rp($jumlah_total), rp($harga_total), ""));

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}

	$pdf->Output("SIMDO_Rekap_Inventaris.pdf","I");
?>