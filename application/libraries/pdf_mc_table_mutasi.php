<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->library('fpdf');
class PDF_MC_Table_mutasi extends FPDF
{

var $widths;
var $aligns;

function SetWidths($w)
{
    $this->widths=$w;
}

function SetAligns($a)
{
    $this->aligns=$a;
}

function Row($data)
{
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    $this->CheckPageBreak($h);
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
		if ($i==13 || $i==15  || $i==17 || $i==19){
        	$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
		}elseif ($i==3 || $i==10 || $i==13  || $i==20){
        	$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		}else{
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
		}
        $x=$this->GetX();
        $y=$this->GetY();
        $this->Rect($x,$y,$w,$h);
        $this->MultiCell($w,5,$data[$i],0,$a);
        $this->SetXY($x+$w,$y);
    }
    $this->Ln($h);
}

function RowJudul($data)
{
	
    $this->SetFillColor(230,230,200);
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    $this->CheckPageBreak($h);
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
        $x=$this->GetX();
        $y=$this->GetY();
        $this->Rect($x,$y,$w,$h);
        $this->MultiCell($w,5,$data[$i],0,$a,true);
        $this->SetXY($x+$w,$y);
    }
    $this->Ln($h);
}


function UPBTitle($bidang,$unit,$sub_unit,$upb,$kode_lokasi)
{
        $this->SetFont('Arial','B',11);
        $x=$this->SetX(10);
        $y=$this->GetY();
		$y2=42;
		$w=0;
		$h=0;
        $this->Rect($x,$y,$w,$h);
		
			
		$this->Cell(19,5,'PROVINSI',0,0,'L');$this->Cell(35,5,' ',0,0,'L');
		$this->Cell(0,5,': SUMATERA UTARA',0,0,'L');
		$this->Ln();
		$this->Cell(19,5,'KABUPATEN',0,0,'L');$this->Cell(35,5,' ',0,0,'L');$this->Cell(0,5,': '.NM_PEMDA,0,0,'L');
		$this->Ln();
		$this->Cell(19,5,'BIDANG',0,0,'L');$this->Cell(35,5,' ',0,0,'L');$this->Cell(0,5,': '.$bidang,0,0,'L');
		$this->Ln();
		$this->Cell(19,5,'UNIT ORGANISASI ',0,0,'L');$this->Cell(35,5,' ',0,0,'L');$this->Cell(0,5,': '.$unit,0,0,'L');
		$this->Ln();
		$this->Cell(19,5,'SUB UNIT ORGANISASI ',0,0,'L');$this->Cell(35,5,' ',0,0,'L');$this->Cell(0,5,': '.$sub_unit,0,0,'L');
		$this->Ln();
		
		if (!empty($upb)){
		$this->Cell(19,5,'UPB ',0,0,'L');$this->Cell(35,5,' ',0,0,'L');$this->Cell(0,5,': '.$upb,0,0,'L');
		$this->Ln();
		}
		
		$this->Ln();
		$this->SetFont('Arial','',10);
		$this->Cell(19,5,'NO. KODE LOKASI ',0,0,'L');$this->Cell(35,5,'',0,0,'L');$this->Cell(0,5,': '.$kode_lokasi,0,0,'L');
}

function UPBTitle2($kode_lokasi)
{
        $this->SetFont('Arial','B',11);
        $x=$this->SetX(10);
        $y=$this->GetY();
		$y2=42;
		$w=0;
		$h=0;
        $this->Rect($x,$y,$w,$h);
		
		$this->Cell(19,5,'PROVINSI',0,0,'L');
		$this->Cell(35,5,' ',0,0,'L'); 
		$this->Cell(0,5,': SUMATERA UTARA',0,0,'L');  
		$this->Ln();  $this->Cell(19,5,'KABUPATEN/KOTA',0,0,'L');
		$this->Cell(35,5,' ',0,0,'L');$this->Cell(0,5,': '.NM_PEMDA,0,0,'L');  
		$this->Cell(35,5,' ',0,0,'L');
		$this->Cell(35,5,' ',0,0,'L');
		$this->Ln();    
		  
		$this->Ln();  
		$this->SetFont('Arial','',10);  
		$this->Cell(19,5,'NO. KODE LOKASI ',0,0,'L');$this->Cell(35,5,'',0,0,'L');
		$this->Cell(0,5,': '.$kode_lokasi,0,0,'L'); 
}


