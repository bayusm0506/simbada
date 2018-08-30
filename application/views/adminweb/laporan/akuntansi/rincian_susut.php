<?php

	$this->load->library('akuntansi/PDF_MC_rincian_susut');
	
	$pdf=new PDF_MC_rincian_susut('P','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(280,5,"RINCIAN BARANG PENYUSUTAN \n ".NM_PEMDA,0,'C');
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
	
	$pdf->SetFont('Times','','10');
	$pdf->SetWidths(array(35,90,40,40,40,40));
	$pdf->SetAligns(array('C','C','C','C','C','C'));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3", "4", "5", "6"));
	$pdf->SetAligns(array('L','L','R','R','R','R'));

	/* START KIB A  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_nb_awal   = sum_arr($kiba,'NB_Awal');
	$total_susut     = sum_arr($kiba,'Susut');
	$total_akm_susut = sum_arr($kiba,'Akm_Susut');
	$total_nb_akhir  = sum_arr($kiba,'NB_Akhir');
	$pdf->Row(array("      02","TANAH",rp($total_nb_awal),rp($total_susut),rp($total_akm_susut),rp($total_nb_akhir)));

	$total_aset2_nb_awal   = array();
	$total_aset3_nb_awal   = array();
	$total_aset4_nb_awal   = array();
	$total_aset2_susut     = array();
	$total_aset3_susut     = array();
	$total_aset4_susut     = array();
	$total_aset2_akm_susut = array();
	$total_aset3_akm_susut = array();
	$total_aset4_akm_susut = array();
	$total_aset2_nb_akhir  = array();
	$total_aset3_nb_akhir  = array();
	$total_aset4_nb_akhir  = array();
	foreach ($kiba->result() as $row){
		if (!isset($total_aset2_nb_awal[$row->Kd_Aset2])) {
				$total_aset2_nb_awal[$row->Kd_Aset2]   = $row->NB_Awal;
				$total_aset2_susut[$row->Kd_Aset2]     = $row->Susut;
				$total_aset2_akm_susut[$row->Kd_Aset2] = $row->Akm_Susut;
				$total_aset2_nb_akhir[$row->Kd_Aset2]  = $row->NB_Akhir;
		}else{
				$total_aset2_nb_awal[$row->Kd_Aset2]   += $row->NB_Awal;
				$total_aset2_susut[$row->Kd_Aset2]     += $row->Susut;
				$total_aset2_akm_susut[$row->Kd_Aset2] += $row->Akm_Susut;
				$total_aset2_nb_akhir[$row->Kd_Aset2]  += $row->NB_Akhir;
		}

		if (!isset($total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3])) {
				$total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3]   = $row->NB_Awal;
				$total_aset3_susut[$row->Kd_Aset2][$row->Kd_Aset3]     = $row->Susut;
				$total_aset3_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3] = $row->Akm_Susut;
				$total_aset3_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3]  = $row->NB_Akhir;
   		}else{
   				$total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3]   += $row->NB_Awal;
				$total_aset3_susut[$row->Kd_Aset2][$row->Kd_Aset3]     += $row->Susut;
				$total_aset3_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3] += $row->Akm_Susut;
				$total_aset3_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3]  += $row->NB_Akhir;
   		}

   		if (!isset($total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4])) {
   				$total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]   = $row->NB_Awal;
				$total_aset4_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]     = $row->Susut;
				$total_aset4_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] = $row->Akm_Susut;
				$total_aset4_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]  = $row->NB_Akhir;
   		}else{
   				$total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]   += $row->NB_Awal;
				$total_aset4_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]     += $row->Susut;
				$total_aset4_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] += $row->Akm_Susut;
				$total_aset4_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]  += $row->NB_Akhir;
   		}

	}

	
	$kd_aset2_list = array();
	foreach ($kiba->result() as $row){

		if(!in_array($row->Kd_Aset2, $kd_aset2_list)) {
			array_push($kd_aset2_list, $row->Kd_Aset2);

			$pdf->RowAset2(array(d2($row->Kd_Aset1).".".d2($row->Kd_Aset2), $row->Nm_Aset2, rp($total_aset2_nb_awal[$row->Kd_Aset2]),rp($total_aset2_susut[$row->Kd_Aset2]),rp($total_aset2_akm_susut[$row->Kd_Aset2]),rp($total_aset2_nb_akhir[$row->Kd_Aset2])));
	        
	        $kd_aset3_list = array();	        
			foreach ($kiba->result() as $row2) {

	        	if ($row->Kd_Aset2 == $row2->Kd_Aset2 AND !in_array($row2->Kd_Aset3, $kd_aset3_list)) {
	        		array_push($kd_aset3_list, $row2->Kd_Aset3);
	        		$pdf->RowAset3(array(d2($row2->Kd_Aset1).".".d2($row2->Kd_Aset2).".".d2($row2->Kd_Aset3), $row2->Nm_Aset3, rp($total_aset3_nb_awal[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_susut[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_akm_susut[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_nb_akhir[$row2->Kd_Aset2][$row2->Kd_Aset3])));
	        		
	        		$kd_aset4_list = array(); 
	        		foreach ($kiba->result() as $row3) {
			        	if ($row2->Kd_Aset2 == $row3->Kd_Aset2 && $row2->Kd_Aset3 == $row3->Kd_Aset3 && !in_array($row3->Kd_Aset4, $kd_aset4_list)) {
			        		array_push($kd_aset4_list, $row3->Kd_Aset4);
			        		$pdf->RowAset4(array(d2($row3->Kd_Aset1).".".d2($row3->Kd_Aset2).".".d2($row3->Kd_Aset3).".".d2($row3->Kd_Aset4), $row3->Nm_Aset4, rp($total_aset4_nb_awal[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_susut[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_akm_susut[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_nb_akhir[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4])));

			        		$kd_aset5_list = array(); 
			        		foreach ($kiba->result() as $row4) {
					        	if ($row3->Kd_Aset2 == $row4->Kd_Aset2 && $row3->Kd_Aset3 == $row4->Kd_Aset3 && $row3->Kd_Aset4 == $row4->Kd_Aset4 && !in_array($row4->Kd_Aset5, $kd_aset5_list)) {
					        		array_push($kd_aset5_list, $row4->Kd_Aset5);
					        		$pdf->RowAset5(array(d2($row4->Kd_Aset1).".".d2($row4->Kd_Aset2).".".d2($row4->Kd_Aset3).".".d2($row4->Kd_Aset4).".".d2($row4->Kd_Aset5), $row4->Nm_Aset5, rp(total_arr($row4->NB_Awal)), rp(total_arr($row4->Susut)), rp(total_arr($row4->Akm_Susut)), rp(total_arr($row4->NB_Akhir)) ));
					        	}
					        }

			        	}
			        }
	        		 
	        	}
	        }

		}
	}

	/* END KIB A  */
	
	/* START KIB B  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_nb_awal   = sum_arr($kibb,'NB_Awal');
	$total_susut     = sum_arr($kibb,'Susut');
	$total_akm_susut = sum_arr($kibb,'Akm_Susut');
	$total_nb_akhir  = sum_arr($kibb,'NB_Akhir');
	$pdf->Row(array("      02","PERALATAN & MESIN",rp($total_nb_awal),rp($total_susut),rp($total_akm_susut),rp($total_nb_akhir)));

	$total_aset2_nb_awal   = array();
	$total_aset3_nb_awal   = array();
	$total_aset4_nb_awal   = array();
	$total_aset2_susut     = array();
	$total_aset3_susut     = array();
	$total_aset4_susut     = array();
	$total_aset2_akm_susut = array();
	$total_aset3_akm_susut = array();
	$total_aset4_akm_susut = array();
	$total_aset2_nb_akhir  = array();
	$total_aset3_nb_akhir  = array();
	$total_aset4_nb_akhir  = array();
	foreach ($kibb->result() as $row){
		if (!isset($total_aset2_nb_awal[$row->Kd_Aset2])) {
				$total_aset2_nb_awal[$row->Kd_Aset2]   = $row->NB_Awal;
				$total_aset2_susut[$row->Kd_Aset2]     = $row->Susut;
				$total_aset2_akm_susut[$row->Kd_Aset2] = $row->Akm_Susut;
				$total_aset2_nb_akhir[$row->Kd_Aset2]  = $row->NB_Akhir;
		}else{
				$total_aset2_nb_awal[$row->Kd_Aset2]   += $row->NB_Awal;
				$total_aset2_susut[$row->Kd_Aset2]     += $row->Susut;
				$total_aset2_akm_susut[$row->Kd_Aset2] += $row->Akm_Susut;
				$total_aset2_nb_akhir[$row->Kd_Aset2]  += $row->NB_Akhir;
		}

		if (!isset($total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3])) {
				$total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3]   = $row->NB_Awal;
				$total_aset3_susut[$row->Kd_Aset2][$row->Kd_Aset3]     = $row->Susut;
				$total_aset3_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3] = $row->Akm_Susut;
				$total_aset3_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3]  = $row->NB_Akhir;
   		}else{
   				$total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3]   += $row->NB_Awal;
				$total_aset3_susut[$row->Kd_Aset2][$row->Kd_Aset3]     += $row->Susut;
				$total_aset3_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3] += $row->Akm_Susut;
				$total_aset3_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3]  += $row->NB_Akhir;
   		}

   		if (!isset($total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4])) {
   				$total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]   = $row->NB_Awal;
				$total_aset4_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]     = $row->Susut;
				$total_aset4_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] = $row->Akm_Susut;
				$total_aset4_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]  = $row->NB_Akhir;
   		}else{
   				$total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]   += $row->NB_Awal;
				$total_aset4_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]     += $row->Susut;
				$total_aset4_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] += $row->Akm_Susut;
				$total_aset4_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]  += $row->NB_Akhir;
   		}

	}

	
	$kd_aset2_list = array();
	foreach ($kibb->result() as $row){

		if(!in_array($row->Kd_Aset2, $kd_aset2_list)) {
			array_push($kd_aset2_list, $row->Kd_Aset2);

			$pdf->RowAset2(array(d2($row->Kd_Aset1).".".d2($row->Kd_Aset2), $row->Nm_Aset2, rp($total_aset2_nb_awal[$row->Kd_Aset2]),rp($total_aset2_susut[$row->Kd_Aset2]),rp($total_aset2_akm_susut[$row->Kd_Aset2]),rp($total_aset2_nb_akhir[$row->Kd_Aset2])));
	        
	        $kd_aset3_list = array();	        
			foreach ($kibb->result() as $row2) {

	        	if ($row->Kd_Aset2 == $row2->Kd_Aset2 AND !in_array($row2->Kd_Aset3, $kd_aset3_list)) {
	        		array_push($kd_aset3_list, $row2->Kd_Aset3);
	        		$pdf->RowAset3(array(d2($row2->Kd_Aset1).".".d2($row2->Kd_Aset2).".".d2($row2->Kd_Aset3), $row2->Nm_Aset3, rp($total_aset3_nb_awal[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_susut[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_akm_susut[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_nb_akhir[$row2->Kd_Aset2][$row2->Kd_Aset3])));
	        		
	        		$kd_aset4_list = array(); 
	        		foreach ($kibb->result() as $row3) {
			        	if ($row2->Kd_Aset2 == $row3->Kd_Aset2 && $row2->Kd_Aset3 == $row3->Kd_Aset3 && !in_array($row3->Kd_Aset4, $kd_aset4_list)) {
			        		array_push($kd_aset4_list, $row3->Kd_Aset4);
			        		$pdf->RowAset4(array(d2($row3->Kd_Aset1).".".d2($row3->Kd_Aset2).".".d2($row3->Kd_Aset3).".".d2($row3->Kd_Aset4), $row3->Nm_Aset4, rp($total_aset4_nb_awal[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_susut[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_akm_susut[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_nb_akhir[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4])));

			        		$kd_aset5_list = array(); 
			        		foreach ($kibb->result() as $row4) {
					        	if ($row3->Kd_Aset2 == $row4->Kd_Aset2 && $row3->Kd_Aset3 == $row4->Kd_Aset3 && $row3->Kd_Aset4 == $row4->Kd_Aset4 && !in_array($row4->Kd_Aset5, $kd_aset5_list)) {
					        		array_push($kd_aset5_list, $row4->Kd_Aset5);
					        		$pdf->RowAset5(array(d2($row4->Kd_Aset1).".".d2($row4->Kd_Aset2).".".d2($row4->Kd_Aset3).".".d2($row4->Kd_Aset4).".".d2($row4->Kd_Aset5), $row4->Nm_Aset5, rp(total_arr($row4->NB_Awal)), rp(total_arr($row4->Susut)), rp(total_arr($row4->Akm_Susut)), rp(total_arr($row4->NB_Akhir)) ));
					        	}
					        }

			        	}
			        }
	        		 
	        	}
	        }

		}
	}

	/* END KIB B  */

	/* START KIB C  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_nb_awal   = sum_arr($kibc,'NB_Awal');
	$total_susut     = sum_arr($kibc,'Susut');
	$total_akm_susut = sum_arr($kibc,'Akm_Susut');
	$total_nb_akhir  = sum_arr($kibc,'NB_Akhir');
	$pdf->Row(array("      03","GEDUNG DAN BANGUNAN",rp($total_nb_awal),rp($total_susut),rp($total_akm_susut),rp($total_nb_akhir)));

	$total_aset2_nb_awal   = array();
	$total_aset3_nb_awal   = array();
	$total_aset4_nb_awal   = array();
	$total_aset2_susut     = array();
	$total_aset3_susut     = array();
	$total_aset4_susut     = array();
	$total_aset2_akm_susut = array();
	$total_aset3_akm_susut = array();
	$total_aset4_akm_susut = array();
	$total_aset2_nb_akhir  = array();
	$total_aset3_nb_akhir  = array();
	$total_aset4_nb_akhir  = array();
	foreach ($kibc->result() as $row){
		if (!isset($total_aset2_nb_awal[$row->Kd_Aset2])) {
				$total_aset2_nb_awal[$row->Kd_Aset2]   = $row->NB_Awal;
				$total_aset2_susut[$row->Kd_Aset2]     = $row->Susut;
				$total_aset2_akm_susut[$row->Kd_Aset2] = $row->Akm_Susut;
				$total_aset2_nb_akhir[$row->Kd_Aset2]  = $row->NB_Akhir;
		}else{
				$total_aset2_nb_awal[$row->Kd_Aset2]   += $row->NB_Awal;
				$total_aset2_susut[$row->Kd_Aset2]     += $row->Susut;
				$total_aset2_akm_susut[$row->Kd_Aset2] += $row->Akm_Susut;
				$total_aset2_nb_akhir[$row->Kd_Aset2]  += $row->NB_Akhir;
		}

		if (!isset($total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3])) {
				$total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3]   = $row->NB_Awal;
				$total_aset3_susut[$row->Kd_Aset2][$row->Kd_Aset3]     = $row->Susut;
				$total_aset3_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3] = $row->Akm_Susut;
				$total_aset3_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3]  = $row->NB_Akhir;
   		}else{
   				$total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3]   += $row->NB_Awal;
				$total_aset3_susut[$row->Kd_Aset2][$row->Kd_Aset3]     += $row->Susut;
				$total_aset3_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3] += $row->Akm_Susut;
				$total_aset3_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3]  += $row->NB_Akhir;
   		}

   		if (!isset($total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4])) {
   				$total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]   = $row->NB_Awal;
				$total_aset4_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]     = $row->Susut;
				$total_aset4_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] = $row->Akm_Susut;
				$total_aset4_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]  = $row->NB_Akhir;
   		}else{
   				$total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]   += $row->NB_Awal;
				$total_aset4_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]     += $row->Susut;
				$total_aset4_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] += $row->Akm_Susut;
				$total_aset4_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]  += $row->NB_Akhir;
   		}

	}

	
	$kd_aset2_list = array();
	foreach ($kibc->result() as $row){

		if(!in_array($row->Kd_Aset2, $kd_aset2_list)) {
			array_push($kd_aset2_list, $row->Kd_Aset2);

			$pdf->RowAset2(array(d2($row->Kd_Aset1).".".d2($row->Kd_Aset2), $row->Nm_Aset2, rp($total_aset2_nb_awal[$row->Kd_Aset2]),rp($total_aset2_susut[$row->Kd_Aset2]),rp($total_aset2_akm_susut[$row->Kd_Aset2]),rp($total_aset2_nb_akhir[$row->Kd_Aset2])));
	        
	        $kd_aset3_list = array();	        
			foreach ($kibc->result() as $row2) {

	        	if ($row->Kd_Aset2 == $row2->Kd_Aset2 AND !in_array($row2->Kd_Aset3, $kd_aset3_list)) {
	        		array_push($kd_aset3_list, $row2->Kd_Aset3);
	        		$pdf->RowAset3(array(d2($row2->Kd_Aset1).".".d2($row2->Kd_Aset2).".".d2($row2->Kd_Aset3), $row2->Nm_Aset3, rp($total_aset3_nb_awal[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_susut[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_akm_susut[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_nb_akhir[$row2->Kd_Aset2][$row2->Kd_Aset3])));
	        		
	        		$kd_aset4_list = array(); 
	        		foreach ($kibc->result() as $row3) {
			        	if ($row2->Kd_Aset2 == $row3->Kd_Aset2 && $row2->Kd_Aset3 == $row3->Kd_Aset3 && !in_array($row3->Kd_Aset4, $kd_aset4_list)) {
			        		array_push($kd_aset4_list, $row3->Kd_Aset4);
			        		$pdf->RowAset4(array(d2($row3->Kd_Aset1).".".d2($row3->Kd_Aset2).".".d2($row3->Kd_Aset3).".".d2($row3->Kd_Aset4), $row3->Nm_Aset4, rp($total_aset4_nb_awal[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_susut[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_akm_susut[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_nb_akhir[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4])));

			        		$kd_aset5_list = array(); 
			        		foreach ($kibc->result() as $row4) {
					        	if ($row3->Kd_Aset2 == $row4->Kd_Aset2 && $row3->Kd_Aset3 == $row4->Kd_Aset3 && $row3->Kd_Aset4 == $row4->Kd_Aset4 && !in_array($row4->Kd_Aset5, $kd_aset5_list)) {
					        		array_push($kd_aset5_list, $row4->Kd_Aset5);
					        		$pdf->RowAset5(array(d2($row4->Kd_Aset1).".".d2($row4->Kd_Aset2).".".d2($row4->Kd_Aset3).".".d2($row4->Kd_Aset4).".".d2($row4->Kd_Aset5), $row4->Nm_Aset5, rp(total_arr($row4->NB_Awal)), rp(total_arr($row4->Susut)), rp(total_arr($row4->Akm_Susut)), rp(total_arr($row4->NB_Akhir)) ));
					        	}
					        }

			        	}
			        }
	        		 
	        	}
	        }

		}
	}

	/* END KIB C  */



    /* START KIB D  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_nb_awal   = sum_arr($kibd,'NB_Awal');
	$total_susut     = sum_arr($kibd,'Susut');
	$total_akm_susut = sum_arr($kibd,'Akm_Susut');
	$total_nb_akhir  = sum_arr($kibd,'NB_Akhir');
	$pdf->Row(array("      04","JALAN, IRIGASI & JARINGAN",rp($total_nb_awal),rp($total_susut),rp($total_akm_susut),rp($total_nb_akhir)));

	$total_aset2_nb_awal   = array();
	$total_aset3_nb_awal   = array();
	$total_aset4_nb_awal   = array();
	$total_aset2_susut     = array();
	$total_aset3_susut     = array();
	$total_aset4_susut     = array();
	$total_aset2_akm_susut = array();
	$total_aset3_akm_susut = array();
	$total_aset4_akm_susut = array();
	$total_aset2_nb_akhir  = array();
	$total_aset3_nb_akhir  = array();
	$total_aset4_nb_akhir  = array();
	foreach ($kibd->result() as $row){
		if (!isset($total_aset2_nb_awal[$row->Kd_Aset2])) {
				$total_aset2_nb_awal[$row->Kd_Aset2]   = $row->NB_Awal;
				$total_aset2_susut[$row->Kd_Aset2]     = $row->Susut;
				$total_aset2_akm_susut[$row->Kd_Aset2] = $row->Akm_Susut;
				$total_aset2_nb_akhir[$row->Kd_Aset2]  = $row->NB_Akhir;
		}else{
				$total_aset2_nb_awal[$row->Kd_Aset2]   += $row->NB_Awal;
				$total_aset2_susut[$row->Kd_Aset2]     += $row->Susut;
				$total_aset2_akm_susut[$row->Kd_Aset2] += $row->Akm_Susut;
				$total_aset2_nb_akhir[$row->Kd_Aset2]  += $row->NB_Akhir;
		}

		if (!isset($total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3])) {
				$total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3]   = $row->NB_Awal;
				$total_aset3_susut[$row->Kd_Aset2][$row->Kd_Aset3]     = $row->Susut;
				$total_aset3_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3] = $row->Akm_Susut;
				$total_aset3_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3]  = $row->NB_Akhir;
   		}else{
   				$total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3]   += $row->NB_Awal;
				$total_aset3_susut[$row->Kd_Aset2][$row->Kd_Aset3]     += $row->Susut;
				$total_aset3_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3] += $row->Akm_Susut;
				$total_aset3_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3]  += $row->NB_Akhir;
   		}

   		if (!isset($total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4])) {
   				$total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]   = $row->NB_Awal;
				$total_aset4_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]     = $row->Susut;
				$total_aset4_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] = $row->Akm_Susut;
				$total_aset4_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]  = $row->NB_Akhir;
   		}else{
   				$total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]   += $row->NB_Awal;
				$total_aset4_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]     += $row->Susut;
				$total_aset4_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] += $row->Akm_Susut;
				$total_aset4_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]  += $row->NB_Akhir;
   		}

	}

	
	$kd_aset2_list = array();
	foreach ($kibd->result() as $row){

		if(!in_array($row->Kd_Aset2, $kd_aset2_list)) {
			array_push($kd_aset2_list, $row->Kd_Aset2);

			$pdf->RowAset2(array(d2($row->Kd_Aset1).".".d2($row->Kd_Aset2), $row->Nm_Aset2, rp($total_aset2_nb_awal[$row->Kd_Aset2]),rp($total_aset2_susut[$row->Kd_Aset2]),rp($total_aset2_akm_susut[$row->Kd_Aset2]),rp($total_aset2_nb_akhir[$row->Kd_Aset2])));
	        
	        $kd_aset3_list = array();	        
			foreach ($kibd->result() as $row2) {

	        	if ($row->Kd_Aset2 == $row2->Kd_Aset2 AND !in_array($row2->Kd_Aset3, $kd_aset3_list)) {
	        		array_push($kd_aset3_list, $row2->Kd_Aset3);
	        		$pdf->RowAset3(array(d2($row2->Kd_Aset1).".".d2($row2->Kd_Aset2).".".d2($row2->Kd_Aset3), $row2->Nm_Aset3, rp($total_aset3_nb_awal[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_susut[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_akm_susut[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_nb_akhir[$row2->Kd_Aset2][$row2->Kd_Aset3])));
	        		
	        		$kd_aset4_list = array(); 
	        		foreach ($kibd->result() as $row3) {
			        	if ($row2->Kd_Aset2 == $row3->Kd_Aset2 && $row2->Kd_Aset3 == $row3->Kd_Aset3 && !in_array($row3->Kd_Aset4, $kd_aset4_list)) {
			        		array_push($kd_aset4_list, $row3->Kd_Aset4);
			        		$pdf->RowAset4(array(d2($row3->Kd_Aset1).".".d2($row3->Kd_Aset2).".".d2($row3->Kd_Aset3).".".d2($row3->Kd_Aset4), $row3->Nm_Aset4, rp($total_aset4_nb_awal[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_susut[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_akm_susut[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_nb_akhir[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4])));

			        		$kd_aset5_list = array(); 
			        		foreach ($kibd->result() as $row4) {
					        	if ($row3->Kd_Aset2 == $row4->Kd_Aset2 && $row3->Kd_Aset3 == $row4->Kd_Aset3 && $row3->Kd_Aset4 == $row4->Kd_Aset4 && !in_array($row4->Kd_Aset5, $kd_aset5_list)) {
					        		array_push($kd_aset5_list, $row4->Kd_Aset5);
					        		$pdf->RowAset5(array(d2($row4->Kd_Aset1).".".d2($row4->Kd_Aset2).".".d2($row4->Kd_Aset3).".".d2($row4->Kd_Aset4).".".d2($row4->Kd_Aset5), $row4->Nm_Aset5, rp(total_arr($row4->NB_Awal)), rp(total_arr($row4->Susut)), rp(total_arr($row4->Akm_Susut)), rp(total_arr($row4->NB_Akhir)) ));
					        	}
					        }

			        	}
			        }
	        		 
	        	}
	        }

		}
	}

	/* END KIB D  */

	/* START KIB E  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_nb_awal   = sum_arr($kibe,'NB_Awal');
	$total_susut     = sum_arr($kibe,'Susut');
	$total_akm_susut = sum_arr($kibe,'Akm_Susut');
	$total_nb_akhir  = sum_arr($kibe,'NB_Akhir');
	$pdf->Row(array("      05","ASET TETAP LAINNYA",rp($total_nb_awal),rp($total_susut),rp($total_akm_susut),rp($total_nb_akhir)));

	$total_aset2_nb_awal   = array();
	$total_aset3_nb_awal   = array();
	$total_aset4_nb_awal   = array();
	$total_aset2_susut     = array();
	$total_aset3_susut     = array();
	$total_aset4_susut     = array();
	$total_aset2_akm_susut = array();
	$total_aset3_akm_susut = array();
	$total_aset4_akm_susut = array();
	$total_aset2_nb_akhir  = array();
	$total_aset3_nb_akhir  = array();
	$total_aset4_nb_akhir  = array();
	foreach ($kibe->result() as $row){
		if (!isset($total_aset2_nb_awal[$row->Kd_Aset2])) {
				$total_aset2_nb_awal[$row->Kd_Aset2]   = $row->NB_Awal;
				$total_aset2_susut[$row->Kd_Aset2]     = $row->Susut;
				$total_aset2_akm_susut[$row->Kd_Aset2] = $row->Akm_Susut;
				$total_aset2_nb_akhir[$row->Kd_Aset2]  = $row->NB_Akhir;
		}else{
				$total_aset2_nb_awal[$row->Kd_Aset2]   += $row->NB_Awal;
				$total_aset2_susut[$row->Kd_Aset2]     += $row->Susut;
				$total_aset2_akm_susut[$row->Kd_Aset2] += $row->Akm_Susut;
				$total_aset2_nb_akhir[$row->Kd_Aset2]  += $row->NB_Akhir;
		}

		if (!isset($total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3])) {
				$total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3]   = $row->NB_Awal;
				$total_aset3_susut[$row->Kd_Aset2][$row->Kd_Aset3]     = $row->Susut;
				$total_aset3_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3] = $row->Akm_Susut;
				$total_aset3_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3]  = $row->NB_Akhir;
   		}else{
   				$total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3]   += $row->NB_Awal;
				$total_aset3_susut[$row->Kd_Aset2][$row->Kd_Aset3]     += $row->Susut;
				$total_aset3_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3] += $row->Akm_Susut;
				$total_aset3_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3]  += $row->NB_Akhir;
   		}

   		if (!isset($total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4])) {
   				$total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]   = $row->NB_Awal;
				$total_aset4_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]     = $row->Susut;
				$total_aset4_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] = $row->Akm_Susut;
				$total_aset4_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]  = $row->NB_Akhir;
   		}else{
   				$total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]   += $row->NB_Awal;
				$total_aset4_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]     += $row->Susut;
				$total_aset4_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] += $row->Akm_Susut;
				$total_aset4_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]  += $row->NB_Akhir;
   		}

	}

	
	$kd_aset2_list = array();
	foreach ($kibe->result() as $row){

		if(!in_array($row->Kd_Aset2, $kd_aset2_list)) {
			array_push($kd_aset2_list, $row->Kd_Aset2);

			$pdf->RowAset2(array(d2($row->Kd_Aset1).".".d2($row->Kd_Aset2), $row->Nm_Aset2, rp($total_aset2_nb_awal[$row->Kd_Aset2]),rp($total_aset2_susut[$row->Kd_Aset2]),rp($total_aset2_akm_susut[$row->Kd_Aset2]),rp($total_aset2_nb_akhir[$row->Kd_Aset2])));
	        
	        $kd_aset3_list = array();	        
			foreach ($kibe->result() as $row2) {

	        	if ($row->Kd_Aset2 == $row2->Kd_Aset2 AND !in_array($row2->Kd_Aset3, $kd_aset3_list)) {
	        		array_push($kd_aset3_list, $row2->Kd_Aset3);
	        		$pdf->RowAset3(array(d2($row2->Kd_Aset1).".".d2($row2->Kd_Aset2).".".d2($row2->Kd_Aset3), $row2->Nm_Aset3, rp($total_aset3_nb_awal[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_susut[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_akm_susut[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_nb_akhir[$row2->Kd_Aset2][$row2->Kd_Aset3])));
	        		
	        		$kd_aset4_list = array(); 
	        		foreach ($kibe->result() as $row3) {
			        	if ($row2->Kd_Aset2 == $row3->Kd_Aset2 && $row2->Kd_Aset3 == $row3->Kd_Aset3 && !in_array($row3->Kd_Aset4, $kd_aset4_list)) {
			        		array_push($kd_aset4_list, $row3->Kd_Aset4);
			        		$pdf->RowAset4(array(d2($row3->Kd_Aset1).".".d2($row3->Kd_Aset2).".".d2($row3->Kd_Aset3).".".d2($row3->Kd_Aset4), $row3->Nm_Aset4, rp($total_aset4_nb_awal[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_susut[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_akm_susut[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_nb_akhir[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4])));

			        		$kd_aset5_list = array(); 
			        		foreach ($kibe->result() as $row4) {
					        	if ($row3->Kd_Aset2 == $row4->Kd_Aset2 && $row3->Kd_Aset3 == $row4->Kd_Aset3 && $row3->Kd_Aset4 == $row4->Kd_Aset4 && !in_array($row4->Kd_Aset5, $kd_aset5_list)) {
					        		array_push($kd_aset5_list, $row4->Kd_Aset5);
					        		$pdf->RowAset5(array(d2($row4->Kd_Aset1).".".d2($row4->Kd_Aset2).".".d2($row4->Kd_Aset3).".".d2($row4->Kd_Aset4).".".d2($row4->Kd_Aset5), $row4->Nm_Aset5, rp(total_arr($row4->NB_Awal)), rp(total_arr($row4->Susut)), rp(total_arr($row4->Akm_Susut)), rp(total_arr($row4->NB_Akhir)) ));
					        	}
					        }

			        	}
			        }
	        		 
	        	}
	        }

		}
	}

	/* END KIB E  */

	/* START KIB F  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_nb_awal   = sum_arr($kibf,'NB_Awal');
	$total_susut     = sum_arr($kibf,'Susut');
	$total_akm_susut = sum_arr($kibf,'Akm_Susut');
	$total_nb_akhir  = sum_arr($kibf,'NB_Akhir');
	$pdf->Row(array("      06","KONSTRUKSI DALAM PENGERJAAN",rp($total_nb_awal),rp($total_susut),rp($total_akm_susut),rp($total_nb_akhir)));

	$total_aset2_nb_awal   = array();
	$total_aset3_nb_awal   = array();
	$total_aset4_nb_awal   = array();
	$total_aset2_susut     = array();
	$total_aset3_susut     = array();
	$total_aset4_susut     = array();
	$total_aset2_akm_susut = array();
	$total_aset3_akm_susut = array();
	$total_aset4_akm_susut = array();
	$total_aset2_nb_akhir  = array();
	$total_aset3_nb_akhir  = array();
	$total_aset4_nb_akhir  = array();
	foreach ($kibf->result() as $row){
		if (!isset($total_aset2_nb_awal[$row->Kd_Aset2])) {
				$total_aset2_nb_awal[$row->Kd_Aset2]   = $row->NB_Awal;
				$total_aset2_susut[$row->Kd_Aset2]     = $row->Susut;
				$total_aset2_akm_susut[$row->Kd_Aset2] = $row->Akm_Susut;
				$total_aset2_nb_akhir[$row->Kd_Aset2]  = $row->NB_Akhir;
		}else{
				$total_aset2_nb_awal[$row->Kd_Aset2]   += $row->NB_Awal;
				$total_aset2_susut[$row->Kd_Aset2]     += $row->Susut;
				$total_aset2_akm_susut[$row->Kd_Aset2] += $row->Akm_Susut;
				$total_aset2_nb_akhir[$row->Kd_Aset2]  += $row->NB_Akhir;
		}

		if (!isset($total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3])) {
				$total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3]   = $row->NB_Awal;
				$total_aset3_susut[$row->Kd_Aset2][$row->Kd_Aset3]     = $row->Susut;
				$total_aset3_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3] = $row->Akm_Susut;
				$total_aset3_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3]  = $row->NB_Akhir;
   		}else{
   				$total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3]   += $row->NB_Awal;
				$total_aset3_susut[$row->Kd_Aset2][$row->Kd_Aset3]     += $row->Susut;
				$total_aset3_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3] += $row->Akm_Susut;
				$total_aset3_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3]  += $row->NB_Akhir;
   		}

   		if (!isset($total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4])) {
   				$total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]   = $row->NB_Awal;
				$total_aset4_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]     = $row->Susut;
				$total_aset4_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] = $row->Akm_Susut;
				$total_aset4_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]  = $row->NB_Akhir;
   		}else{
   				$total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]   += $row->NB_Awal;
				$total_aset4_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]     += $row->Susut;
				$total_aset4_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] += $row->Akm_Susut;
				$total_aset4_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]  += $row->NB_Akhir;
   		}

	}

	
	$kd_aset2_list = array();
	foreach ($kibf->result() as $row){

		if(!in_array($row->Kd_Aset2, $kd_aset2_list)) {
			array_push($kd_aset2_list, $row->Kd_Aset2);

			$pdf->RowAset2(array(d2($row->Kd_Aset1).".".d2($row->Kd_Aset2), $row->Nm_Aset2, rp($total_aset2_nb_awal[$row->Kd_Aset2]),rp($total_aset2_susut[$row->Kd_Aset2]),rp($total_aset2_akm_susut[$row->Kd_Aset2]),rp($total_aset2_nb_akhir[$row->Kd_Aset2])));
	        
	        $kd_aset3_list = array();	        
			foreach ($kibf->result() as $row2) {

	        	if ($row->Kd_Aset2 == $row2->Kd_Aset2 AND !in_array($row2->Kd_Aset3, $kd_aset3_list)) {
	        		array_push($kd_aset3_list, $row2->Kd_Aset3);
	        		$pdf->RowAset3(array(d2($row2->Kd_Aset1).".".d2($row2->Kd_Aset2).".".d2($row2->Kd_Aset3), $row2->Nm_Aset3, rp($total_aset3_nb_awal[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_susut[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_akm_susut[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_nb_akhir[$row2->Kd_Aset2][$row2->Kd_Aset3])));
	        		
	        		$kd_aset4_list = array(); 
	        		foreach ($kibf->result() as $row3) {
			        	if ($row2->Kd_Aset2 == $row3->Kd_Aset2 && $row2->Kd_Aset3 == $row3->Kd_Aset3 && !in_array($row3->Kd_Aset4, $kd_aset4_list)) {
			        		array_push($kd_aset4_list, $row3->Kd_Aset4);
			        		$pdf->RowAset4(array(d2($row3->Kd_Aset1).".".d2($row3->Kd_Aset2).".".d2($row3->Kd_Aset3).".".d2($row3->Kd_Aset4), $row3->Nm_Aset4, rp($total_aset4_nb_awal[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_susut[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_akm_susut[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_nb_akhir[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4])));

			        		$kd_aset5_list = array(); 
			        		foreach ($kibf->result() as $row4) {
					        	if ($row3->Kd_Aset2 == $row4->Kd_Aset2 && $row3->Kd_Aset3 == $row4->Kd_Aset3 && $row3->Kd_Aset4 == $row4->Kd_Aset4 && !in_array($row4->Kd_Aset5, $kd_aset5_list)) {
					        		array_push($kd_aset5_list, $row4->Kd_Aset5);
					        		$pdf->RowAset5(array(d2($row4->Kd_Aset1).".".d2($row4->Kd_Aset2).".".d2($row4->Kd_Aset3).".".d2($row4->Kd_Aset4).".".d2($row4->Kd_Aset5), $row4->Nm_Aset5, rp(total_arr($row4->NB_Awal)), rp(total_arr($row4->Susut)), rp(total_arr($row4->Akm_Susut)), rp(total_arr($row4->NB_Akhir)) ));
					        	}
					        }

			        	}
			        }
	        		 
	        	}
	        }

		}
	}

	/* END KIB F  */

	/* START KIB LAINYA  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_nb_awal   = sum_arr($kib_lainnya,'NB_Awal');
	$total_susut     = sum_arr($kib_lainnya,'Susut');
	$total_akm_susut = sum_arr($kib_lainnya,'Akm_Susut');
	$total_nb_akhir  = sum_arr($kib_lainnya,'NB_Akhir');
	$pdf->Row(array("      07","ASET TAK BERWUJUD",rp($total_nb_awal),rp($total_susut),rp($total_akm_susut),rp($total_nb_akhir)));

	$total_aset2_nb_awal   = array();
	$total_aset3_nb_awal   = array();
	$total_aset4_nb_awal   = array();
	$total_aset2_susut     = array();
	$total_aset3_susut     = array();
	$total_aset4_susut     = array();
	$total_aset2_akm_susut = array();
	$total_aset3_akm_susut = array();
	$total_aset4_akm_susut = array();
	$total_aset2_nb_akhir  = array();
	$total_aset3_nb_akhir  = array();
	$total_aset4_nb_akhir  = array();
	foreach ($kib_lainnya->result() as $row){
		if (!isset($total_aset2_nb_awal[$row->Kd_Aset2])) {
				$total_aset2_nb_awal[$row->Kd_Aset2]   = $row->NB_Awal;
				$total_aset2_susut[$row->Kd_Aset2]     = $row->Susut;
				$total_aset2_akm_susut[$row->Kd_Aset2] = $row->Akm_Susut;
				$total_aset2_nb_akhir[$row->Kd_Aset2]  = $row->NB_Akhir;
		}else{
				$total_aset2_nb_awal[$row->Kd_Aset2]   += $row->NB_Awal;
				$total_aset2_susut[$row->Kd_Aset2]     += $row->Susut;
				$total_aset2_akm_susut[$row->Kd_Aset2] += $row->Akm_Susut;
				$total_aset2_nb_akhir[$row->Kd_Aset2]  += $row->NB_Akhir;
		}

		if (!isset($total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3])) {
				$total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3]   = $row->NB_Awal;
				$total_aset3_susut[$row->Kd_Aset2][$row->Kd_Aset3]     = $row->Susut;
				$total_aset3_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3] = $row->Akm_Susut;
				$total_aset3_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3]  = $row->NB_Akhir;
   		}else{
   				$total_aset3_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3]   += $row->NB_Awal;
				$total_aset3_susut[$row->Kd_Aset2][$row->Kd_Aset3]     += $row->Susut;
				$total_aset3_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3] += $row->Akm_Susut;
				$total_aset3_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3]  += $row->NB_Akhir;
   		}

   		if (!isset($total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4])) {
   				$total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]   = $row->NB_Awal;
				$total_aset4_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]     = $row->Susut;
				$total_aset4_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] = $row->Akm_Susut;
				$total_aset4_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]  = $row->NB_Akhir;
   		}else{
   				$total_aset4_nb_awal[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]   += $row->NB_Awal;
				$total_aset4_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]     += $row->Susut;
				$total_aset4_akm_susut[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] += $row->Akm_Susut;
				$total_aset4_nb_akhir[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4]  += $row->NB_Akhir;
   		}

	}

	
	$kd_aset2_list = array();
	foreach ($kib_lainnya->result() as $row){

		if(!in_array($row->Kd_Aset2, $kd_aset2_list)) {
			array_push($kd_aset2_list, $row->Kd_Aset2);

			$pdf->RowAset2(array(d2($row->Kd_Aset1).".".d2($row->Kd_Aset2), $row->Nm_Aset2, rp($total_aset2_nb_awal[$row->Kd_Aset2]),rp($total_aset2_susut[$row->Kd_Aset2]),rp($total_aset2_akm_susut[$row->Kd_Aset2]),rp($total_aset2_nb_akhir[$row->Kd_Aset2])));
	        
	        $kd_aset3_list = array();	        
			foreach ($kib_lainnya->result() as $row2) {

	        	if ($row->Kd_Aset2 == $row2->Kd_Aset2 AND !in_array($row2->Kd_Aset3, $kd_aset3_list)) {
	        		array_push($kd_aset3_list, $row2->Kd_Aset3);
	        		$pdf->RowAset3(array(d2($row2->Kd_Aset1).".".d2($row2->Kd_Aset2).".".d2($row2->Kd_Aset3), $row2->Nm_Aset3, rp($total_aset3_nb_awal[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_susut[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_akm_susut[$row2->Kd_Aset2][$row2->Kd_Aset3]),rp($total_aset3_nb_akhir[$row2->Kd_Aset2][$row2->Kd_Aset3])));
	        		
	        		$kd_aset4_list = array(); 
	        		foreach ($kib_lainnya->result() as $row3) {
			        	if ($row2->Kd_Aset2 == $row3->Kd_Aset2 && $row2->Kd_Aset3 == $row3->Kd_Aset3 && !in_array($row3->Kd_Aset4, $kd_aset4_list)) {
			        		array_push($kd_aset4_list, $row3->Kd_Aset4);
			        		$pdf->RowAset4(array(d2($row3->Kd_Aset1).".".d2($row3->Kd_Aset2).".".d2($row3->Kd_Aset3).".".d2($row3->Kd_Aset4), $row3->Nm_Aset4, rp($total_aset4_nb_awal[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_susut[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_akm_susut[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4]),rp($total_aset4_nb_akhir[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4])));

			        		$kd_aset5_list = array(); 
			        		foreach ($kib_lainnya->result() as $row4) {
					        	if ($row3->Kd_Aset2 == $row4->Kd_Aset2 && $row3->Kd_Aset3 == $row4->Kd_Aset3 && $row3->Kd_Aset4 == $row4->Kd_Aset4 && !in_array($row4->Kd_Aset5, $kd_aset5_list)) {
					        		array_push($kd_aset5_list, $row4->Kd_Aset5);
					        		$pdf->RowAset5(array(d2($row4->Kd_Aset1).".".d2($row4->Kd_Aset2).".".d2($row4->Kd_Aset3).".".d2($row4->Kd_Aset4).".".d2($row4->Kd_Aset5), $row4->Nm_Aset5, rp(total_arr($row4->NB_Awal)), rp(total_arr($row4->Susut)), rp(total_arr($row4->Akm_Susut)), rp(total_arr($row4->NB_Akhir)) ));
					        	}
					        }

			        	}
			        }
	        		 
	        	}
	        }

		}
	}

	/* END KIB LAINYA  */

	
	// $total_at = ($total_kiba + $total_kibb + $total_kibc + $total_kibd + $total_kibe + $total_kibf);

	// $pdf->Akumulasi("TOTAL ASET TETAP",rp($total_at));

	// /* Aset Lainya  */
	// $RB_kibb = sum_arr($kibb,'RB');
	// $RB_kibc = sum_arr($kibc,'RB');
	// $RB_kibd = sum_arr($kibd,'RB');
	// $RB_kibe = sum_arr($kibe,'RB');
	// $total_RB = $RB_kibb + $RB_kibc + $RB_kibd + $RB_kibe;

	// $total_lainnya = $total_RB + $kib_lainnya->NB_Akhir + $non_operasioanl->NB_Akhir; 
	// $pdf->SetFont('Times','B','10');	
	// $pdf->Row(array("      07","ASET LAINNYA",rp($total_lainnya)));

	// $pdf->SetFont('Times','','10');
	// /* KIB LAINYA - Aset lainnya  */
	// $pdf->Row(array("      07.20", "      Aset Lainnya", 0));
	// /* KIB LAINYA - Aset Rusak Berat */
	// $pdf->Row(array("      07.20", "      Rusak Berat", rp($total_RB)));
	// /* KIB LAINYA - Aset Non Operasional */
	// // $NonOP_kiba = sum_arr($kiba,'NB_Non_Operasional');
	// // $NonOP_kibb = sum_arr($kibb,'NB_Non_Operasional');
	// // $NonOP_kibc = sum_arr($kibc,'NB_Non_Operasional');
	// // $NonOP_kibd = sum_arr($kibd,'NB_Non_Operasional');
	// // $NonOP_kibe = sum_arr($kibe,'NB_Non_Operasional');

	// // $total_NonOP = $NonOP_kiba + $NonOP_kibb + $NonOP_kibc + $NonOP_kibd + $NonOP_kibe;
	// $total_NonOP = $non_operasioanl->NB_Akhir;
	// $pdf->Row(array("      07.22", "      Aset Non Operasional (Aset yang dimanfaatkan pihak lain)", rp($total_NonOP)));
	
	
	// /* KIB LAINYA - Aset Renovasi  */
	// $pdf->Row(array("      07.23", "      Aset yang dikerjasamakan dengan pihak ke 3", 0));
	// /* KIB LAINYA - Aset tak berwujud  */
	// $pdf->Row(array("      07.24", "      Aset Tak Berwujud", rp($kib_lainnya->NB_Akhir)));

	// $pdf->kosong("");

	// $pdf->Akumulasi("TOTAL ASET",rp($total_at+$total_lainnya));
	
	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}
	$pdf->Output("SIMDO_Rincian_Neraca.pdf","I");
?>