<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->library('fpdf');
class PDF_MC_rincian_barang extends FPDF
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
		if ($i==2 ){
        	$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
		}elseif ($i==3 || $i==10  || $i==14){
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


function RowAset2($data)
{
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));

    $h=5*$nb;
    $this->CheckPageBreak($h);
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        $x=$this->GetX();
        $y=$this->GetY();
        $this->Rect($x,$y,$w,$h,'F');
        
        if ($i==1 || $i==0){
			$this->SetXY($x+5,$y);
		}

		$this->SetFont('Times','B',10);

        $this->MultiCell($w,5,$data[$i],0,$a);
        $this->SetXY($x+$w,$y);
    }
    $this->Ln($h);
}

function RowAset3($data)
{
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));

    $h=5*$nb;
    $this->CheckPageBreak($h);
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        $x=$this->GetX();
        $y=$this->GetY();
        $this->Rect($x,$y,$w,$h);
        
        if ($i==0){
			$this->SetXY($x+5,$y);
		}

        if ($i==1){
			$this->SetXY($x+10,$y);
		}

		$this->SetFont('Times','B',10);

        $this->MultiCell($w,5,$data[$i],0,$a);
        $this->SetXY($x+$w,$y);
    }
    $this->Ln($h);
}

function RowAset4($data)
{
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));

    $h=5*$nb;
    $this->CheckPageBreak($h);
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        $x=$this->GetX();
        $y=$this->GetY();
        $this->Rect($x,$y,$w,$h);
        
        if ($i==0){
			$this->SetXY($x+5,$y);
		}

        if ($i==1 ){
			$this->SetXY($x+15,$y);
		}

		if ($i==2 || $i==3 || $i==4 || $i==5 || $i==6){
			$this->SetFont('Times','B',10);
		}else{
			$this->SetFont('Times','',10);
		}

        $this->MultiCell($w,5,$data[$i],0,$a);
        $this->SetXY($x+$w,$y);
    }
    $this->Ln($h);
}

function RowAset5($data)
{
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));

    $h=5*$nb;
    $this->CheckPageBreak($h);
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        $x=$this->GetX();
        $y=$this->GetY();
        $this->Rect($x,$y,$w,$h);
        
        if ($i==0){
			$this->SetXY($x+5,$y);
		}

        if ($i==1 ){
			$this->SetXY($x+20,$y);
		}

		$this->SetFont('Times','',10);

        $this->MultiCell($w,5,$data[$i],0,$a);
        $this->SetXY($x+$w,$y);
    }
    $this->Ln($h);
}

function Akumulasi($text,$b,$kb,$rb,$jml,$total)
{
	$this->SetFont('Times','B',10);
	$y=$this->GetY();
	$this->MultiCell(170,8,$text,1,'L');
	$this->SetXY(180,$y);
    $this->MultiCell(15,8,$b,1,'C');
    $this->SetXY(195,$y);
    $this->MultiCell(15,8,$kb,1,'C');
    $this->SetXY(210,$y);
    $this->MultiCell(15,8,$rb,1,'C');
    $this->SetXY(225,$y);
    $this->MultiCell(20,8,$jml,1,'C');
    $this->SetXY(245,$y);
	$this->MultiCell(45,8,$total,1,'R');
}

function kosong()
{
	$y=$this->GetY();
	$this->MultiCell(283,8,"",1,'L');
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
        $b=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
        $this->MultiCell($w,5,$data[$i],0,$a,true);
        $this->SetXY($x+$w,$y);
    }
    $this->Ln($h);
}


function UPBTitle($bidang,$unit,$sub_unit,$upb='',$kode_lokasi)
{
        $this->SetFont('Arial','B',11);
        $x=$this->SetX(10);
        $y=$this->GetY();
        $y2=42;
        $w=0;
        $h=0;
        $this->Rect($x,$y,$w,$h);
        
            
        $this->Cell(19,5,'PROVINSI',0,0,'L');$this->Cell(35,5,'',0,0,'L');$this->Cell(0,5,': SUMATERA UTARA',0,0,'L');
        $this->Ln();
        $this->Cell(19,5,'KABUPATEN',0,0,'L');$this->Cell(35,5,'',0,0,'L');$this->Cell(0,5,': '.NM_PEMDA,0,0,'L');
        $this->Ln();
        $this->Cell(19,5,'BIDANG',0,0,'L');$this->Cell(35,5,'',0,0,'L');$this->Cell(0,5,": ".$bidang,0,0,'L');
        $this->Ln();
        $this->Cell(19,5,'UNIT ORGANISASI ',0,0,'L');$this->Cell(35,5,'',0,0,'L');$this->Cell(0,5,": ".$unit,0,0,'L');
        $this->Ln();
        $this->Cell(19,5,'SUB UNIT ORGANISASI ',0,0,'L');$this->Cell(35,5,'',0,0,'L');$this->Cell(0,5,": ".$sub_unit,0,0,'L');
        
        $this->Ln();
        if($upb){
            $this->Cell(19,5,'UPB ',0,0,'L');$this->Cell(35,5,'',0,0,'L');$this->Cell(190,5,": ".$upb,0,0,'L');
        }else{
            $this->Cell(19,5,'',0,0,'L');$this->Cell(35,5,'',0,0,'L');$this->Cell(190,5,"",0,0,'L');
        }
        $this->SetFont('Arial','B',10);
        $this->Cell(5,5,'NO. KODE LOKASI     ',0,0,'R');
        $this->SetFont('Arial','',10);
        $this->Cell(0,5,": ".$kode_lokasi,0,0,'R');
        $this->Ln(5);

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
        $this->SetFont('Arial','B',11);
        $x=$this->SetX(10);
        $y=$this->GetY();
		$y2=$y+2;
		$w=0;
		$h=0;
        $this->Rect($x,$y,$w,$h);

		$this->SetXY(10,$y);
		$this->MultiCell(50,10,"Kode Barang",1,'C');

		$this->SetXY(60,$y);
		$this->MultiCell(120,10,"Nama Barang",1,'C');

        $this->SetXY(180,$y);
        $this->MultiCell(45,5,"Kondisi",1,'C');

        $this->SetXY(180,$y+5);
        $this->MultiCell(15,5,"B",1,'C');

        $this->SetXY(195,$y+5);
        $this->MultiCell(15,5,"KB",1,'C');

        $this->SetXY(210,$y+5);
        $this->MultiCell(15,5,"RB",1,'C');

        $this->SetXY(225,$y);
        $this->MultiCell(20,10,"Jumlah",1,'C');
        
		$this->SetXY(245,$y);
		$this->MultiCell(45,10,"",1,'C');
		$this->SetXY(245,$y);
		$this->MultiCell(45,5,"Total\n(Rupiah)",0,'C');

}