function Rowheader()
{
        $this->SetFont('Arial','B',9);
        $x=$this->SetX(10);
        $y=$this->GetY();
		$y2=$y+2;
		$w=0;
		$h=0;
        $this->Rect($x,$y,$w,$h);
        $this->MultiCell(50,5,"NOMOR",1,'C');
		
		$this->SetXY(10,$y2+3);
		$this->MultiCell(10,15,"",1,'C');
		$this->SetXY(10,$y2+5);
		$this->MultiCell(10,5,"No. Urut",0,'C');
		
		$this->SetXY(20,$y2+3);
		$this->MultiCell(25,15,"Kode Barang",1,'C');
		$this->SetXY(45,$y2+3);
		$this->MultiCell(15,15,"Register",1,'C');
		
		$this->SetXY(60,$y);
		$this->MultiCell(110,5,"SPESIFIKASI BARANG",1,'C');
		$this->SetXY(60,$y2+3);
		$this->MultiCell(50,15,"Nama/Jenis Barang",1,'C');
		$this->SetXY(110,$y2+3);
		$this->MultiCell(20,15,"Merk/Type",1,'C');
		
		$this->SetXY(130,$y2+3);
		$this->MultiCell(20,15,"",1,'C');
		$this->SetXY(130,$y2+5);
		$this->MultiCell(20,3,"No. Sertifikat/Pabrik/Chasis/Mesin",0,'C');
		
		$this->SetXY(150,$y2+3);
		$this->MultiCell(20,15,"Bahan",1,'C');
		$this->SetXY(170,$y);
		$this->MultiCell(20,20,"",1,'C');
		$this->SetXY(170,$y+5);
		$this->MultiCell(20,4,"Asal/Cara\n Perolehan",0,'C');
		
		$this->SetXY(190,$y);
		$this->MultiCell(20,20,"",1,'C');
		$this->SetXY(190,$y+5);
		$this->MultiCell(20,4,"Tahun\nPerolehan",0,'C');
		
		$this->SetXY(210,$y);
		$this->MultiCell(20,20,"",1,'C');
		$this->SetXY(210,$y+2);
		$this->MultiCell(20,4,"Ukuran Barang/ Konstruksi\n(P,SP,D)",0,'C');
		
		$this->SetXY(230,$y);
		$this->MultiCell(20,20,"Satuan",1,'C');
		
		$this->SetXY(250,$y);
		$this->MultiCell(15,20,"",1,'C');
		$this->SetXY(250,$y+4);
		$this->MultiCell(15,4,"Kondisi\n(B, KB, RB)",0,'C');
		
		
		$this->SetXY(265,$y);
		$this->MultiCell(35,5,"JUMLAH AWAL",1,'C');
		$this->SetXY(265,$y2+3);
		$this->MultiCell(15,15,"Barang",1,'C');
		$this->SetXY(280,$y2+3);
		$this->MultiCell(20,15,"Harga",1,'C');
		
		$this->SetXY(300,$y);
		$this->MultiCell(80,5,"MUTASI/PERUBAHAN",1,'C');
		$this->SetXY(300,$y2+3);
		$this->MultiCell(35,5,"Berkurang",1,'C');
		$this->SetXY(300,$y2+8);
		$this->MultiCell(15,10,"Barang",1,'C');
		$this->SetXY(315,$y2+8);
		$this->MultiCell(20,10,"Harga",1,'C');
		
		$this->SetXY(335,$y2+3);
		$this->MultiCell(45,5,"Bertambah",1,'C');
		$this->SetXY(335,$y2+8);
		$this->MultiCell(15,10,"Barang",1,'C');
		$this->SetXY(350,$y2+8);
		$this->MultiCell(30,10,"Harga",1,'C');	
		
		$this->SetXY(380,$y);
		$this->MultiCell(50,5,"JUMLAH AKHIR",1,'C');
		$this->SetXY(380,$y2+3);
		$this->MultiCell(15,15,"Barang",1,'C');
		$this->SetXY(395,$y2+3);
		$this->MultiCell(35,15,"Harga",1,'C');
		
		$this->SetXY(430,$y);
		$this->MultiCell(20,20,"Ket.",1,'C');
}


