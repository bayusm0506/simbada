<?php

	$this->load->library('inventarisasi/PDF_MC_Table_rekapmutasi');
	
	$pdf=new PDF_MC_Table_rekapmutasi('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"REKAPITULASI MUTASI BARANG \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');
	$pdf->Ln(10);	

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
	$pdf->SetWidths(array(15,115,25,45,25,45,25,45,25,45,30));
	$pdf->Rowheader($this->session->userdata('awal'),$this->session->userdata('akhir'));
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11"));
	
	/* KIB A */
	$no=1;
	$jumlah_awal_A   = sum_arr($kiba,"Jumlah_awal");
	$total_awal_A    = sum_arr($kiba,"Harga_awal");
	$jumlah_kurang_A = sum_arr($kiba,"Jumlah_Kurang");
	$total_kurang_A  = sum_arr($kiba,"Mutasi_Kurang");
	$jumlah_tambah_A = sum_arr($kiba,"Jumlah_Tambah");
	$total_tambah_A  = sum_arr($kiba,"Mutasi_Tambah");
	$total_jumlah_A  = ($jumlah_awal_A + $jumlah_tambah_A) - $jumlah_kurang_A;	
	$total_harga_A   = ($total_awal_A + $total_tambah_A) - $total_kurang_A;	
	$pdf->SetFont('Times','B','10');	
	$pdf->Row(array("#01", "TANAH", rp($jumlah_awal_A),rp($total_awal_A),rp($jumlah_kurang_A),rp($total_kurang_A),rp($jumlah_tambah_A),rp($total_tambah_A),rp($total_jumlah_A), rp($total_harga_A),""));
	$pdf->SetFont('Times','','10');	
	if ($jumlaha > 0){
	foreach ($kiba->result() as $row){
		$kd2          = sprintf ("%02u", $row->Kd_Aset2);
		$jumlah_akhir = ($row->Jumlah_awal + $row->Jumlah_Tambah) - $row->Jumlah_Kurang;
		$total_akhir  = ($row->Harga_awal + $row->Mutasi_Tambah) - $row->Mutasi_Kurang;
		$pdf->Row(array($kd2, $row->Nm_Aset2, nilai($row->Jumlah_awal), rp($row->Harga_awal),nilai($row->Jumlah_Kurang), rp($row->Mutasi_Kurang),nilai($row->Jumlah_Tambah),rp($row->Mutasi_Tambah),nilai($jumlah_akhir),rp($total_akhir),""));
		$no++;
	}
	}else{
		$pdf->nihil();
	}
	
	/* KIB B  */
	$no=1;
	$jumlah_awal_B   = sum_arr($kibb,"Jumlah_awal");
	$total_awal_B    = sum_arr($kibb,"Harga_awal");
	$jumlah_kurang_B = sum_arr($kibb,"Jumlah_Kurang");
	$total_kurang_B  = sum_arr($kibb,"Mutasi_Kurang");
	$jumlah_tambah_B = sum_arr($kibb,"Jumlah_Tambah");
	$total_tambah_B  = sum_arr($kibb,"Mutasi_Tambah");
	$total_jumlah_B  = ($jumlah_awal_B + $jumlah_tambah_B) - $jumlah_kurang_B;	
	$total_harga_B   = ($total_awal_B + $total_tambah_B) - $total_kurang_B;	
	$pdf->SetFont('Times','B','10');	
	$pdf->Row(array("#02", "PERALATAN DAN MESIN", rp($jumlah_awal_B),rp($total_awal_B),rp($jumlah_kurang_B),rp($total_kurang_B),rp($jumlah_tambah_B),rp($total_tambah_B),rp($total_jumlah_B), rp($total_harga_B),""));
	$pdf->SetFont('Times','','10');	
	if ($jumlahb > 0){
	foreach ($kibb->result() as $row){
		$kd2          = sprintf ("%02u", $row->Kd_Aset2);
		$jumlah_akhir = ($row->Jumlah_awal + $row->Jumlah_Tambah) - $row->Jumlah_Kurang;
		$total_akhir  = ($row->Harga_awal + $row->Mutasi_Tambah) - $row->Mutasi_Kurang;
		$pdf->Row(array($kd2, $row->Nm_Aset2, nilai($row->Jumlah_awal), rp($row->Harga_awal),nilai($row->Jumlah_Kurang), rp($row->Mutasi_Kurang),nilai($row->Jumlah_Tambah),rp($row->Mutasi_Tambah),nilai($jumlah_akhir),rp($total_akhir),""));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	/* KIB C */
	$no=1;
	$jumlah_awal_C   = sum_arr($kibc,"Jumlah_awal");
	$total_awal_C    = sum_arr($kibc,"Harga_awal");
	$jumlah_kurang_C = sum_arr($kibc,"Jumlah_Kurang");
	$total_kurang_C  = sum_arr($kibc,"Mutasi_Kurang");
	$jumlah_tambah_C = sum_arr($kibc,"Jumlah_Tambah");
	$total_tambah_C  = sum_arr($kibc,"Mutasi_Tambah");
	$total_jumlah_C  = ($jumlah_awal_C + $jumlah_tambah_C) - $jumlah_kurang_C;	
	$total_harga_C   = ($total_awal_C + $total_tambah_C) - $total_kurang_C;	
	$pdf->SetFont('Times','B','10');	
	$pdf->Row(array("#03", "GEDUNG DAN BANGUNAN", rp($jumlah_awal_C),rp($total_awal_C),rp($jumlah_kurang_C),rp($total_kurang_C),rp($jumlah_tambah_C),rp($total_tambah_C),rp($total_jumlah_C), rp($total_harga_C),""));
	$pdf->SetFont('Times','','10');	
	if ($jumlahc > 0){
	foreach ($kibc->result() as $row){
		$kd2          = sprintf ("%02u", $row->Kd_Aset2);
		$jumlah_akhir = ($row->Jumlah_awal + $row->Jumlah_Tambah) - $row->Jumlah_Kurang;
		$total_akhir  = ($row->Harga_awal + $row->Mutasi_Tambah) - $row->Mutasi_Kurang;
		$pdf->Row(array($kd2, $row->Nm_Aset2, nilai($row->Jumlah_awal), rp($row->Harga_awal),nilai($row->Jumlah_Kurang), rp($row->Mutasi_Kurang),nilai($row->Jumlah_Tambah),rp($row->Mutasi_Tambah),nilai($jumlah_akhir),rp($total_akhir),""));
		$no++;
	}
	}else{
		$pdf->nihil();
	}

	/* KIB D */
	$no=1;
	$jumlah_awal_D   = sum_arr($kibd,"Jumlah_awal");
	$total_awal_D    = sum_arr($kibd,"Harga_awal");
	$jumlah_kurang_D = sum_arr($kibd,"Jumlah_Kurang");
	$total_kurang_D  = sum_arr($kibd,"Mutasi_Kurang");
	$jumlah_tambah_D = sum_arr($kibd,"Jumlah_Tambah");
	$total_tambah_D  = sum_arr($kibd,"Mutasi_Tambah");
	$total_jumlah_D  = ($jumlah_awal_D + $jumlah_tambah_D) - $jumlah_kurang_D;	
	$total_harga_D   = ($total_awal_D + $total_tambah_D) - $total_kurang_D;	
	$pdf->SetFont('Times','B','10');	
	$pdf->Row(array("#04", "JALAN, IRIGASI DAN JARINGAN", rp($jumlah_awal_D),rp($total_awal_D),rp($jumlah_kurang_D),rp($total_kurang_D),rp($jumlah_tambah_D),rp($total_tambah_D),rp($total_jumlah_D), rp($total_harga_D),""));
	$pdf->SetFont('Times','','10');	
	if ($jumlahd > 0){
	foreach ($kibd->result() as $row){
		$kd2          = sprintf ("%02u", $row->Kd_Aset2);
		$jumlah_akhir = ($row->Jumlah_awal + $row->Jumlah_Tambah) - $row->Jumlah_Kurang;
		$total_akhir  = ($row->Harga_awal + $row->Mutasi_Tambah) - $row->Mutasi_Kurang;
		$pdf->Row(array($kd2, $row->Nm_Aset2, nilai($row->Jumlah_awal), rp($row->Harga_awal),nilai($row->Jumlah_Kurang), rp($row->Mutasi_Kurang),nilai($row->Jumlah_Tambah),rp($row->Mutasi_Tambah),nilai($jumlah_akhir),rp($total_akhir),""));
		$no++;
	}
	}else{
		$pdf->nihil();
	}

	/* KIB E */
	$no=1;
	$jumlah_awal_E   = sum_arr($kibe,"Jumlah_awal");
	$total_awal_E    = sum_arr($kibe,"Harga_awal");
	$jumlah_kurang_E = sum_arr($kibe,"Jumlah_Kurang");
	$total_kurang_E  = sum_arr($kibe,"Mutasi_Kurang");
	$jumlah_tambah_E = sum_arr($kibe,"Jumlah_Tambah");
	$total_tambah_E  = sum_arr($kibe,"Mutasi_Tambah");
	$total_jumlah_E  = ($jumlah_awal_E + $jumlah_tambah_E) - $jumlah_kurang_E;	
	$total_harga_E   = ($total_awal_E + $total_tambah_E) - $total_kurang_E;	
	$pdf->SetFont('Times','B','10');	
	$pdf->Row(array("#05", "ASET TETAP LAINYA", rp($jumlah_awal_E),rp($total_awal_E),rp($jumlah_kurang_E),rp($total_kurang_E),rp($jumlah_tambah_E),rp($total_tambah_E),rp($total_jumlah_E), rp($total_harga_E),""));
	$pdf->SetFont('Times','','10');	
	if ($jumlahe > 0){
	foreach ($kibe->result() as $row){
		$kd2          = sprintf ("%02u", $row->Kd_Aset2);
		$jumlah_akhir = ($row->Jumlah_awal + $row->Jumlah_Tambah) - $row->Jumlah_Kurang;
		$total_akhir  = ($row->Harga_awal + $row->Mutasi_Tambah) - $row->Mutasi_Kurang;
		$pdf->Row(array($kd2, $row->Nm_Aset2, nilai($row->Jumlah_awal), rp($row->Harga_awal),nilai($row->Jumlah_Kurang), rp($row->Mutasi_Kurang),nilai($row->Jumlah_Tambah),rp($row->Mutasi_Tambah),nilai($jumlah_akhir),rp($total_akhir),""));
		$no++;
	}
	}else{
		$pdf->nihil();
	}

	/* KIB F */
	$no=1;
	$jumlah_awal_F   = sum_arr($kibf,"Jumlah_awal");
	$total_awal_F    = sum_arr($kibf,"Harga_awal");
	$jumlah_kurang_F = sum_arr($kibf,"Jumlah_Kurang");
	$total_kurang_F  = sum_arr($kibf,"Mutasi_Kurang");
	$jumlah_tambah_F = sum_arr($kibf,"Jumlah_Tambah");
	$total_tambah_F  = sum_arr($kibf,"Mutasi_Tambah");
	$total_jumlah_F  = ($jumlah_awal_F + $jumlah_tambah_F) - $jumlah_kurang_F;	
	$total_harga_F   = ($total_awal_F + $total_tambah_F) - $total_kurang_F;	
	$pdf->SetFont('Times','B','10');	
	$pdf->Row(array("#06", "KONSTRUKSI DALAM PENGERJAAN", rp($jumlah_awal_F),rp($total_awal_F),rp($jumlah_kurang_F),rp($total_kurang_F),rp($jumlah_tambah_F),rp($total_tambah_F),rp($total_jumlah_F), rp($total_harga_F),""));
	$pdf->SetFont('Times','','10');	
	if ($jumlahf > 0){
	foreach ($kibf->result() as $row){
		$kd2          = sprintf ("%02u", $row->Kd_Aset2);
		$jumlah_akhir = ($row->Jumlah_awal + $row->Jumlah_Tambah) - $row->Jumlah_Kurang;
		$total_akhir  = ($row->Harga_awal + $row->Mutasi_Tambah) - $row->Mutasi_Kurang;
		$pdf->Row(array($kd2, $row->Nm_Aset2, nilai($row->Jumlah_awal), rp($row->Harga_awal),nilai($row->Jumlah_Kurang), rp($row->Mutasi_Kurang),nilai($row->Jumlah_Tambah),rp($row->Mutasi_Tambah),nilai($jumlah_akhir),rp($total_akhir),""));
		$no++;
	}
	}else{
		$pdf->nihil();
	}

	/* LAINYA */
	$no=1;
	$jumlah_awal_L   = sum_arr($kibl,"Jumlah_awal");
	$total_awal_L    = sum_arr($kibl,"Harga_awal");
	$jumlah_kurang_L = sum_arr($kibl,"Jumlah_Kurang");
	$total_kurang_L  = sum_arr($kibl,"Mutasi_Kurang");
	$jumlah_tambah_L = sum_arr($kibl,"Jumlah_Tambah");
	$total_tambah_L  = sum_arr($kibl,"Mutasi_Tambah");
	$total_jumlah_L  = ($jumlah_awal_L + $jumlah_tambah_L) - $jumlah_kurang_L;	
	$total_harga_L   = ($total_awal_L + $total_tambah_L) - $total_kurang_L;	
	$pdf->SetFont('Times','B','10');	
	$pdf->Row(array("#07", "ASET LAINNYA", rp($jumlah_awal_L),rp($total_awal_L),rp($jumlah_kurang_L),rp($total_kurang_L),rp($jumlah_tambah_L),rp($total_tambah_L),rp($total_jumlah_L), rp($total_harga_L),""));
	$pdf->SetFont('Times','','10');
	if ($jumlahl > 0){
	foreach ($kibl->result() as $row){
		$kd2          = sprintf ("%02u", $row->Kd_Aset2);
		$jumlah_akhir = ($row->Jumlah_awal + $row->Jumlah_Tambah) - $row->Jumlah_Kurang;
		$total_akhir  = ($row->Harga_awal + $row->Mutasi_Tambah) - $row->Mutasi_Kurang;
		$pdf->Row(array($kd2, $row->Nm_Aset2, nilai($row->Jumlah_awal), rp($row->Harga_awal),nilai($row->Jumlah_Kurang), rp($row->Mutasi_Kurang),nilai($row->Jumlah_Tambah),rp($row->Mutasi_Tambah),nilai($jumlah_akhir),rp($total_akhir),""));
		$no++;
	}
	}else{
		$pdf->nihil();
	}

	$jumlah_awal   = $jumlah_awal_A + $jumlah_awal_B + $jumlah_awal_C + $jumlah_awal_D + $jumlah_awal_E + $jumlah_awal_F + $jumlah_awal_L;
	$total_awal    = $total_awal_A + $total_awal_B + $total_awal_C + $total_awal_D + $total_awal_E  + $total_awal_F + $total_awal_L;
	$jumlah_kurang = $jumlah_kurang_A + $jumlah_kurang_B + $jumlah_kurang_C + $jumlah_kurang_D + $jumlah_kurang_E  + $jumlah_kurang_F + $jumlah_kurang_L;
	$total_kurang  = $total_kurang_A + $total_kurang_B + $total_kurang_C + $total_kurang_D + $total_kurang_E + $total_kurang_F + $total_kurang_L;
	$jumlah_tambah = $jumlah_tambah_A + $jumlah_tambah_B + $jumlah_tambah_C + $jumlah_tambah_D + $jumlah_tambah_E + $jumlah_tambah_F + $jumlah_tambah_L;
	$total_tambah  = $total_tambah_A + $total_tambah_B + $total_tambah_C + $total_tambah_D + $total_tambah_E + $total_tambah_F + $total_tambah_L;
	$total_jumlah  = ($jumlah_awal + $jumlah_tambah) - $jumlah_kurang;	
	$total_harga   = ($total_awal + $total_tambah) - $total_kurang;	
	
	$pdf->SetFont('Arial','B','10');
	$pdf->Row(array('#', "J U M L A H", rp($jumlah_awal),rp($total_awal),rp($jumlah_kurang),rp($total_kurang),rp($jumlah_tambah),rp($total_tambah),rp($total_jumlah),rp($total_harga), ""));

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}
	$pdf->Output("SIMDO_Rekap_Mutasi.pdf","I");
?>