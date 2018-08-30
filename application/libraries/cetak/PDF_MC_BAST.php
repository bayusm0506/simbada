<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->library('fpdf');
class PDF_MC_BAST extends FPDF
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
		if ($i==9){
        	$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'R';
		}elseif ($i==3 || $i==10){
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

function Rowheader(){
        $this->SetFont('Arial','B',11);
        $x=$this->SetX(10);
        $y=$this->GetY();
		$y2=$y+2;
		$w=0;
		$h=0;
        $this->Rect($x,$y,$w,$h);
        $this->MultiCell(65,5,"NOMOR",1,'C');
		
		$this->SetXY(10,$y2+3);
		$this->MultiCell(15,15,"",1,'C');
		$this->SetXY(10,$y2+5);
		$this->MultiCell(15,5,"No. Urut",0,'C');
		
		$this->SetXY(25,$y2+3);
		$this->MultiCell(30,15,"Kode Barang",1,'C');
		$this->SetXY(55,$y2+3);
		$this->MultiCell(20,15,"Register",1,'C');
		
		$this->SetXY(75,$y);
		$this->MultiCell(95,5,"SPESIFIKASI BARANG",1,'C');
		$this->SetXY(75,$y2+3);
		$this->MultiCell(45,15,"Nama/Jenis Barang",1,'C');
		$this->SetXY(120,$y2+3);
		$this->MultiCell(25,15,"Merk/Type",1,'C');
		
		$this->SetXY(145,$y2+3);
		$this->MultiCell(25,15,"",1,'C');
		$this->SetXY(145,$y2+3);
		$this->MultiCell(25,3,"No. Sertifikat\nPabrik\nChasis\nMesin",0,'C');
		
		$this->SetXY(170,$y);
		$this->MultiCell(20,20,"Kondisi",1,'C');
		
		$this->SetXY(190,$y);
		$this->MultiCell(25,20,"",1,'C');
		$this->SetXY(190,$y+5);
		$this->MultiCell(25,4,"Tahun\nPerolehan",0,'C');

		$this->SetXY(215,$y);
		$this->MultiCell(50,5,"JUMLAH",1,'C');
		$this->SetXY(215,$y2+3);
		$this->MultiCell(17,15,"Barang",1,'C');
		$this->SetXY(232,$y2+3);
		$this->MultiCell(33,15,"Harga",1,'C');
		
		$this->SetXY(265,$y);
		$this->MultiCell(25,20,"Ket.",1,'C');
}

function Identitas1($nama,$nip,$jabatan,$alamat)
{
        $this->SetFont('Arial','',12);
        $x=$this->SetX(10);
        $y=$this->GetY();

        $this->MultiCell(10,5,"I. ",0,'L');
		$this->SetXY(20,$y);
		$this->MultiCell(40,5,"Nama",0,'L');
		$this->SetXY(60,$y);
		$this->MultiCell(230,5,": ".$nama,0,'L');
		$this->SetXY(20,$y+5);
		$this->MultiCell(40,5,"NIP",0,'L');
		$this->SetXY(60,$y+5);
		$this->MultiCell(230,5,": ".$nip,0,'L');
		$this->SetXY(20,$y+10);
		$this->MultiCell(40,5,"Jabatan",0,'L');
		$this->SetXY(60,$y+10);
		$this->MultiCell(230,5,": ".$jabatan,0,'L');
		$this->SetXY(20,$y+15);
		$this->MultiCell(40,5,"Alamat",0,'L');
		$this->SetXY(60,$y+15);
		$this->MultiCell(230,5,": ".$alamat,0,'L');
		$this->SetXY(60,$y+20);
		$this->MultiCell(230,5,"  Bertindak dan untuk atas nama Pemerintah Kabupaten PROVINSI SUMATERA UTARA",0,'L');
		$this->SetXY(60,$y+25);
		$this->MultiCell(230,5,"  Selanjutnya disebut PIHAK PERTAMA",0,'L');
		
}

function Identitas2($nama,$nip,$jabatan,$alamat)
{
        $this->SetFont('Arial','',12);
        $x=$this->SetX(10);
        $y=$this->GetY();

        $this->MultiCell(10,5,"II. ",0,'L');
		$this->SetXY(20,$y);
		$this->MultiCell(40,5,"Nama",0,'L');
		$this->SetXY(60,$y);
		$this->MultiCell(230,5,": ".$nama,0,'L');
		$this->SetXY(20,$y+5);
		$this->MultiCell(40,5,"NIP",0,'L');
		$this->SetXY(60,$y+5);
		$this->MultiCell(230,5,": ".$nip,0,'L');
		$this->SetXY(20,$y+10);
		$this->MultiCell(40,5,"Jabatan",0,'L');
		$this->SetXY(60,$y+10);
		$this->MultiCell(230,5,": ".$jabatan,0,'L');
		$this->SetXY(20,$y+15);
		$this->MultiCell(40,5,"Alamat",0,'L');
		$this->SetXY(60,$y+15);
		$this->MultiCell(230,5,": ".$alamat,0,'L');
		$this->SetXY(60,$y+20);
		$this->MultiCell(230,5,"  Selanjutnya disebut PIHAK KEDUA",0,'L');
		
}

function Ketentuan()
{
        $this->SetFont('Arial','',12);
        $x=$this->SetX(10);
        $y=$this->GetY();

        $this->MultiCell(280,5,"dengan ketentuan sebagai berikut :",0,'L');
        $this->Ln();
        $this->MultiCell(10,5,"1. ",0,'L');
		$this->SetXY(20,$y+10);
		$this->MultiCell(270,5,"PIHAK KEDUA berkewajiban menjaga dan merawat barang-barang dimaksud yang merupakan aset Pemerintah Kabupaten PROVINSI SUMATERA UTARA dari segala sesuatu hal yang patut dianggap dapat merusak atau merugikan Pemerintah Kabupaten PROVINSI SUMATERA UTARA.",0,'L');
		$this->Ln(5);
		$this->MultiCell(10,5,"2. ",0,'L');
		$this->SetXY(20,$y+25);
		$this->MultiCell(270,5,"Segala sesuatu biaya yang timbul setelah Berita Acara Serah Terima Barang ini ditandatangani adalah sepenuhnya menjadi tanggungjawab PIHAK KEDUA selaku pemakai tanpa dapat dituntut pengembaliannya kepada PIHAK PERTAMA.",0,'L');
		$this->Ln(5);
		$this->MultiCell(280,5,"Demikian berita acara ini diperbuat dengan sebenarnya dalam rangkap 2 (dua) untuk dapat dipergunakan sebagaimana mestinya.",0,'L');
}

function Pasal($a='',$b='')
{
        $this->SetFont('Arial','',12);
        $x=$this->SetX(10);
        $y=$this->GetY();

        $this->MultiCell(280,5,"Yang diatur dengan ketentuan sebagai berikut :",0,'L');
        $this->Ln();
        $this->SetFont('Arial','B',12);
        $this->MultiCell(280,5,"Pasal 1",0,'C');
        $this->SetFont('Arial','',12);
		$this->SetXY(10,$y+15);
		$this->MultiCell(280,5,"Pihak Ke-I meminjampakaikan kepada Pihak Ke-II barang milik daerah dimaksud tanpa merubah status kepemilikannya dan akan dipergunakan oleh Pihak Ke-II sesuai dengan peruntukannya.",0,'L');
		$this->Ln(5);
		$this->SetFont('Arial','B',12);
        $this->MultiCell(280,5,"Pasal 2",0,'C');
        $this->SetFont('Arial','',12);
		$this->SetXY(10,$y+35);
		$this->MultiCell(280,5,"Pihak Ke-II bertanggungjawab atas keutuhan dan keselamatan barang milik daerah dimaksud serta bertanggungjawab atas biaya operasional dan pemeliharaannya selama jangka waktu pinjam pakai dan tidak dapat dituntut pengembaliannya oleh Pihak Ke-II kepada Pihak Ke-I.",0,'L');
		$this->Ln(5);
		$this->SetFont('Arial','B',12);
        $this->MultiCell(280,5,"Pasal 3",0,'C');
        $this->SetFont('Arial','',12);
		$this->SetXY(10,$y+60);
		$this->MultiCell(280,5,"Jangka waktu pinjam pakai barang sejak tanggal ".tgl_indo($a)." sampai dengan tanggal ".tgl_indo($b)." dan dapat diperpanjang kembali apabila masih dibutuhkan oleh Pihak Ke-II dengan mengajukan permohonan kepada Pihak Ke-I.",0,'L');
		$this->Ln(5);
		$this->SetFont('Arial','B',12);
        $this->MultiCell(280,5,"Pasal 4",0,'C');
		$this->SetXY(10,$y+85);
		$this->SetFont('Arial','',12);
		$this->MultiCell(280,5,"Pihak Ke-II wajib mengembalikan kepada Pihak Ke-I barang milik daerah dimaksud dalam keadaan baik dan lengkap. Apabila sewaktu-waktu diperlukan Pihak Ke-I.",0,'L');
		$this->Ln(5);
		$this->SetFont('Arial','B',12);
        $this->MultiCell(280,5,"Pasal 5",0,'C');
        $this->SetFont('Arial','',12);
        $this->MultiCell(10,5,"1. ",0,'L');
		$this->SetXY(20,$y+105);
		$this->MultiCell(280,5,"Apabila terjadi hal-hal di luar kekuasaan kedua belah pihak atau force majeure (bencana alam, keadaan keamanan yang tidak mengizinkan), dapat dipertimbangkan kemungkinan perubahan perjanjian dengan persetujuan kedua belah pihak.",0,'L');
        $this->Ln();
        $this->MultiCell(10,5,"2. ",0,'L');
		$this->SetXY(20,$y+120);
		$this->MultiCell(280,5,"Segala perubahan dan/atau pembatalan terhadap surat perjanjian ini akan diatur kemudian oleh Pihak Ke-I dan Pihak Ke-II.",0,'L');
		$this->Ln(5);
		$this->SetFont('Arial','B',12);
        $this->MultiCell(280,5,"Pasal 6",0,'C');
		$this->SetXY(10,$y+135);
		$this->SetFont('Arial','',12);
		$this->MultiCell(280,5,"Surat Perjanjian ini dibuat dan ditandatangani oleh kedua belah pihak, pada hari dan tanggal tersebut diatas.",0,'L');
}

function ttd($nm_1,$nip_1,$nm_2,$nip_2,$tgl){
	
	$r1  = 10;
	$y1  = $this->h - 72;
	$y=$this->GetY();
	if($this->GetY() >= 370)
        $this->AddPage($this->CurOrientation);
	
	$this->SetXY($r1,$y1-10);
	$this->SetFont("Arial", "B", 12);
	$this->MultiCell(280,5,"Medan, ".tgl($tgl)." ".bulan(bln($tgl))." ".thn($tgl),0,'C');

	$this->SetFont("Arial", "B", 12);
	$this->SetXY($r1+190,$y1);
	$this->Ln();
	$this->MultiCell(90,5,"PIHAK PERTAMA",0,'C');
	$this->SetXY($r1+190,$y1+5);
	$this->MultiCell(90,5,"PIHAK KEDUA",0,'C');
	
	$this->Ln(25);
	$this->SetFont('Arial','UB',12);
	$this->Cell(90,5,$nm_1,0,'B', 'C');
	$this->SetXY($r1+190,$y1+35);
	$this->Cell(90,5,$nm_2,0,'B', 'C');
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(90,5,'NIP . '.$nip_1,0,'B', 'C');
	$this->SetXY($r1+210,$y1+40);
	$this->Cell(90,5,'NIP . '.$nip_2, 'C');

}

function nihil()
{
	$this->SetFont('times','',50);
	$this->MultiCell(440,60,"N   I   H   I   L",1,'C');
}


// function Header()
// {
   
// 	if( $this->PageNo() != 1){
// 		$this->SetFont('Arial','B',15);
// 	    $this->Cell(0,9,'BERITA ACARA PINJAM PAKAI',0,0,'C');
// 	}
// }

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