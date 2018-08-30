<?php

	$this->load->library('perencanaan/PDF_MC_Table_usul_rkbu');
	
	$pdf=new PDF_MC_Table_usul_rkbu('L','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(435,5,"DAFTAR USULAN RENCANA KEBUTUHAN BARANG \n ".NM_PEMDA,0,'C');
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
	$pdf->SetWidths(array(15,90,30,45,20,20,20,20,30,45,20,20,20,20,25));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15"));

	$no=1;
	$pdf->SetFont('Times','','10');	
	$total =0;
	if ($jumlah_rkbu > 0){
		foreach ($rkbu->result() as $row){
			$kode_barang       = $row->Kd_Aset1.".".$row->Kd_Aset2.".".$row->Kd_Aset3.".".$row->Kd_Aset4.".".$row->Kd_Aset5;
			$satuan_ekist       = "Unit";
			if(!empty($row->Nm_Aset_ekist)){
				$kode_barang_ekist       = $row->Kd_Aset1.".".$row->Kd_Aset2.".".$row->Kd_Aset3.".".$row->Kd_Aset4.".".$row->Kd_Aset5;
			}else{
				$kode_barang_ekist = "";
			}
			$jml_kebutuhan_rill = $row->Kebutuhan_Max - $row->Jumlah_ekist;
			$rek               = $row->Kd_Rek_1.".".$row->Kd_Rek_2.".".$row->Kd_Rek_3.".".$row->Kd_Rek_4.".".$row->Kd_Rek_5." - ".$row->Nm_Rek_5;
			$pdf->Row(array($no, $row->Ket_Program, $kode_barang, $row->Nm_Aset5,rp($row->Jumlah), $satuan_ekist, $row->Kebutuhan_Max, $satuan_ekist, $kode_barang_ekist, $row->Nm_Aset_ekist, $row->Jumlah_ekist, $satuan_ekist, $jml_kebutuhan_rill, $satuan_ekist, $row->Keterangan));
			$no++;
		}
	}else{
		$pdf->nihil();
	}

	// $pdf->Row(array("1", "2", "3", "4","5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15"));


	// $harga_total		= number_format($total,0,",",".");
	// $pdf->SetFont('Times','B','10');
	// $pdf->Row(array('', "Jumlah Harga", '','',"", "", "", "", $harga_total, "", "",""));

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}
	
	$pdf->Output("SIMDO_kibc.pdf","I");
?>