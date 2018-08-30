<?php

	$this->load->library('cetak/PDF_MC_BAST');
	
	$pdf=new PDF_MC_BAST('P','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(280,5,"BERITA ACARA SERAH TERIMA",0,'C');
	$pdf->SetFont('Arial','',12);
	$No_Dokumen = sprintf ("%03u", $No_Dokumen);
	$pdf->MultiCell(280,5,"NOMOR : 032/".$No_Dokumen."/BAST/".strtoupper($this->session->userdata('username'))."/".thn($Log_entry),0,'C');
	$pdf->Ln(20);

	$pdf->MultiCell(280,5,"Pada hari ini ".nama_hari($Tgl_Dokumen)." tanggal ".terbilang(tgl($Tgl_Dokumen))." bulan ".bulan(bln($Tgl_Dokumen))." tahun ".terbilang(thn($Tgl_Dokumen))." yang bertanda dibawah ini masing-masing :",0,'L');
	$pdf->Ln(10);
	$pdf->Identitas1($Nama_Pihak_1,$Nip_Pihak_1,$Jabatan_Pihak_1,$Alamat_Pihak_1);
	$pdf->Ln(10);
	$pdf->Identitas2($Nama_Pihak_2,$Nip_Pihak_2,$Jabatan_Pihak_2,$Alamat_Pihak_2);
	$pdf->Ln(10);
	$pdf->MultiCell(280,5,"menyatakan dengan sesungguhnya bahwa PIHAK PERTAMA telah menyerahkan kepada PIHAK KEDUA dan PIHAK KEDUA telah menerima dari PIHAK PERTAMA barang berupa ",0,'L');
	$pdf->Ln(5);

	$pdf->SetFont('Arial','','10');
	$pdf->SetWidths(array(15,30,20,45,25,25,20,25,17,33,25));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11"));

	/* KIB B */
	$no=1;
	$pdf->SetFont('Times','','10');
	if ($jumlah_ba > 0){
	foreach ($ba->result() as $row){
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
		
		if ($row->Kondisi == 1){
			$kondisi = 'B';
		}elseif ($row->Kondisi == 2){
			$kondisi = 'KB';
		}else{
			$kondisi = 'RB';
		}
		
		$harga		= number_format($row->Harga,0,",",".");
		$pdf->Row(array($no, $kodebarang, $register, $row->Nm_Aset5,$merk, "-", $kondisi, $row->Tahun,$row->jumlah_register, $harga, $row->Keterangan));		
		$no++;
	}
	}else{
		$pdf->nihil2();
	}

	$harga_total		= number_format($total_harga,0,",",".");
	$pdf->SetFont('Times','B','10');
	$pdf->Row(array('', '', '', '','', "", '', '',$total_jumlah, $harga_total,''));

	$pdf->Ln(10);
	$pdf->Ketentuan();

	$nm_1	= $Nama_Pihak_1;
	$nip_1	= $Nip_Pihak_1;
	$nm_2	= $Nama_Pihak_2;
	$nip_2	= $Nip_Pihak_2;

	$pdf->ttd($nm_1,$nip_1,$nm_2,$nip_2,$Tgl_Dokumen);

	$pdf->Output("SIMDO_Berita_Acara_BAST.pdf","I");
?>