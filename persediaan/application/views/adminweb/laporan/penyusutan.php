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

	$kb1 = sprintf ("%02u", $nama_upb->Kd_Bidang);
	$kb2 = sprintf ("%02u", $nama_upb->Kd_Unit);
	$kb3 = sprintf ("%02u", $nama_upb->Kd_Sub);
	$kb4 = sprintf ("%02u", $nama_upb->Kd_UPB);
	$kode_lokasi = "11.02.0.".$kb1.'.'.$kb2.'.'.$kb3.'.'.$kb4;
	$pdf->UPBTitle($nama_upb->Nm_bidang,$nama_upb->Nm_unit,$nama_upb->Nm_sub_unit,$nama_upb->Nm_UPB,$kode_lokasi);


	$pdf->Ln();
	$tgl=date('Y-m-d');

	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(15,30,20,40,25,30,20,25,25,35,25,35,17,35,10,30,23));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14", "15","16", "17"));

	/* KIB B */
	$no=1;
	$pdf->SetFont('Times','','10');
	$total_hr_kibb=0;
	$total_nb_kibb=0;
	$total_st_kibb=0;
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
		$manfaat        = ($row->Masa_Manfaat == 0) ? 1 : $row->Masa_Manfaat;
		$beban_susut    = (($row->Masa_Manfaat - $umur_barang) == $row->Masa_Manfaat || ($row->Masa_Manfaat - $umur_barang) < 0) ? 0 : ($row->Harga/$manfaat);
		/*$penyusutan     = (($beban_susut*$umur_barang) == 0) ? $row->Harga : ($beban_susut*$umur_barang);*/
		$penyusutan     = (($beban_susut*$umur_barang) == 0 AND ($umur_barang != 0 ) ) ? $row->Harga : ($beban_susut*$umur_barang);

	    if ($row->Harga == 0) {
	        $hasil_bagi = "100";
	    } else {
	        $hasil_bagi = $penyusutan/$row->Harga*100;
	    }
		$persen         = sprintf ("%d", $hasil_bagi);
		$nilai_buku     = $row->Harga - $penyusutan;
		
		$total_hr_kibb    	+= $row->Harga;
		$total_nb_kibb      += $nilai_buku;
		$total_st_kibb    	+= $penyusutan;
		
		$harga          = number_format($row->Harga,0,",",".");
		$rp_beban_susut = number_format($beban_susut,0,",",".");
		$rp_penyusutan  = number_format($penyusutan,0,",",".");
		$rp_nilai_buku  = number_format($nilai_buku,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$merk,$spec,$row->Bahan, $row->Asal_usul,$row->Tahun, $harga, $manfaat,$rp_beban_susut, $umur_barang, $rp_penyusutan,$persen, $rp_nilai_buku, ''));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	$total_harga      = number_format($total_hr_kibb,0,",",".");
	$total_nilai_buku = number_format($total_nb_kibb,0,",",".");
	$total_susut      = number_format($total_st_kibb,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", "", "	", "TOTAL KIB B","", "", "", "","", $total_harga, "","", "", $total_susut,"", $total_nilai_buku, ""));

	/* KIB C */
	$no=1;
	$pdf->SetFont('Times','','10');
	$total_hr_kibc=0;
	$total_nb_kibc=0;
	$total_st_kibc=0;
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
		$beban_susut    = (($row->Masa_Manfaat - $umur_barang) == $row->Masa_Manfaat || ($row->Masa_Manfaat - $umur_barang) < 0) ? 0 : ($row->Harga/$manfaat);
		/*$penyusutan     = (($beban_susut*$umur_barang) == 0) ? $row->Harga : ($beban_susut*$umur_barang);*/
		$penyusutan     = (($beban_susut*$umur_barang) == 0 AND ($umur_barang != 0 ) ) ? $row->Harga : ($beban_susut*$umur_barang);

	    if ($row->Harga == 0) {
	        $hasil_bagi = "100";
	    } else {
	        $hasil_bagi = $penyusutan/$row->Harga*100;
	    }
		$persen         = sprintf ("%d", $hasil_bagi);
		$nilai_buku     = $row->Harga - $penyusutan;
		
		$total_hr_kibc += $row->Harga;
		$total_nb_kibc += $nilai_buku;
		$total_st_kibc += $penyusutan;
		
		$harga          = number_format($row->Harga,0,",",".");
		$rp_beban_susut = number_format($beban_susut,0,",",".");
		$rp_penyusutan  = number_format($penyusutan,0,",",".");
		$rp_nilai_buku  = number_format($nilai_buku,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$merk,$spec,'', $row->Asal_usul,$row->Tahun, $harga, $manfaat,$rp_beban_susut, $umur_barang, $rp_penyusutan,$persen, $rp_nilai_buku, ''));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	$total_harga      = number_format($total_hr_kibc,0,",",".");
	$total_nilai_buku = number_format($total_nb_kibc,0,",",".");
	$total_susut      = number_format($total_st_kibc,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", "", "	", "TOTAL KIB C","", "", "", "","", $total_harga, "","", "", $total_susut,"", $total_nilai_buku, ""));


	/* KIB D */
	$no=1;
	$pdf->SetFont('Times','','10');
	$total_hr_kibd=0;
	$total_nb_kibd=0;
	$total_st_kibd=0;
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
		$beban_susut    = (($row->Masa_Manfaat - $umur_barang) == $row->Masa_Manfaat || ($row->Masa_Manfaat - $umur_barang) < 0) ? 0 : ($row->Harga/$manfaat);
		/*$penyusutan     = (($beban_susut*$umur_barang) == 0) ? $row->Harga : ($beban_susut*$umur_barang);*/
		$penyusutan     = (($beban_susut*$umur_barang) == 0 AND ($umur_barang != 0 ) ) ? $row->Harga : ($beban_susut*$umur_barang);

	    if ($row->Harga == 0) {
	        $hasil_bagi = "100";
	    } else {
	        $hasil_bagi = $penyusutan/$row->Harga*100;
	    }
		$persen         = sprintf ("%d", $hasil_bagi);
		$nilai_buku     = $row->Harga - $penyusutan;
		
		$total_hr_kibd += $row->Harga;
		$total_nb_kibd += $nilai_buku;
		$total_st_kibd += $penyusutan;
		
		$harga          = number_format($row->Harga,0,",",".");
		$rp_beban_susut = number_format($beban_susut,0,",",".");
		$rp_penyusutan  = number_format($penyusutan,0,",",".");
		$rp_nilai_buku  = number_format($nilai_buku,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$merk,$spec,"", $row->Asal_usul,$row->Tahun, $harga, $manfaat,$rp_beban_susut, $umur_barang, $rp_penyusutan,$persen, $rp_nilai_buku, ''));
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	$total_harga      = number_format($total_hr_kibd,0,",",".");
	$total_nilai_buku = number_format($total_nb_kibd,0,",",".");
	$total_susut      = number_format($total_st_kibd,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array("", "", "	", "TOTAL KIB D","", "", "", "","", $total_harga, "","", "", $total_susut,"", $total_nilai_buku, ""));


	$total_hr_all = $total_hr_kibb + $total_hr_kibc + $total_hr_kibd;
	$total_all_harga = number_format($total_hr_all,0,",",".");
	$total_st_all = $total_st_kibb + $total_st_kibc + $total_st_kibd;
	$total_all_susut = number_format($total_st_all,0,",",".");
	$total_nb_all = $total_nb_kibb + $total_nb_kibc + $total_nb_kibd;
	$total_all_nilai_buku = number_format($total_nb_all,0,",",".");
	$pdf->SetFont('Times','B','11');
	$pdf->Row(array("", "", "	", "T O T A L","", "", "", "","", $total_all_harga, "","", "", $total_all_susut,"", $total_all_nilai_buku, ""));


	$pdf->ttd($ta_upb->Nm_Pimpinan,$ta_upb->Nip_Pimpinan,$ta_upb->Jbt_Pimpinan,$ta_upb->Nm_Pengurus,$ta_upb->Nip_Pengurus,$tanggal);

	$pdf->Output("SIMDO_Penyusutan.pdf","I");
?>