function ttd($nm_pimpinan,$nip_pimpinan,$jbt_pimpinan,$nm_pengurus,$nip_pengurus,$tanggal)
{
	
	$r1  = 10;
	$y1  = $this->h - 72;
	$y=$this->GetY();
	if($this->GetY() >= 230)
        $this->AddPage($this->CurOrientation);

	$this->SetFont("Arial", "", 11);
	$this->SetXY($r1, $y1 );
	$this->Cell(90,5,'Mengetahui',0,'LR', 'C');
	$this->SetXY($r1+350,$y1);
	$this->Cell(90,5,$tanggal,0,'LR', 'C');
	$this->Ln();
	$this->MultiCell(90,5,strtoupper($jbt_pimpinan),0,'C');
	$this->SetXY($r1+350,$y1+5);
	$this->MultiCell(90,5,"PENGURUS BARANG",0,'C');
	
	$this->Ln(30);
	$this->SetFont('Arial','UB',11);
	$this->Cell(90,5,$nm_pimpinan,0,'B', 'C');
	$this->Cell(615,5,$nm_pengurus,0,'B', 'C');
	
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(90,5,'NIP .'.$nip_pimpinan,0,'B', 'C');
	$this->Cell(615,5,'NIP .'.$nip_pengurus,0,'B', 'C');

}

function ttd2($nm_pimpinan,$nip_pimpinan,$jbt_pimpinan,$nm_pengurus,$nip_pengurus,$tanggal)
{
	
	$r1  = 10;
	$y1  = $this->h - 72;
	$y=$this->GetY();
	if($this->GetY() >= 230)
        $this->AddPage($this->CurOrientation);

	$this->SetFont("Arial", "", 11);
	$this->SetXY($r1, $y1 );
	$this->Cell(90,5,'Mengetahui',0,'LR', 'C');
	$this->SetXY($r1+350,$y1);
	$this->Cell(90,5,$tanggal,0,'LR', 'C');
	$this->Ln();
	$this->MultiCell(90,5,STATUS_KEPALA_DAERAH." \n ".STATUS_SEKDA,0,'C');
	$this->SetXY($r1+350,$y1+5);
	$this->MultiCell(90,5,STATUS_KEPALA_DINAS,0,'C');
	
	$this->Ln(25);
	$this->SetFont('Arial','UB',11);
	$this->Cell(90,5,NAMA_SEKDA,0,'B', 'C');  
	$this->Cell(615,5,NAMA_KEPALA_DINAS,0,'B', 'C'); 
	
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(90,5,NIP_SEKDA,0,'B', 'C');
	$this->Cell(615,5,NIP_KEPALA_DINAS,0,'B', 'C');

}

function nihil()
{
	$this->SetFont('times','',50);
	$this->MultiCell(440,60,"N   I   H   I   L",1,'C');
}

function nihil2()
{
	$this->SetFont('times','',20);
	$this->MultiCell(440,10,"N   I   H   I   L",1,'C');
}


function Header()
{
    
	if( $this->PageNo() != 1){
	$this->SetFont('Arial','B',15);
    $this->Cell(0,9,'LAPORAN MUTASI BARANG ',0,0,'C');
	$this->Ln();
	$this->SetFont('Arial','B','10');
	$this->SetWidths(array(10,25,15,50,20,20,20,20,20,20,20,15,15,20,15,20,15,30,15,35,20));
	$this->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14", "15", "16", "17", "18", "19", "20", "21"));

	}
}

function Footer()
{
    $this->SetY(-15);
    $this->SetFont('Arial','I',8);
    $this->SetTextColor(128);
    $_this = & get_Instance();
	    $this->MultiCell(0,4,'Log '.$_this->session->userdata('tahun_anggaran').' By '.$_this->session->userdata('username'),0,'L');
	    $this->MultiCell(0,4,'Printed by SIMBADA V.02 Akrual - '.date("d/m/Y").' | Page '.$this->PageNo(),0,'R');
}

function CheckPageBreak($h)
{
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
}
?>