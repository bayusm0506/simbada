<?php

	$this->load->library('PDF_MC_Table_pengadaan');
	
	$pdf=new PDF_MC_Table_pengadaan('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"DAFTAR PENGADAAN",0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(435,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(435,5,$periode,0,'C');
	$kode_lokasi = KODE_LOKASI;
	$pdf->UPBTitle2('','','','',$kode_lokasi);

	$pdf->Ln();	
	$tgl=date('Y-m-d');
	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(10,60,40,40,40,40,25,40,40,40,35,30));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12"));

	$no=1;
	$pdf->SetFont('Times','','10');	
	$total =0;
	if ($jumlah > 0){
		foreach ($pengadaan->result() as $row){
			$tgl_kontrak 		= date('d/m/Y', strtotime($row->Tgl_Kontrak));
			$tgl_kuitansi 		= date('d/m/Y', strtotime($row->Tgl_Kuitansi));
			$harga_satuan		= number_format($row->Harga,0,",",".");
			$jumlah				= number_format($row->Harga*$row->Jumlah,0,",",".");
			$unit = "";
			$tag	= explode(',',$row->Dipergunakan);
			foreach($tag as $element){
				$unit .= $element." \n";
				}
			$pdf->Row(array($no,  $row->Nm_Aset5, $tgl_kontrak, $row->No_Kontrak, $tgl_kuitansi,  $row->No_Kuitansi, $row->Jumlah, $harga_satuan,$jumlah, $row->Nm_UPB, $unit, $row->Keterangan));
			$total += ($row->Harga*$row->Jumlah);
			$no++;
		}
	}else{
		$pdf->nihil();
	}

	$harga_total		= number_format($total,0,",",".");
	$pdf->SetFont('Times','','10');
	$pdf->Row(array('', "Jumlah Harga", '','',"", "", "", "", $harga_total, "", "",""));

	$pdf->ttd2($ta_upb->Nm_Pimpinan,$ta_upb->Nip_Pimpinan,$ta_upb->Jbt_Pimpinan,$ta_upb->Nm_Pengurus,$ta_upb->Nip_Pengurus,$tanggal);
	
	$pdf->Output("SIMDO_kibc.pdf","I");
?>