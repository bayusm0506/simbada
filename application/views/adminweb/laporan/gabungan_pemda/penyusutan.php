<?php
	$this->load->library('PDF_MC_Table_penyusutan');

	$pdf=new PDF_MC_Table_penyusutan('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"DAFTAR PENYUSUTAN BARANG MILIK DAERAH\nPEMERINTAH PROVINSI SUMATERA UTARA",0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');

	$pdf->Ln(20);
	$kode_lokasi = KODE_LOKASI;
	$pdf->UPBTitle2($nama_upb->Nm_bidang,$nama_upb->Nm_unit,$nama_upb->Nm_sub_unit,$nama_upb->Nm_UPB,$kode_lokasi);

	$pdf->Ln();
	$tgl=date('Y-m-d');

	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(15,30,20,40,25,30,20,25,25,35,25,35,17,35,10,30,23));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14", "15","16", "17"));

	/* KIB B */
	$no=1;
	$pdf->SetFont('Times','','10');
	$total_hr=0;
	$total_nb=0;
	$total_st=0;
	//$total_penyusutan		= number_format(123456789,0,",",".");
	//$pdf->Row(array("#", "", "", " ", "JUMLAH RODA ","", "", "", "","", "", "", "","", 12345, $total_penyusutan,"", "",""));
	if ($jumlah_penyusutan > 0){
	foreach ($penyusutan->result() as $row){
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

		if (empty($row->Nomor_Pabrik) AND empty($row->Nomor_Mesin)){
			$spec = '-';
		}else{
			$spec = $row->Nomor_Pabrik.' / '.$row->Nomor_Mesin;
		}
		
		$umur_barang    = (($this->session->userdata('tahun_anggaran')-$row->Tahun) < 0) ? 0 : ($this->session->userdata('tahun_anggaran')-$row->Tahun);			
		$manfaat        = ($row->Masa_Manfaat == 0) ? 0 : $row->Masa_Manfaat;
		$beban_susut    = (($row->Masa_Manfaat - $umur_barang) == $row->Masa_Manfaat) ? 0 : ($row->Harga/$manfaat);
		/*$penyusutan     = (($beban_susut*$umur_barang) == 0) ? $row->Harga : ($beban_susut*$umur_barang);*/
		$penyusutan     = (($beban_susut*$umur_barang) == 0 AND ($umur_barang != 0 ) ) ? $row->Harga : ($beban_susut*$umur_barang);

	    if ($row->Harga == 0) {
	        $hasil_bagi = "100";
	    } else {
	        $hasil_bagi = $penyusutan/$row->Harga*100;
	    }
		$persen         = sprintf ("%d", $hasil_bagi);
		$nilai_buku     = $row->Harga - $penyusutan;
		
		$total_hr    	+= $row->Harga;
		$total_nb       += $nilai_buku;
		$total_st    	+= $penyusutan;
		
		$harga          = number_format($row->Harga,0,",",".");
		$rp_beban_susut = number_format($beban_susut,0,",",".");
		$rp_penyusutan  = number_format($penyusutan,0,",",".");
		$rp_nilai_buku  = number_format($nilai_buku,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$merk,$spec,$row->Bahan, $row->Asal_usul,$row->Tahun, $harga, $manfaat,$rp_beban_susut, $umur_barang, $rp_penyusutan,$persen, $rp_nilai_buku, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	$total_harga      = number_format($total_hr,0,",",".");
	$total_nilai_buku = number_format($total_nb,0,",",".");
	$total_susut      = number_format($total_st,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", "", "	", "TOTAL KIB B","", "", "", "","", $total_harga, "","", "", $total_susut,"", $total_nilai_buku, ""));

	/* KIB C */
	$no=1;
	$pdf->SetFont('Times','','10');
	$total_hr=0;
	$total_nb=0;
	$total_st=0;
	//$total_penyusutan		= number_format(123456789,0,",",".");
	//$pdf->Row(array("#", "", "", " ", "JUMLAH RODA ","", "", "", "","", "", "", "","", 12345, $total_penyusutan,"", "",""));
	if ($jumlah_penyusutan_kibc > 0){
	foreach ($penyusutan_kibc->result() as $row){
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

		$umur_barang    = (($this->session->userdata('tahun_anggaran')-$row->Tahun) < 0) ? 0 : ($this->session->userdata('tahun_anggaran')-$row->Tahun);			
		$manfaat        = ($row->Masa_Manfaat == 0) ? 0 : $row->Masa_Manfaat;
		$beban_susut    = (($row->Masa_Manfaat - $umur_barang) == $row->Masa_Manfaat) ? 0 : ($row->Harga/$manfaat);
		/*$penyusutan     = (($beban_susut*$umur_barang) == 0) ? $row->Harga : ($beban_susut*$umur_barang);*/
		$penyusutan     = (($beban_susut*$umur_barang) == 0 AND ($umur_barang != 0 ) ) ? $row->Harga : ($beban_susut*$umur_barang);

	    if ($row->Harga == 0) {
	        $hasil_bagi = "100";
	    } else {
	        $hasil_bagi = $penyusutan/$row->Harga*100;
	    }
		$persen         = sprintf ("%d", $hasil_bagi);
		$nilai_buku     = $row->Harga - $penyusutan;
		
		$total_hr    	+= $row->Harga;
		$total_nb       += $nilai_buku;
		$total_st    	+= $penyusutan;
		
		$harga          = number_format($row->Harga,0,",",".");
		$rp_beban_susut = number_format($beban_susut,0,",",".");
		$rp_penyusutan  = number_format($penyusutan,0,",",".");
		$rp_nilai_buku  = number_format($nilai_buku,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$merk,$spec,'', $row->Asal_usul,$row->Tahun, $harga, $manfaat,$rp_beban_susut, $umur_barang, $rp_penyusutan,$persen, $rp_nilai_buku, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	$total_harga      = number_format($total_hr,0,",",".");
	$total_nilai_buku = number_format($total_nb,0,",",".");
	$total_susut      = number_format($total_st,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", "", "	", "TOTAL KIB C","", "", "", "","", $total_harga, "","", "", $total_susut,"", $total_nilai_buku, ""));


	/* KIB D */
	$no=1;
	$pdf->SetFont('Times','','10');
	$total_hr=0;
	$total_nb=0;
	$total_st=0;
	//$total_penyusutan		= number_format(123456789,0,",",".");
	//$pdf->Row(array("#", "", "", " ", "JUMLAH RODA ","", "", "", "","", "", "", "","", 12345, $total_penyusutan,"", "",""));
	if ($jumlah_penyusutan_kibd > 0){
	foreach ($penyusutan_kibd->result() as $row){
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

		$umur_barang    = (($this->session->userdata('tahun_anggaran')-$row->Tahun) < 0) ? 0 : ($this->session->userdata('tahun_anggaran')-$row->Tahun);			
		$manfaat        = ($row->Masa_Manfaat == 0) ? 0 : $row->Masa_Manfaat;
		$beban_susut    = (($row->Masa_Manfaat - $umur_barang) == $row->Masa_Manfaat) ? 0 : ($row->Harga/$manfaat);
		/*$penyusutan     = (($beban_susut*$umur_barang) == 0) ? $row->Harga : ($beban_susut*$umur_barang);*/
		$penyusutan     = (($beban_susut*$umur_barang) == 0 AND ($umur_barang != 0 ) ) ? $row->Harga : ($beban_susut*$umur_barang);

	    if ($row->Harga == 0) {
	        $hasil_bagi = "100";
	    } else {
	        $hasil_bagi = $penyusutan/$row->Harga*100;
	    }
		$persen         = sprintf ("%d", $hasil_bagi);
		$nilai_buku     = $row->Harga - $penyusutan;
		
		$total_hr    	+= $row->Harga;
		$total_nb       += $nilai_buku;
		$total_st    	+= $penyusutan;
		
		$harga          = number_format($row->Harga,0,",",".");
		$rp_beban_susut = number_format($beban_susut,0,",",".");
		$rp_penyusutan  = number_format($penyusutan,0,",",".");
		$rp_nilai_buku  = number_format($nilai_buku,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$merk,$spec,'', $row->Asal_usul,$row->Tahun, $harga, $manfaat,$rp_beban_susut, $umur_barang, $rp_penyusutan,$persen, $rp_nilai_buku, $row->Nm_UPB));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	$total_harga      = number_format($total_hr,0,",",".");
	$total_nilai_buku = number_format($total_nb,0,",",".");
	$total_susut      = number_format($total_st,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", "", "	", "TOTAL KIB D","", "", "", "","", $total_harga, "","", "", $total_susut,"", $total_nilai_buku, ""));
	
	$kepaladaerah        = "NAMA PIMPINAN";
	$nipkepaladaerah     = "NIP PIMPINAN";
	$jabatankepaladaerah = "JABATAN PIMPINAN";
	$namapengurus        = "NAMA PPENGURUS";
	$nippengurus         = "NIP PENGURUS";
	

	$pdf->ttd2($kepaladaerah,$nipkepaladaerah,$jabatankepaladaerah,$namapengurus,$nippengurus,$tanggal);

	$pdf->Output("SIMDO_Penyusutan.pdf","I");
?>