function ttd($nm_pimpinan,$nip_pimpinan,$jbt_pimpinan,$nm_pengurus,$nip_pengurus,$tanggal)
{
	
	$r1  = 10;
	$y1  = $this->h - 72;
	$y=$this->GetY();
    if($y >= 230)
        $this->AddPage($this->CurOrientation);

	$this->SetFont("Arial", "", 11);
	$this->SetXY($r1, $y1 );
	$this->Cell(90,5,'Mengetahui',0,'LR', 'C');
	$this->SetXY($r1+190,$y1);
	$this->Cell(90,5,$tanggal,0,'LR', 'C');
	$this->Ln();
	$this->MultiCell(90,5,strtoupper($jbt_pimpinan),0,'C');
	$this->SetXY($r1+190,$y1+5);
	$this->MultiCell(90,5,"PENGURUS BARANG",0,'C');
	
	$this->Ln(30);
	$this->SetFont('Arial','UB',11);
	$this->Cell(90,5,$nm_pimpinan,0,'B', 'C');
	$this->SetXY($r1+190,$y1+40);
	$this->Cell(90,5,$nm_pengurus,0,'B', 'C');
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(90,5,'NIP .'.$nip_pimpinan,0,'B', 'C');
	$this->SetXY($r1+190,$y1+45);
	$this->Cell(90,5,'NIP .'.$nip_pengurus,0,'B', 'C');

}

function ttd2($nm_pimpinan,$nip_pimpinan,$jbt_pimpinan,$nm_pengurus,$nip_pengurus,$tanggal)
{
	
	$r1  = 10;
	$y1  = $this->h - 72;
	$y=$this->GetY();
     if($y >= 230)
        $this->AddPage($this->CurOrientation);

	$this->SetFont("Arial", "", 11);
	$this->SetXY($r1, $y1 );
	$this->Cell(90,5,'Mengetahui',0,'LR', 'C');
	$this->SetXY($r1+190,$y1);
	$this->Cell(90,5,$tanggal,0,'LR', 'C');
	$this->Ln();
	$this->MultiCell(90,5,STATUS_KEPALA_DAERAH." \n ".STATUS_SEKDA,0,'C');
	$this->SetXY($r1+190,$y1+5);
	$this->MultiCell(90,5,STATUS_KEPALA_DINAS,0,'C');
	
	$this->Ln(25);
	$this->SetFont('Arial','UB',11);
	$this->Cell(90,5,NAMA_SEKDA,0,'B', 'C');
	$this->SetXY($r1+190,$y1+40);
	$this->Cell(90,5,NAMA_KEPALA_DINAS,0,'B', 'C');
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(90,5,NIP_SEKDA,0,'B', 'C');
	$this->SetXY($r1+210,$y1+45);
	$this->Cell(90,5,NIP_KEPALA_DINAS, 'C');

}

function nihil()
{
	$this->SetFont('times','',50);
	$this->MultiCell(280,60,"N   I   H   I   L",1,'C');
}

function nihil2()
{
	$this->SetFont('times','',20);
	$this->MultiCell(280,10,"N   I   H   I   L",1,'C');
}


function Header()
{
    
	if( $this->PageNo() != 1){
	$this->SetFont('Arial','B',15);
    $this->Cell(0,9,'RINCIAN BARANG',0,0,'C');
	$this->Ln();
	$this->SetFont('Arial','B','10');
	$this->SetWidths(array(50,120,15,15,15,20,45));
    $this->SetAligns(array('C','C','C','C','C','C','C'));
    $this->RowJudul(array("1", "2", "3", "4", "5", "6", "7"));
    $this->SetAligns(array('L','L','C','C','C','C','R'));

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