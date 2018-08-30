<?php

	$this->load->library('PDF_MC_Table_pengadaan_bj');
	
	$pdf=new PDF_MC_Table_pengadaan_bj('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"DAFTAR PENGADAAN BARANG & JASA \n ".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');
	
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
	$pdf->SetWidths(array(10,60,25,55,40,40,25,40,40,40,35,30));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12"));

	$no=1;
	$pdf->SetFont('Times','','10');	
	$total =0;
	if ($jumlah_pengadaan > 0){
		foreach ($pengadaan->result() as $row){
			$tgl_kontrak 		= date('d/m/Y', strtotime($row->Tgl_Kontrak));
			$tgl_kuitansi 		= date('d/m/Y', strtotime($row->Tgl_Kuitansi));
			$jlh				= abs($row->Jumlah);
			$harga_satuan		= number_format($row->Harga,0,",",".");
			$jumlah				= number_format($row->Harga*$row->Jumlah,0,",",".");
			$unit = "";
			$tag	= explode(',',$row->Dipergunakan);
			foreach($tag as $element){
				$unit .= $element." \n";
				}
			$pdf->Row(array($no,  $row->Uraian_Kegiatan, $tgl_kontrak, strtoupper($row->No_Kontrak), $tgl_kuitansi,  $row->No_Kuitansi, rp($jlh).' Paket', $harga_satuan,$jumlah, "-", $unit, $row->Keterangan));
			$total += ($row->Harga*$row->Jumlah);
			$no++;
		}
	}else{
		$pdf->nihil();
	}

	$harga_total		= number_format($total,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('', "Jumlah Harga", '','',"", "", "", "", $harga_total, "", "",""));

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}
	
	$pdf->Output("SIMDO_Pengadaan_BJ.pdf","I");
?>