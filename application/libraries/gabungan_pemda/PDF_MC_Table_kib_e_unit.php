<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->library('fpdf');
class PDF_MC_Table_kib_e extends FPDF
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
		if ($i==14){
        	$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
		}elseif ($i==1 || $i==15){
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
		$this->Cell(19,5,'KABUPATEN/KOTA',0,0,'L');$this->Cell(35,5,' ',0,0,'L');$this->Cell(0,5,': MEDAN',0,0,'L');
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


function Rowheader()
{
        $this->SetFont('Arial','B',11);
        $x=$this->SetX(10);
        $y=$this->GetY();
		$y2=$y+2;
		$w=0;
		$h=0;
        $this->Rect($x,$y,$w,$h);
        $this->MultiCell(10,20,"No.",1,'C');
		$this->SetXY(20,$y);
		$this->MultiCell(60,20,"",1,'C');
		$this->SetXY(20,$y+5);
		$this->MultiCell(60,5,"Jenis Barang/\n Nama Barang",0,'C');
		
		$this->SetXY(80,$y);
		$this->MultiCell(40,20,"Nomor",1,'C');
		
		$this->SetXY(120,$y);
		$this->MultiCell(30,20,"",1,'C');
		$this->SetXY(120,$y+5);
		$this->MultiCell(30,5,"Nomor\nRegister",0,'C');

		$this->SetXY(150,$y);
		$this->MultiCell(50,10,"Buku / Perpustakaan",1,'C');
		$this->SetXY(150,$y2+8);
		$this->MultiCell(25,5,"Judul/\nPencipta",1,'C');
		$this->SetXY(175,$y2+8);
		$this->MultiCell(25,10,"Spesifikasi",1,'C');
		
				
		$this->SetXY(200,$y);
		$this->MultiCell(75,5,"Barang Bercorak\n Kesenian / Kebudayaan",1,'C');
		$this->SetXY(200,$y2+8);
		$this->MultiCell(25,5,"Asal/\n Daerah",1,'C');
		$this->SetXY(225,$y2+8);
		$this->MultiCell(25,10,"Pencipta",1,'C');
		$this->SetXY(250,$y2+8);
		$this->MultiCell(25,10,"Bahan",1,'C');
		
		$this->SetXY(275,$y);
		$this->MultiCell(50,5,"Hewan/Ternak\ndan Tumbuhan",1,'C');
		$this->SetXY(275,$y2+8);
		$this->MultiCell(25,10,"Jenis",1,'C');
		$this->SetXY(300,$y2+8);
		$this->MultiCell(25,10,"Ukuran",1,'C');
		
		$this->SetXY(325,$y);
		$this->MultiCell(18,20,"Jumlah",1,'C');
		
		$this->SetXY(343,$y);
		$this->MultiCell(20,20,"Asal Usul",1,'C');
		
		$this->SetXY(363,$y);
		$this->MultiCell(22,20,"",1,'C');
		$this->SetXY(363,$y+3);
		$this->MultiCell(22,5,"Tahun Cetak / Pembelian",0,'C');
		
		$this->SetXY(385,$y);
		$this->MultiCell(35,20,"",1,'C');
		$this->SetXY(385,$y+5);
		$this->MultiCell(35,5,"Harga \n (ribuan Rp)",0,'C');
		
		$this->SetXY(420,$y);
		$this->MultiCell(30,20,"",1,'C');
		$this->SetXY(420,$y+8);
		$this->MultiCell(30,5,"Keterangan",0,'C');
		$this->Ln(7.5);
		
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

function nihil()
{
	$this->SetFont('times','',50);
	$this->MultiCell(440,60,"N   I   H   I   L",1,'C');
}


function Header()
{
	if( $this->PageNo() != 1){
	$this->SetFont('Arial','B',15);
    $this->Cell(0,9,'KARTU INVENTARIS BARANG (KIB) E. ASET TETAP LAINYA ',0,0,'C');
	$this->Ln();
	$this->SetFont('Arial','B','10');
	$this->SetWidths(array(10,60,40,30,25,25,25,25,25,25,25,18,20,22,35,30));
	$this->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14", "15", "16"));
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