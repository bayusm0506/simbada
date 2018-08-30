<?php

	$this->load->library('persediaan/PDF_Rekap_Persediaan');
	
	$pdf=new PDF_Rekap_Persediaan('P','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(280,5,$title."\n".NM_PEMDA,0,'C');
	$pdf->SetFont('Arial','I',12);
	$pdf->MultiCell(280,5,"TAHUN ANGGARAN",0,'C');
	$pdf->MultiCell(280,5,$periode,0,'C');
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
	$pdf->SetWidths(array(30,70,15,30,15,30,15,30,15,30));
	if($ttd_pengurus){
		$pdf->Rowheader();
	}else{
		$pdf->Rowheader(); /*untuk sementara sama dulu*/
	}
	$pdf->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8", "9", "10"));
	
	/* START KIB A  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	// $total_nb_awal   = sum_arr($data,'NB_Awal');
	// $total_susut     = sum_arr($data,'Susut');
	// $total_akm_susut = sum_arr($data,'Akm_Susut');
	// $total_nb_akhir  = sum_arr($data,'NB_Akhir');
	// $pdf->Row(array("      02","TANAH",rp($total_nb_awal),rp($total_susut),rp($total_akm_susut),rp($total_nb_akhir)));

	$arr_aset2       = array();
	$arr_aset3       = array();
	$arr_aset4       = array();
	$arr_aset5       = array();

	foreach ($data->result() as $row){
		if (!isset($arr_aset2[$row->Kd_Aset2])) {
				$arr_aset2[$row->Kd_Aset2]['Jumlah_Awal']       = $row->Jumlah_Awal;
				$arr_aset2[$row->Kd_Aset2]['Total_Awal']        = $row->Total_Awal;
				$arr_aset2[$row->Kd_Aset2]['Jumlah_Kurang_Now'] = $row->Jumlah_Kurang_Now;
				$arr_aset2[$row->Kd_Aset2]['Total_Kurang_Now']  = $row->Total_Kurang_Now;
				$arr_aset2[$row->Kd_Aset2]['Jumlah_Tambah_Now'] = $row->Jumlah_Tambah_Now;
				$arr_aset2[$row->Kd_Aset2]['Total_Tambah_Now']  = $row->Total_Tambah_Now;
				$arr_aset2[$row->Kd_Aset2]['Jumlah_Akhir']      = $row->Jumlah_Akhir;
				$arr_aset2[$row->Kd_Aset2]['Total_Akhir']       = $row->Total_Akhir;
		}else{
				$arr_aset2[$row->Kd_Aset2]['Jumlah_Awal']       += $row->Jumlah_Awal;
				$arr_aset2[$row->Kd_Aset2]['Total_Awal']        += $row->Total_Awal;
				$arr_aset2[$row->Kd_Aset2]['Jumlah_Kurang_Now'] += $row->Jumlah_Kurang_Now;
				$arr_aset2[$row->Kd_Aset2]['Total_Kurang_Now']  += $row->Total_Kurang_Now;
				$arr_aset2[$row->Kd_Aset2]['Jumlah_Tambah_Now'] += $row->Jumlah_Tambah_Now;
				$arr_aset2[$row->Kd_Aset2]['Total_Tambah_Now']  += $row->Total_Tambah_Now;
				$arr_aset2[$row->Kd_Aset2]['Jumlah_Akhir']      += $row->Jumlah_Akhir;
				$arr_aset2[$row->Kd_Aset2]['Total_Akhir']       += $row->Total_Akhir;
		}

		if (!isset($arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3])) {
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Jumlah_Awal']       = $row->Jumlah_Awal;
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Total_Awal']        = $row->Total_Awal;
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Jumlah_Kurang_Now'] = $row->Jumlah_Kurang_Now;
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Total_Kurang_Now']  = $row->Total_Kurang_Now;
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Jumlah_Tambah_Now'] = $row->Jumlah_Tambah_Now;
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Total_Tambah_Now']  = $row->Total_Tambah_Now;
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Jumlah_Akhir']      = $row->Jumlah_Akhir;
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Total_Akhir']       = $row->Total_Akhir;
   		}else{
   				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Jumlah_Awal']       += $row->Jumlah_Awal;
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Total_Awal']        += $row->Total_Awal;
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Jumlah_Kurang_Now'] += $row->Jumlah_Kurang_Now;
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Total_Kurang_Now']  += $row->Total_Kurang_Now;
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Jumlah_Tambah_Now'] += $row->Jumlah_Tambah_Now;
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Total_Tambah_Now']  += $row->Total_Tambah_Now;
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Jumlah_Akhir']      += $row->Jumlah_Akhir;
				$arr_aset3[$row->Kd_Aset2][$row->Kd_Aset3]['Total_Akhir']       += $row->Total_Akhir;
   		}

   		if (!isset($arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4])) {
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Jumlah_Awal']       = $row->Jumlah_Awal;
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Total_Awal']        = $row->Total_Awal;
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Jumlah_Kurang_Now'] = $row->Jumlah_Kurang_Now;
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Total_Kurang_Now']  = $row->Total_Kurang_Now;
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Jumlah_Tambah_Now'] = $row->Jumlah_Tambah_Now;
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Total_Tambah_Now']  = $row->Total_Tambah_Now;
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Jumlah_Akhir']      = $row->Jumlah_Akhir;
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Total_Akhir']       = $row->Total_Akhir;
   		}else{
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Jumlah_Awal']       += $row->Jumlah_Awal;
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Total_Awal']        += $row->Total_Awal;
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Jumlah_Kurang_Now'] += $row->Jumlah_Kurang_Now;
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Total_Kurang_Now']  += $row->Total_Kurang_Now;
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Jumlah_Tambah_Now'] += $row->Jumlah_Tambah_Now;
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Total_Tambah_Now']  += $row->Total_Tambah_Now;
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Jumlah_Akhir']      += $row->Jumlah_Akhir;
				$arr_aset4[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]['Total_Akhir']       += $row->Total_Akhir;
   		}

   		if (!isset($arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5])) {
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Jumlah_Awal']       = $row->Jumlah_Awal;
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Total_Awal']        = $row->Total_Awal;
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Jumlah_Kurang_Now'] = $row->Jumlah_Kurang_Now;
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Total_Kurang_Now']  = $row->Total_Kurang_Now;
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Jumlah_Tambah_Now'] = $row->Jumlah_Tambah_Now;
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Total_Tambah_Now']  = $row->Total_Tambah_Now;
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Jumlah_Akhir']      = $row->Jumlah_Akhir;
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Total_Akhir']       = $row->Total_Akhir;
   		}else{
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Jumlah_Awal']       += $row->Jumlah_Awal;
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Total_Awal']        += $row->Total_Awal;
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Jumlah_Kurang_Now'] += $row->Jumlah_Kurang_Now;
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Total_Kurang_Now']  += $row->Total_Kurang_Now;
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Jumlah_Tambah_Now'] += $row->Jumlah_Tambah_Now;
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Total_Tambah_Now']  += $row->Total_Tambah_Now;
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Jumlah_Akhir']      += $row->Jumlah_Akhir;
				$arr_aset5[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4][$row->Kd_Aset5]['Total_Akhir']       += $row->Total_Akhir;
   		}

	}

	
	$kd_aset2_list = array();
	foreach ($data->result() as $row){

		if(!in_array($row->Kd_Aset2, $kd_aset2_list)) {
			array_push($kd_aset2_list, $row->Kd_Aset2);

			$pdf->RowAset2(array(d2($row->Kd_Aset1).".".d2($row->Kd_Aset2), strtoupper($row->Nm_SSH2), rp($arr_aset2[$row->Kd_Aset2]['Jumlah_Awal']),rp($arr_aset2[$row->Kd_Aset2]['Total_Awal']),rp($arr_aset2[$row->Kd_Aset2]['Jumlah_Kurang_Now']),rp($arr_aset2[$row->Kd_Aset2]['Total_Kurang_Now']),rp($arr_aset2[$row->Kd_Aset2]['Jumlah_Tambah_Now']),rp($arr_aset2[$row->Kd_Aset2]['Total_Tambah_Now']),rp($arr_aset2[$row->Kd_Aset2]['Jumlah_Akhir']),rp($arr_aset2[$row->Kd_Aset2]['Total_Akhir'])));
	        
	        $kd_aset3_list = array();	        
			foreach ($data->result() as $row2) {

	        	if ($row->Kd_Aset2 == $row2->Kd_Aset2 AND !in_array($row2->Kd_Aset3, $kd_aset3_list)) {
	        		array_push($kd_aset3_list, $row2->Kd_Aset3);
	        		$pdf->RowAset3(array(d2($row2->Kd_Aset1).".".d2($row2->Kd_Aset2).".".d2($row2->Kd_Aset3), $row2->Nm_SSH3, rp($arr_aset3[$row2->Kd_Aset2][$row2->Kd_Aset3]['Jumlah_Awal']),rp($arr_aset3[$row2->Kd_Aset2][$row2->Kd_Aset3]['Total_Awal']),rp($arr_aset3[$row2->Kd_Aset2][$row2->Kd_Aset3]['Jumlah_Kurang_Now']),rp($arr_aset3[$row2->Kd_Aset2][$row2->Kd_Aset3]['Total_Kurang_Now']),rp($arr_aset3[$row2->Kd_Aset2][$row2->Kd_Aset3]['Jumlah_Tambah_Now']),rp($arr_aset3[$row2->Kd_Aset2][$row2->Kd_Aset3]['Total_Tambah_Now']),rp($arr_aset3[$row2->Kd_Aset2][$row2->Kd_Aset3]['Jumlah_Akhir']),rp($arr_aset3[$row2->Kd_Aset2][$row2->Kd_Aset3]['Total_Akhir'])));
	        		
	        		$kd_aset4_list = array(); 
	        		foreach ($data->result() as $row3) {
			        	if ($row2->Kd_Aset2 == $row3->Kd_Aset2 && $row2->Kd_Aset3 == $row3->Kd_Aset3 && !in_array($row3->Kd_Aset4, $kd_aset4_list)) {
			        		array_push($kd_aset4_list, $row3->Kd_Aset4);
			        		$pdf->RowAset4(array(d2($row3->Kd_Aset1).".".d2($row3->Kd_Aset2).".".d2($row3->Kd_Aset3).".".d2($row3->Kd_Aset4), $row3->Nm_SSH4,rp($arr_aset4[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]['Jumlah_Awal']),rp($arr_aset4[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]['Total_Awal']),rp($arr_aset4[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]['Jumlah_Kurang_Now']),rp($arr_aset4[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]['Total_Kurang_Now']),rp($arr_aset4[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]['Jumlah_Tambah_Now']),rp($arr_aset4[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]['Total_Tambah_Now']),rp($arr_aset4[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]['Jumlah_Akhir']),rp($arr_aset4[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]['Total_Akhir'])));

			        		$kd_aset5_list = array(); 
			        		foreach ($data->result() as $row4) {
					        	if ($row3->Kd_Aset2 == $row4->Kd_Aset2 && $row3->Kd_Aset3 == $row4->Kd_Aset3 && $row3->Kd_Aset4 == $row4->Kd_Aset4 && !in_array($row4->Kd_Aset5, $kd_aset5_list)) {
					        		array_push($kd_aset5_list, $row4->Kd_Aset5);
					        		$pdf->RowAset5(array(d2($row4->Kd_Aset1).".".d2($row4->Kd_Aset2).".".d2($row4->Kd_Aset3).".".d2($row4->Kd_Aset4).".".d2($row4->Kd_Aset5), $row4->Nm_SSH5, rp($arr_aset5[$row4->Kd_Aset2][$row4->Kd_Aset3][$row4->Kd_Aset4][$row4->Kd_Aset5]['Jumlah_Awal']),rp($arr_aset5[$row4->Kd_Aset2][$row4->Kd_Aset3][$row4->Kd_Aset4][$row4->Kd_Aset5]['Total_Awal']),rp($arr_aset5[$row4->Kd_Aset2][$row4->Kd_Aset3][$row4->Kd_Aset4][$row4->Kd_Aset5]['Jumlah_Kurang_Now']),rp($arr_aset5[$row4->Kd_Aset2][$row4->Kd_Aset3][$row4->Kd_Aset4][$row4->Kd_Aset5]['Total_Kurang_Now']),rp($arr_aset5[$row4->Kd_Aset2][$row4->Kd_Aset3][$row4->Kd_Aset4][$row4->Kd_Aset5]['Jumlah_Tambah_Now']),rp($arr_aset5[$row4->Kd_Aset2][$row4->Kd_Aset3][$row4->Kd_Aset4][$row4->Kd_Aset5]['Total_Tambah_Now']),rp($arr_aset5[$row4->Kd_Aset2][$row4->Kd_Aset3][$row4->Kd_Aset4][$row4->Kd_Aset5]['Jumlah_Akhir']),rp($arr_aset5[$row4->Kd_Aset2][$row4->Kd_Aset3][$row4->Kd_Aset4][$row4->Kd_Aset5]['Total_Akhir']) ));
					        	}
					        }

			        	}
			        }
	        		 
	        	}
	        }

		}
	}

	/* END KIB A  */

	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}

	$pdf->Output("SIMDO_Rekap_Persediaan.pdf","I");
?>