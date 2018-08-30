<?php

	$this->load->library('akuntansi/PDF_MC_rincian_neraca');
	
	$pdf=new PDF_MC_rincian_neraca('P','mm','f4');
	$pdf->Open();
	$pdf->AddPage();
	$pdf->Image(base_url().'asset/img/logo.png',10,5,22,0,'PNG');
	$pdf->SetFont('Arial','B',15);
	$pdf->MultiCell(280,5,"RINCIAN BARANG KE NERACA \n ".NM_PEMDA,0,'C');
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
	$pdf->SetWidths(array(50,175,58));
	$pdf->SetAligns(array('C','C','C'));
	$pdf->Rowheader();
	$pdf->RowJudul(array("1", "2", "3"));
	$pdf->SetAligns(array('L','L','R'));

	/* START KIB A  */
	$no=1;
	$pdf->SetFont('Times','B','10');
	$total_kiba = sum_arr($kiba,'Harga');	
	$pdf->Row(array("      01","TANAH",rp($total_kiba)));

	$total_kiba_aset2_arr = array();
	$total_kiba_aset3_arr = array();
	$total_kiba_aset4_arr = array();
	foreach ($kiba->result() as $row){
		if (!isset($total_kiba_aset2_arr[$row->Kd_Aset2])) {
		   	$total_kiba_aset2_arr[$row->Kd_Aset2] = $row->Harga;
		}else{
			$total_kiba_aset2_arr[$row->Kd_Aset2] += $row->Harga;
		}

		if (!isset($total_kiba_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3])) {
   			$total_kiba_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3] = $row->Harga;
   		}else{
   			$total_kiba_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3] += $row->Harga;
   		}

   		if (!isset($total_kiba_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4])) {
   			$total_kiba_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] = $row->Harga;
   		}else{
   			$total_kiba_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] += $row->Harga;
   		}

	}
	
	$kd_aset2_list_kiba = array();
	foreach ($kiba->result() as $row){

		if(!in_array($row->Kd_Aset2, $kd_aset2_list_kiba)) {
			array_push($kd_aset2_list_kiba, $row->Kd_Aset2);

			$pdf->RowAset2(array(d2($row->Kd_Aset1).".".d2($row->Kd_Aset2), $row->Nm_Aset2, rp($total_kiba_aset2_arr[$row->Kd_Aset2])));
	        
	        $kd_aset3_list_kiba = array();	        
			foreach ($kiba->result() as $row2) {

	        	if ($row->Kd_Aset2 == $row2->Kd_Aset2 AND !in_array($row2->Kd_Aset3, $kd_aset3_list_kiba)) {
	        		array_push($kd_aset3_list_kiba, $row2->Kd_Aset3);
	        		$pdf->RowAset3(array(d2($row2->Kd_Aset1).".".d2($row2->Kd_Aset2).".".d2($row2->Kd_Aset3), $row2->Nm_Aset3,  rp($total_kiba_aset3_arr[$row2->Kd_Aset2][$row2->Kd_Aset3])));
	        		
	        		$kd_aset4_list_kiba = array(); 
	        		foreach ($kiba->result() as $row3) {
			        	if ($row2->Kd_Aset2 == $row3->Kd_Aset2 && $row2->Kd_Aset3 == $row3->Kd_Aset3 && !in_array($row3->Kd_Aset4, $kd_aset4_list_kiba)) {
			        		array_push($kd_aset4_list_kiba, $row3->Kd_Aset4);
			        		$pdf->RowAset4(array(d2($row3->Kd_Aset1).".".d2($row3->Kd_Aset2).".".d2($row3->Kd_Aset3).".".d2($row3->Kd_Aset4), $row3->Nm_Aset4, rp($total_kiba_aset4_arr[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4])));

			        		$kd_aset5_list_kiba = array(); 
			        		foreach ($kiba->result() as $row4) {
					        	if ($row3->Kd_Aset2 == $row4->Kd_Aset2 && $row3->Kd_Aset3 == $row4->Kd_Aset3 && $row3->Kd_Aset4 == $row4->Kd_Aset4 && !in_array($row4->Kd_Aset5, $kd_aset5_list_kiba)) {
					        		array_push($kd_aset5_list_kiba, $row4->Kd_Aset5);
					        		$pdf->RowAset5(array(d2($row4->Kd_Aset1).".".d2($row4->Kd_Aset2).".".d2($row4->Kd_Aset3).".".d2($row4->Kd_Aset4).".".d2($row4->Kd_Aset5), $row4->Nm_Aset5, rp(total_arr($row4->Harga))));
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
	$total_kibb = sum_arr($kibb,'Harga');	
	$pdf->Row(array("      02","PERALATAN & MESIN",rp($total_kibb)));

	$total_aset2_arr = array();
	$total_aset3_arr = array();
	$total_aset4_arr = array();
	foreach ($kibb->result() as $row){
		if (!isset($total_aset2_arr[$row->Kd_Aset2])) {
		   	$total_aset2_arr[$row->Kd_Aset2] = $row->Harga;
		}else{
			$total_aset2_arr[$row->Kd_Aset2] += $row->Harga;
		}

		if (!isset($total_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3])) {
   			$total_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3] = $row->Harga;
   		}else{
   			$total_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3] += $row->Harga;
   		}

   		if (!isset($total_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4])) {
   			$total_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] = $row->Harga;
   		}else{
   			$total_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] += $row->Harga;
   		}

	}
	
	$kd_aset2_list = array();
	foreach ($kibb->result() as $row){

		if(!in_array($row->Kd_Aset2, $kd_aset2_list)) {
			array_push($kd_aset2_list, $row->Kd_Aset2);

			$pdf->RowAset2(array(d2($row->Kd_Aset1).".".d2($row->Kd_Aset2), $row->Nm_Aset2, rp($total_aset2_arr[$row->Kd_Aset2])));
	        
	        $kd_aset3_list = array();	        
			foreach ($kibb->result() as $row2) {

	        	if ($row->Kd_Aset2 == $row2->Kd_Aset2 AND !in_array($row2->Kd_Aset3, $kd_aset3_list)) {
	        		array_push($kd_aset3_list, $row2->Kd_Aset3);
	        		$pdf->RowAset3(array(d2($row2->Kd_Aset1).".".d2($row2->Kd_Aset2).".".d2($row2->Kd_Aset3), $row2->Nm_Aset3,  rp($total_aset3_arr[$row2->Kd_Aset2][$row2->Kd_Aset3])));
	        		
	        		$kd_aset4_list = array(); 
	        		foreach ($kibb->result() as $row3) {
			        	if ($row2->Kd_Aset2 == $row3->Kd_Aset2 && $row2->Kd_Aset3 == $row3->Kd_Aset3 && !in_array($row3->Kd_Aset4, $kd_aset4_list)) {
			        		array_push($kd_aset4_list, $row3->Kd_Aset4);
			        		$pdf->RowAset4(array(d2($row3->Kd_Aset1).".".d2($row3->Kd_Aset2).".".d2($row3->Kd_Aset3).".".d2($row3->Kd_Aset4), $row3->Nm_Aset4, rp($total_aset4_arr[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4])));

			        		$kd_aset5_list = array(); 
			        		foreach ($kibb->result() as $row4) {
					        	if ($row3->Kd_Aset2 == $row4->Kd_Aset2 && $row3->Kd_Aset3 == $row4->Kd_Aset3 && $row3->Kd_Aset4 == $row4->Kd_Aset4 && !in_array($row4->Kd_Aset5, $kd_aset5_list)) {
					        		array_push($kd_aset5_list, $row4->Kd_Aset5);
					        		$pdf->RowAset5(array(d2($row4->Kd_Aset1).".".d2($row4->Kd_Aset2).".".d2($row4->Kd_Aset3).".".d2($row4->Kd_Aset4).".".d2($row4->Kd_Aset5), $row4->Nm_Aset5, rp(total_arr($row4->Harga))));
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
	$total_kibc = sum_arr($kibc,'Harga');	
	$pdf->Row(array("      03","GEDUNG DAN BANGUNAN",rp($total_kibc)));

	$total_kibc_aset2_arr = array();
	$total_kibc_aset3_arr = array();
	$total_kibc_aset4_arr = array();
	foreach ($kibc->result() as $row){
		if (!isset($total_kibc_aset2_arr[$row->Kd_Aset2])) {
		   	$total_kibc_aset2_arr[$row->Kd_Aset2] = $row->Harga;
		}else{
			$total_kibc_aset2_arr[$row->Kd_Aset2] += $row->Harga;
		}

		if (!isset($total_kibc_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3])) {
   			$total_kibc_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3] = $row->Harga;
   		}else{
   			$total_kibc_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3] += $row->Harga;
   		}

   		if (!isset($total_kibc_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4])) {
   			$total_kibc_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] = $row->Harga;
   		}else{
   			$total_kibc_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] += $row->Harga;
   		}

	}
	
	$kd_aset2_list_kibc = array();
	foreach ($kibc->result() as $row){

		if(!in_array($row->Kd_Aset2, $kd_aset2_list_kibc)) {
			array_push($kd_aset2_list_kibc, $row->Kd_Aset2);

			$pdf->RowAset2(array(d2($row->Kd_Aset1).".".d2($row->Kd_Aset2), $row->Nm_Aset2, rp($total_kibc_aset2_arr[$row->Kd_Aset2])));
	        
	        $kd_aset3_list_kibc = array();	        
			foreach ($kibc->result() as $row2) {

	        	if ($row->Kd_Aset2 == $row2->Kd_Aset2 AND !in_array($row2->Kd_Aset3, $kd_aset3_list_kibc)) {
	        		array_push($kd_aset3_list_kibc, $row2->Kd_Aset3);
	        		$pdf->RowAset3(array(d2($row2->Kd_Aset1).".".d2($row2->Kd_Aset2).".".d2($row2->Kd_Aset3), $row2->Nm_Aset3,  rp($total_kibc_aset3_arr[$row2->Kd_Aset2][$row2->Kd_Aset3])));
	        		
	        		$kd_aset4_list_kibc = array(); 
	        		foreach ($kibc->result() as $row3) {
			        	if ($row2->Kd_Aset2 == $row3->Kd_Aset2 && $row2->Kd_Aset3 == $row3->Kd_Aset3 && !in_array($row3->Kd_Aset4, $kd_aset4_list_kibc)) {
			        		array_push($kd_aset4_list_kibc, $row3->Kd_Aset4);
			        		$pdf->RowAset4(array(d2($row3->Kd_Aset1).".".d2($row3->Kd_Aset2).".".d2($row3->Kd_Aset3).".".d2($row3->Kd_Aset4), $row3->Nm_Aset4, rp($total_kibc_aset4_arr[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4])));

			        		$kd_aset5_list_kibc = array(); 
			        		foreach ($kibc->result() as $row4) {
					        	if ($row3->Kd_Aset2 == $row4->Kd_Aset2 && $row3->Kd_Aset3 == $row4->Kd_Aset3 && $row3->Kd_Aset4 == $row4->Kd_Aset4 && !in_array($row4->Kd_Aset5, $kd_aset5_list_kibc)) {
					        		array_push($kd_aset5_list_kibc, $row4->Kd_Aset5);
					        		$pdf->RowAset5(array(d2($row4->Kd_Aset1).".".d2($row4->Kd_Aset2).".".d2($row4->Kd_Aset3).".".d2($row4->Kd_Aset4).".".d2($row4->Kd_Aset5), $row4->Nm_Aset5, rp(total_arr($row4->Harga))));
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
	$total_kibd = sum_arr($kibd,'Harga');	
	$pdf->Row(array("      04","JALAN, IRIGASI & JARINGAN",rp($total_kibd)));

	$total_kibd_aset2_arr = array();
	$total_kibd_aset3_arr = array();
	$total_kibd_aset4_arr = array();
	foreach ($kibd->result() as $row){
		if (!isset($total_kibd_aset2_arr[$row->Kd_Aset2])) {
		   	$total_kibd_aset2_arr[$row->Kd_Aset2] = $row->Harga;
		}else{
			$total_kibd_aset2_arr[$row->Kd_Aset2] += $row->Harga;
		}

		if (!isset($total_kibd_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3])) {
   			$total_kibd_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3] = $row->Harga;
   		}else{
   			$total_kibd_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3] += $row->Harga;
   		}

   		if (!isset($total_kibd_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4])) {
   			$total_kibd_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] = $row->Harga;
   		}else{
   			$total_kibd_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] += $row->Harga;
   		}

	}
	
	$kd_aset2_list_kibd = array();
	foreach ($kibd->result() as $row){

		if(!in_array($row->Kd_Aset2, $kd_aset2_list_kibd)) {
			array_push($kd_aset2_list_kibd, $row->Kd_Aset2);

			$pdf->RowAset2(array(d2($row->Kd_Aset1).".".d2($row->Kd_Aset2), $row->Nm_Aset2, rp($total_kibd_aset2_arr[$row->Kd_Aset2])));
	        
	        $kd_aset3_list_kibd = array();	        
			foreach ($kibd->result() as $row2) {

	        	if ($row->Kd_Aset2 == $row2->Kd_Aset2 AND !in_array($row2->Kd_Aset3, $kd_aset3_list_kibd)) {
	        		array_push($kd_aset3_list_kibd, $row2->Kd_Aset3);
	        		$pdf->RowAset3(array(d2($row2->Kd_Aset1).".".d2($row2->Kd_Aset2).".".d2($row2->Kd_Aset3), $row2->Nm_Aset3,  rp($total_kibd_aset3_arr[$row2->Kd_Aset2][$row2->Kd_Aset3])));
	        		
	        		$kd_aset4_list_kibd = array(); 
	        		foreach ($kibd->result() as $row3) {
			        	if ($row2->Kd_Aset2 == $row3->Kd_Aset2 && $row2->Kd_Aset3 == $row3->Kd_Aset3 && !in_array($row3->Kd_Aset4, $kd_aset4_list_kibd)) {
			        		array_push($kd_aset4_list_kibd, $row3->Kd_Aset4);
			        		$pdf->RowAset4(array(d2($row3->Kd_Aset1).".".d2($row3->Kd_Aset2).".".d2($row3->Kd_Aset3).".".d2($row3->Kd_Aset4), $row3->Nm_Aset4, rp($total_kibd_aset4_arr[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4])));

			        		$kd_aset5_list_kibd = array(); 
			        		foreach ($kibd->result() as $row4) {
					        	if ($row3->Kd_Aset2 == $row4->Kd_Aset2 && $row3->Kd_Aset3 == $row4->Kd_Aset3 && $row3->Kd_Aset4 == $row4->Kd_Aset4 && !in_array($row4->Kd_Aset5, $kd_aset5_list_kibd)) {
					        		array_push($kd_aset5_list_kibd, $row4->Kd_Aset5);
					        		$pdf->RowAset5(array(d2($row4->Kd_Aset1).".".d2($row4->Kd_Aset2).".".d2($row4->Kd_Aset3).".".d2($row4->Kd_Aset4).".".d2($row4->Kd_Aset5), $row4->Nm_Aset5, rp(total_arr($row4->Harga))));
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
	$total_kibe = sum_arr($kibe,'Harga');	
	$pdf->Row(array("      05","ASET TETAP LAINYA",rp($total_kibe)));

	$total_kibe_aset2_arr = array();
	$total_kibe_aset3_arr = array();
	$total_kibe_aset4_arr = array();
	foreach ($kibe->result() as $row){
		if (!isset($total_kibe_aset2_arr[$row->Kd_Aset2])) {
		   	$total_kibe_aset2_arr[$row->Kd_Aset2] = $row->Harga;
		}else{
			$total_kibe_aset2_arr[$row->Kd_Aset2] += $row->Harga;
		}

		if (!isset($total_kibe_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3])) {
   			$total_kibe_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3] = $row->Harga;
   		}else{
   			$total_kibe_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3] += $row->Harga;
   		}

   		if (!isset($total_kibe_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4])) {
   			$total_kibe_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] = $row->Harga;
   		}else{
   			$total_kibe_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] += $row->Harga;
   		}

	}
	
	$kd_aset2_list_kibe = array();
	foreach ($kibe->result() as $row){

		if(!in_array($row->Kd_Aset2, $kd_aset2_list_kibe)) {
			array_push($kd_aset2_list_kibe, $row->Kd_Aset2);

			$pdf->RowAset2(array(d2($row->Kd_Aset1).".".d2($row->Kd_Aset2), $row->Nm_Aset2, rp($total_kibe_aset2_arr[$row->Kd_Aset2])));
	        
	        $kd_aset3_list_kibe = array();	        
			foreach ($kibe->result() as $row2) {

	        	if ($row->Kd_Aset2 == $row2->Kd_Aset2 AND !in_array($row2->Kd_Aset3, $kd_aset3_list_kibe)) {
	        		array_push($kd_aset3_list_kibe, $row2->Kd_Aset3);
	        		$pdf->RowAset3(array(d2($row2->Kd_Aset1).".".d2($row2->Kd_Aset2).".".d2($row2->Kd_Aset3), $row2->Nm_Aset3,  rp($total_kibe_aset3_arr[$row2->Kd_Aset2][$row2->Kd_Aset3])));
	        		
	        		$kd_aset4_list_kibe = array(); 
	        		foreach ($kibe->result() as $row3) {
			        	if ($row2->Kd_Aset2 == $row3->Kd_Aset2 && $row2->Kd_Aset3 == $row3->Kd_Aset3 && !in_array($row3->Kd_Aset4, $kd_aset4_list_kibe)) {
			        		array_push($kd_aset4_list_kibe, $row3->Kd_Aset4);
			        		$pdf->RowAset4(array(d2($row3->Kd_Aset1).".".d2($row3->Kd_Aset2).".".d2($row3->Kd_Aset3).".".d2($row3->Kd_Aset4), $row3->Nm_Aset4, rp($total_kibe_aset4_arr[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4])));

			        		$kd_aset5_list_kibe = array(); 
			        		foreach ($kibe->result() as $row4) {
					        	if ($row3->Kd_Aset2 == $row4->Kd_Aset2 && $row3->Kd_Aset3 == $row4->Kd_Aset3 && $row3->Kd_Aset4 == $row4->Kd_Aset4 && !in_array($row4->Kd_Aset5, $kd_aset5_list_kibe)) {
					        		array_push($kd_aset5_list_kibe, $row4->Kd_Aset5);
					        		$pdf->RowAset5(array(d2($row4->Kd_Aset1).".".d2($row4->Kd_Aset2).".".d2($row4->Kd_Aset3).".".d2($row4->Kd_Aset4).".".d2($row4->Kd_Aset5), $row4->Nm_Aset5, rp(total_arr($row4->Harga))));
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
	$total_kibf = sum_arr($kibf,'Harga');	
	$pdf->Row(array("      05","KONSTRUKSI DALAM PENGERJAAN",rp($total_kibf)));

	$total_kibf_aset2_arr = array();
	$total_kibf_aset3_arr = array();
	$total_kibf_aset4_arr = array();
	foreach ($kibf->result() as $row){
		if (!isset($total_kibf_aset2_arr[$row->Kd_Aset2])) {
		   	$total_kibf_aset2_arr[$row->Kd_Aset2] = $row->Harga;
		}else{
			$total_kibf_aset2_arr[$row->Kd_Aset2] += $row->Harga;
		}

		if (!isset($total_kibf_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3])) {
   			$total_kibf_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3] = $row->Harga;
   		}else{
   			$total_kibf_aset3_arr[$row->Kd_Aset2][$row->Kd_Aset3] += $row->Harga;
   		}

   		if (!isset($total_kibf_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4])) {
   			$total_kibf_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] = $row->Harga;
   		}else{
   			$total_kibf_aset4_arr[$row->Kd_Aset2][$row->Kd_Aset3][$row->Kd_Aset4] += $row->Harga;
   		}

	}
	
	$kd_aset2_list_kibf = array();
	foreach ($kibf->result() as $row){

		if(!in_array($row->Kd_Aset2, $kd_aset2_list_kibf)) {
			array_push($kd_aset2_list_kibf, $row->Kd_Aset2);

			$pdf->RowAset2(array(d2($row->Kd_Aset1).".".d2($row->Kd_Aset2), $row->Nm_Aset2, rp($total_kibf_aset2_arr[$row->Kd_Aset2])));
	        
	        $kd_aset3_list_kibf = array();	        
			foreach ($kibf->result() as $row2) {

	        	if ($row->Kd_Aset2 == $row2->Kd_Aset2 AND !in_array($row2->Kd_Aset3, $kd_aset3_list_kibf)) {
	        		array_push($kd_aset3_list_kibf, $row2->Kd_Aset3);
	        		$pdf->RowAset3(array(d2($row2->Kd_Aset1).".".d2($row2->Kd_Aset2).".".d2($row2->Kd_Aset3), $row2->Nm_Aset3,  rp($total_kibf_aset3_arr[$row2->Kd_Aset2][$row2->Kd_Aset3])));
	        		
	        		$kd_aset4_list_kibf = array(); 
	        		foreach ($kibf->result() as $row3) {
			        	if ($row2->Kd_Aset2 == $row3->Kd_Aset2 && $row2->Kd_Aset3 == $row3->Kd_Aset3 && !in_array($row3->Kd_Aset4, $kd_aset4_list_kibf)) {
			        		array_push($kd_aset4_list_kibf, $row3->Kd_Aset4);
			        		$pdf->RowAset4(array(d2($row3->Kd_Aset1).".".d2($row3->Kd_Aset2).".".d2($row3->Kd_Aset3).".".d2($row3->Kd_Aset4), $row3->Nm_Aset4, rp($total_kibf_aset4_arr[$row3->Kd_Aset2][$row3->Kd_Aset3][$row3->Kd_Aset4])));

			        		$kd_aset5_list_kibf = array(); 
			        		foreach ($kibf->result() as $row4) {
					        	if ($row3->Kd_Aset2 == $row4->Kd_Aset2 && $row3->Kd_Aset3 == $row4->Kd_Aset3 && $row3->Kd_Aset4 == $row4->Kd_Aset4 && !in_array($row4->Kd_Aset5, $kd_aset5_list_kibf)) {
					        		array_push($kd_aset5_list_kibf, $row4->Kd_Aset5);
					        		$pdf->RowAset5(array(d2($row4->Kd_Aset1).".".d2($row4->Kd_Aset2).".".d2($row4->Kd_Aset3).".".d2($row4->Kd_Aset4).".".d2($row4->Kd_Aset5), $row4->Nm_Aset5, rp(total_arr($row4->Harga))));
					        	}
					        }

			        	}
			        }
	        		 
	        	}
	        }

		}
	}

	/* END KIB F  */

	
	$total_at = ($total_kiba + $total_kibb + $total_kibc + $total_kibd + $total_kibe + $total_kibf);

	$pdf->Akumulasi("TOTAL ASET TETAP",rp($total_at));

	/* Aset Lainya  */
	$RB_kibb = sum_arr($kibb,'RB');
	$RB_kibc = sum_arr($kibc,'RB');
	$RB_kibd = sum_arr($kibd,'RB');
	$RB_kibe = sum_arr($kibe,'RB');
	$total_RB = $RB_kibb + $RB_kibc + $RB_kibd + $RB_kibe;

	$total_lainnya = $total_RB + $kib_lainnya->NB_Akhir + $non_operasioanl->NB_Akhir; 
	$pdf->SetFont('Times','B','10');	
	$pdf->Row(array("      07","ASET LAINNYA",rp($total_lainnya)));

	$pdf->SetFont('Times','','10');
	/* KIB LAINYA - Aset lainnya  */
	$pdf->Row(array("      07.20", "      Aset Lainnya", 0));
	/* KIB LAINYA - Aset Rusak Berat */
	$pdf->Row(array("      07.20", "      Rusak Berat", rp($total_RB)));
	/* KIB LAINYA - Aset Non Operasional */
	// $NonOP_kiba = sum_arr($kiba,'NB_Non_Operasional');
	// $NonOP_kibb = sum_arr($kibb,'NB_Non_Operasional');
	// $NonOP_kibc = sum_arr($kibc,'NB_Non_Operasional');
	// $NonOP_kibd = sum_arr($kibd,'NB_Non_Operasional');
	// $NonOP_kibe = sum_arr($kibe,'NB_Non_Operasional');

	// $total_NonOP = $NonOP_kiba + $NonOP_kibb + $NonOP_kibc + $NonOP_kibd + $NonOP_kibe;
	$total_NonOP = $non_operasioanl->NB_Akhir;
	$pdf->Row(array("      07.22", "      Aset Non Operasional (Aset yang dimanfaatkan pihak lain)", rp($total_NonOP)));
	
	
	/* KIB LAINYA - Aset Renovasi  */
	$pdf->Row(array("      07.23", "      Aset yang dikerjasamakan dengan pihak ke 3", 0));
	/* KIB LAINYA - Aset tak berwujud  */
	$pdf->Row(array("      07.24", "      Aset Tak Berwujud", rp($kib_lainnya->NB_Akhir)));

	$pdf->kosong("");

	$pdf->Akumulasi("TOTAL ASET",rp($total_at+$total_lainnya));
	
	if($ttd_pengurus){
		$pdf->ttd($Nm_Pimpinan,$Nip_Pimpinan,$Jbt_Pimpinan,$Nm_Pengurus,$Nip_Pengurus,$tanggal);
	}else{
		$pdf->ttd2($Nm_Sekda,$Nip_Sekda,$Jbt_Sekda,$Nm_Ka_Keu,$Nip_Ka_Keu,$tanggal);
	}
	$pdf->Output("SIMDO_Rincian_Neraca.pdf","I");
?>