<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  
$this->load->library('fpdf'); 
class pdf_mc_table_unit extends FPDF {  
var $widths; var $aligns;  

function SetWidths($w) {  $this->widths=$w; }  function SetAligns($a) {  $this->aligns=$a; }  function Row($data) {  $nb=0;  for($i=0;$i<count($data);$i++)  $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));  $h=5*$nb;  $this->CheckPageBreak($h);  for($i=0;$i<count($data);$i++)  {  $w=$this->widths[$i];  if ($i==14){  $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';  }elseif ($i==2 || $i==10 || $i==13 || $i==15){  $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';  }else{  $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';  }  $x=$this->GetX();  $y=$this->GetY();  $this->Rect($x,$y,$w,$h);  $this->MultiCell($w,5,$data[$i],0,$a);  $this->SetXY($x+$w,$y);  }  $this->Ln($h); } 

function RowJudul($data) {    $this->SetFillColor(230,230,200);  $nb=0;  for($i=0;$i<count($data);$i++)  $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));  $h=5*$nb;  $this->CheckPageBreak($h);  for($i=0;$i<count($data);$i++)  {  $w=$this->widths[$i];  $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';  $x=$this->GetX();  $y=$this->GetY();  $this->Rect($x,$y,$w,$h);  $this->MultiCell($w,5,$data[$i],0,$a,true);  $this->SetXY($x+$w,$y);  }  $this->Ln($h); }   

function UPBTitle($bidang,$unit,$sub_unit,$upb,$kode_lokasi)
{
	$this->SetFont('Arial','B',11);  $x=$this->SetX(20);
	$y=$this->GetY();  $y2=42;  $w=0;  $h=0;  
	$this->Rect($x,$y,$w,$h);      
	$this->Cell(19,5,'PROVINSI',0,0,'L');
	$this->Cell(35,5,' ',0,0,'L'); 
	$this->Cell(0,5,': SUMATERA UTARA',0,0,'L');  
	$this->Ln();  $this->Cell(19,5,'KABUPATEN/KOTA',0,0,'L');
	$this->Cell(35,5,' ',0,0,'L');$this->Cell(0,5,': '.NM_PEMDA,0,0,'L');  
	$this->Cell(35,5,' ',0,0,'L');
	$this->Cell(35,5,' ',0,0,'L');
	$this->Cell(0,5,': '.$sub_unit,0,0,'L');  
	$this->Ln();    
	  
	
	$this->Ln();  
	$this->SetFont('Arial','',10);  
	$this->Cell(19,5,'NO. KODE LOKASI ',0,0,'L');$this->Cell(35,5,'',0,0,'L');$this->Cell(0,5,': '.$kode_lokasi,0,0,'L'); 
}    


function Rowheader() {  $this->SetFont('Arial','B',11);  $x=$this->SetX(20);  $y=$this->GetY();  $y2=$y+2;  $w=0;  $h=0;  $this->Rect($x,$y,$w,$h);  $this->MultiCell(10,20,"No.",1,'C');  $this->SetXY(30,$y);  $this->MultiCell(30,20,"Kode Barang",1,'C');  $this->SetXY(60,$y);  $this->MultiCell(40,20,"",1,'C');  $this->SetXY(50,$y+5);  $this->MultiCell(53,5,"Jenis Barang/\n Nama Barang",0,'C');  $this->SetXY(100,$y);  

$this->MultiCell(30,20,"",1,'C');  $this->SetXY(100,$y+5);  $this->MultiCell(30,5,"Nomor Register",0,'C');

$this->SetXY(130,$y);  $this->MultiCell(30,20,"",1,'C');  $this->SetXY(130,$y+5);  $this->MultiCell(30,5,"Merk /\nType",0,'C');  
$this->SetXY(160,$y);  $this->MultiCell(20,20,"",1,'C');  $this->SetXY(160,$y+5);  $this->MultiCell(20,5,"Ukuran /\nCC",0,'C');    
$this->SetXY(180,$y);  $this->MultiCell(25,20,"Bahan",1,'C');   
 $this->SetXY(205,$y+5);  $this->MultiCell(25,5,"Tahun Pembelian",0,'C');  
$this->SetXY(205,$y);  $this->MultiCell(25,20,"",1,'C');    $this->SetXY(230,$y);  $this->MultiCell(100,10,"Nomor",1,'C');    
$this->SetXY(230,$y2+8);  $this->MultiCell(20,10,"Pabrik",1,'C');  $this->SetXY(250,$y2+8);  $this->MultiCell(20,10,"Rangka",1,'C');  
$this->SetXY(270,$y2+8);  $this->MultiCell(20,10,"Mesin",1,'C');  $this->SetXY(290,$y2+8);  $this->MultiCell(20,10,"Polisi",1,'C');  
$this->SetXY(310,$y2+8);  $this->MultiCell(20,10,"BPKB",1,'C');      $this->SetXY(330,$y+8);  $this->MultiCell(30,5,"Asal Usul",0,'C');  
$this->SetXY(330,$y);  $this->MultiCell(30,20,"",1,'C');    $this->SetXY(360,$y+5);  $this->MultiCell(35,5,"Harga \n(ribuan Rp)",0,'C');  
$this->SetXY(360,$y);  $this->MultiCell(35,20,"",1,'C');    $this->SetXY(395,$y);  $this->MultiCell(40,20,"Keterangan",1,'C'); }   


function ttd($nm_pimpinan,$nip_pimpinan,$jbt_pimpinan,$nm_pengurus,$nip_pengurus,$tanggal) {    $r1 = 10;  $y1 = $this->h - 72;  $y=$this->GetY();  if($this->GetY() >= 230)  $this->AddPage($this->CurOrientation);   $this->SetFont("Arial", "", 11);  $this->SetXY($r1, $y1 );  
$this->Cell(105,5,'Mengetahui',0,'LR', 'C');  
$this->SetXY($r1+350,$y1);  
$this->Cell(98,5,$tanggal,0,'LR', 'C');  
$this->Ln();  
$this->MultiCell(90,5,'a.n. GUBERNUR SUMATERA UTARA                     Plt. SEKRETARIS DAERAH KABUPATEN',0,'C');  
$this->SetXY($r1+350,$y1+5);  $this->MultiCell(90,5,"KEPALA DINAS PENGELOLAAN KEUANGAN DAN ASET DAERAH",0,'C');    
$this->Ln(25);  
$this->SetFont('Arial','UB',11);  
$this->Cell(90,5,NAMA_SEKDA,0,'B', 'C');  
$this->Cell(600,5,'MURNADY PASARIBU, S.Sos',0,'B', 'C');    
$this->Ln();  $this->SetFont('Arial','',11);  
$this->Cell(90,5,'NIP. '.'195911091986021002',0,'B', 'C');  $this->Cell(600,5,'NIP .'.'197409141993031005',0,'B', 'C');  
} 

function nihil() {  $this->SetFont('times','',50);  $this->MultiCell(415,60,"N I H I L",1,'C'); }   function Header() {  if( $this->PageNo() != 1){  $this->SetFont('Arial','B',15);  $this->Cell(0,9,'KARTU INVENTARIS BARANG (KIB) B ',0,0,'C');  $this->Ln();  $this->SetFont('Arial','B','10');  $this->SetWidths(array(10,30,40,30,30,20,25,25,20,20,20,20,20,30,35,40));  $this->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14", "15", "16"));   } }   function Footer() {  $this->SetY(-15);  $this->SetFont('Arial','I',8);  $this->SetTextColor(128);  $this->MultiCell(0,4,'Halaman '.$this->PageNo(),0,'R'); }  function CheckPageBreak($h) {  if($this->GetY()+$h>$this->PageBreakTrigger)  $this->AddPage($this->CurOrientation); }  function NbLines($w,$txt) {  $cw=&$this->CurrentFont['cw'];  if($w==0)  $w=$this->w-$this->rMargin-$this->x;  $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;  $s=str_replace("\r",'',$txt);  $nb=strlen($s);  if($nb>0 and $s[$nb-1]=="\n")  $nb--;  $sep=-1;  $i=0;  $j=0;  $l=0;  $nl=1;  while($i<$nb)  {  $c=$s[$i];  if($c=="\n")  {  $i++;  $sep=-1;  $j=$i;  $l=0;  $nl++;  continue;  }  if($c==' ')  $sep=$i;  $l+=$cw[$c];  if($l>$wmax)  {  if($sep==-1)  {  if($i==$j)  $i++;  }  else  $i=$sep+1;  $sep=-1;  $j=$i;  $l=0;  $nl++;  }  else  $i++;  }  return $nl; } } ?> 