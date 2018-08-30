<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->library('fpdf');
class PDF_MC_Table_Intra extends FPDF
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
		}elseif ($i==1 || $i==2 || $i==5 || $i==13){
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


function UPBTitle($bidang,$unit,$sub_unit,$upb='',$kode_lokasi)
{
        $this->SetFont('Arial','B',11);
        $x=$this->SetX(10);
        $y=$this->GetY();
		$y2=30;
		$w=0;
		$h=0;
        $this->Rect($x,$y,$w,$h);
		
		$this->SetXY(10,$y2+3);	
		$this->Cell(20,5,'PROVINSI',0,0,'L');
		$this->SetXY(80,$y2+3);	
		$this->Cell(0,5,': SUMATERA UTARA',0,0,'L');

		$this->SetXY(10,$y2+8);
		$this->Cell(20,5,'KABUPATEN/KOTA',0,0,'L');
		$this->SetXY(80,$y2+8);	
		$this->Cell(0,5,': '.NM_PEMDA,0,0,'L');

		$this->SetXY(10,$y2+13);
		$this->Cell(20,5,'BIDANG',0,0,'L');
		$this->SetXY(80,$y2+13);	
		$this->Cell(0,5,': '.$bidang,0,0,'L');

		$this->SetXY(10,$y2+18);
		$this->Cell(20,5,'UNIT ORGANISASI',0,0,'L');
		$this->SetXY(80,$y2+18);	
		$this->Cell(0,5,': '.$unit,0,0,'L');

		$this->SetXY(10,$y2+23);
		$this->Cell(20,5,'SUB UNIT ORGANISASI',0,0,'L');
		$this->SetXY(80,$y2+23);	
		$this->Cell(0,5,': '.$sub_unit,0,0,'L');

		if($upb){
			$this->SetXY(10,$y2+28);
			$this->Cell(20,5,'UPB',0,0,'L');
			$this->SetXY(80,$y2+28);	
			$this->Cell(0,5,': '.$upb,0,0,'L');
		}

		$this->SetXY(350,$y2+28);
		$this->SetFont('Arial','B',10);
		$this->Cell(100,5,'NO. KODE LOKASI : '.$kode_lokasi,0,0,'R');

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
        $this->MultiCell(10,20,"No.",1,'C');
		$this->SetXY(20,$y);
		$this->MultiCell(60,20,"",1,'C');
		$this->SetXY(20,$y+5);
		$this->MultiCell(60,5,"Jenis Barang/\n Nama Barang",0,'C');
		
		$this->SetXY(80,$y);
		$this->MultiCell(40,20,"",1,'C');
		$this->SetXY(80,$y+8);
		$this->MultiCell(40,5,"Merk / Model",0,'C');
		
		$this->SetXY(120,$y);
		$this->MultiCell(30,20,"",1,'C');
		$this->SetXY(120,$y);
		$this->MultiCell(30,5,"No.Sertifikat/\n No.pabrik/\n No. Chasis /\n No. Mesin",0,'C');
		
		$this->SetXY(150,$y);
		$this->MultiCell(30,20,"",1,'C');
		$this->SetXY(150,$y+8);
		$this->MultiCell(30,5,"Ukuran",0,'C');
		
		$this->SetXY(180,$y);
		$this->MultiCell(40,20,"",1,'C');
		$this->SetXY(180,$y+8);
		$this->MultiCell(40,5,"Bahan",0,'C');
		
		$this->SetXY(220,$y);
		$this->MultiCell(25,20,"",1,'C');
		$this->SetXY(220,$y+3);
		$this->MultiCell(25,5,"Tahun Pembelian/ \n Pembuatan",0,'C');
		
		$this->SetXY(245,$y);
		$this->MultiCell(35,20,"",1,'C');
		$this->SetXY(245,$y+5);
		$this->MultiCell(35,5,"Nomor Kode \nBarang",0,'C');
		
		$this->SetXY(280,$y);
		$this->MultiCell(20,20,"",1,'C');
		$this->SetXY(280,$y+3);
		$this->MultiCell(20,5,"Jumlah Barang/\nRegister",0,'C');
		
		$this->SetXY(300,$y);
		$this->MultiCell(40,20,"",1,'C');
		$this->SetXY(300,$y+5);
		$this->MultiCell(40,5,"Harga Beli /\nPerolehan",0,'C');
		
		$this->SetXY(340,$y);
		$this->MultiCell(60,6,"Keadaan Barang",1,'C');
		$this->SetXY(340,$y2+4);
		$this->MultiCell(20,14,"",1,'C');
		$this->SetXY(340,$y+11);
		$this->MultiCell(20,4,"Baik\n(B)",0,'C');
		$this->SetXY(360,$y2+4);
		$this->MultiCell(20,14,"",1,'C');
		$this->SetXY(360,$y+7);
		$this->MultiCell(20,4,"Kurang Baik\n(KB)",0,'C');
		$this->SetXY(380,$y2+4);
		$this->MultiCell(20,14,"",1,'C');
		$this->SetXY(380,$y+7);
		$this->MultiCell(20,4,"Rusak Berat\n(RB)",0,'C');
		
		$this->SetXY(400,$y);
		$this->MultiCell(50,20,"",1,'C');
		$this->SetXY(400,$y+8);
		$this->MultiCell(50,5,"Keterangan",0,'C');
		$this->Ln(7.5);
		
}

function Rowheader2()
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
		$this->MultiCell(40,20,"",1,'C');
		$this->SetXY(80,$y+8);
		$this->MultiCell(40,5,"Merk / Model",0,'C');
		
		$this->SetXY(120,$y);
		$this->MultiCell(30,20,"",1,'C');
		$this->SetXY(120,$y);
		$this->MultiCell(30,5,"No.Sertifikat/\n No.pabrik/\n No. Chasis /\n No. Mesin",0,'C');
		
		$this->SetXY(150,$y);
		$this->MultiCell(30,20,"",1,'C');
		$this->SetXY(150,$y+8);
		$this->MultiCell(30,5,"Ukuran",0,'C');
		
		$this->SetXY(180,$y);
		$this->MultiCell(40,20,"",1,'C');
		$this->SetXY(180,$y+8);
		$this->MultiCell(40,5,"Bahan",0,'C');
		
		$this->SetXY(220,$y);
		$this->MultiCell(25,20,"",1,'C');
		$this->SetXY(220,$y+3);
		$this->MultiCell(25,5,"Tahun Pembelian/ \n Pembuatan",0,'C');
		
		$this->SetXY(245,$y);
		$this->MultiCell(35,20,"",1,'C');
		$this->SetXY(245,$y+5);
		$this->MultiCell(35,5,"Nomor Kode \nBarang",0,'C');
		
		$this->SetXY(280,$y);
		$this->MultiCell(20,20,"",1,'C');
		$this->SetXY(280,$y+3);
		$this->MultiCell(20,5,"Jumlah Barang/\nRegister",0,'C');
		
		$this->SetXY(300,$y);
		$this->MultiCell(40,20,"",1,'C');
		$this->SetXY(300,$y+5);
		$this->MultiCell(40,5,"Harga Beli /\nPerolehan",0,'C');
		
		$this->SetXY(340,$y);
		$this->MultiCell(60,6,"Keadaan Barang",1,'C');
		$this->SetXY(340,$y2+4);
		$this->MultiCell(20,14,"",1,'C');
		$this->SetXY(340,$y+11);
		$this->MultiCell(20,4,"Baik\n(B)",0,'C');
		$this->SetXY(360,$y2+4);
		$this->MultiCell(20,14,"",1,'C');
		$this->SetXY(360,$y+7);
		$this->MultiCell(20,4,"Kurang Baik\n(KB)",0,'C');
		$this->SetXY(380,$y2+4);
		$this->MultiCell(20,14,"",1,'C');
		$this->SetXY(380,$y+7);
		$this->MultiCell(20,4,"Rusak Berat\n(RB)",0,'C');
		
		$this->SetXY(400,$y);
		$this->MultiCell(50,20,"",1,'C');
		$this->SetXY(400,$y+8);
		$this->MultiCell(50,5,"Nama UPB",0,'C');
		$this->Ln(7.5);
		
}

function Lampiran()
{
        $this->SetFont('Arial','I',8);
        $x=$this->SetX(0);
        $y=$this->GetY();
		$y2=$y;
		$w=0;
		$h=0;
		$this->SetXY(380,$y);
		$this->MultiCell(60,5,"Lampiran Keputusan GUBERNUR SUMATERA UTARA",0,'L'); 
		$this->SetXY(380,$y+3);
		$this->MultiCell(60,5,"Nomor      : __________________________",0,'L'); 
		$this->SetXY(380,$y+6);
		$this->MultiCell(60,5,"Tanggal    : __________________________",0,'L'); 
		$this->SetXY(380,$y+10);
		$this->MultiCell(60,4,"Tentang Penetapan Status Penggunaan Barang di Lingkungan Pemerintah Kabupaten Mandaing Natal",0,'L'); 
		
}

function RowheaderHapus()
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
		$this->MultiCell(40,20,"",1,'C');
		$this->SetXY(80,$y+8);
		$this->MultiCell(40,5,"NO. Kode Barang",0,'C');
		
		$this->SetXY(120,$y);
		$this->MultiCell(30,20,"",1,'C');
		$this->SetXY(120,$y);
		$this->MultiCell(30,5,"No. Kode Lokasi",0,'C');
		
		$this->SetXY(150,$y);
		$this->MultiCell(30,20,"",1,'C');
		$this->SetXY(150,$y+8);
		$this->MultiCell(30,5,"Merk / Type",0,'C');
		
		$this->SetXY(180,$y);
		$this->MultiCell(40,20,"",1,'C');
		$this->SetXY(180,$y+8);
		$this->MultiCell(40,5,"Dokumen Kepemilikan",0,'C');
		
		$this->SetXY(220,$y);
		$this->MultiCell(25,20,"",1,'C');
		$this->SetXY(220,$y+3);
		$this->MultiCell(25,5,"Tahun Pembelian/ \n Pembuatan",0,'C');
		
		$this->SetXY(245,$y);
		$this->MultiCell(35,20,"",1,'C');
		$this->SetXY(245,$y+5);
		$this->MultiCell(35,5,"Harga Perolehan \n (Rp)",0,'C');
		
		$this->SetXY(280,$y);
		$this->MultiCell(20,20,"",1,'C');
		$this->SetXY(280,$y+3);
		$this->MultiCell(20,5,"Jumlah Barang/\nRegister",0,'C');
		
		$this->SetXY(300,$y);
		$this->MultiCell(40,20,"",1,'C');
		$this->SetXY(300,$y+5);
		$this->MultiCell(40,5,"Harga Beli /\nPerolehan",0,'C');
		
		$this->SetXY(340,$y);
		$this->MultiCell(60,6,"Keadaan Barang",1,'C');
		$this->SetXY(340,$y2+4);
		$this->MultiCell(20,14,"",1,'C');
		$this->SetXY(340,$y+9);
		$this->MultiCell(20,4,"Baik\n(B)",0,'C');
		$this->SetXY(360,$y2+4);
		$this->MultiCell(20,14,"",1,'C');
		$this->SetXY(360,$y+7);
		$this->MultiCell(20,4,"Kurang Baik\n(KB)",0,'C');
		$this->SetXY(380,$y2+4);
		$this->MultiCell(20,14,"",1,'C');
		$this->SetXY(380,$y+7);
		$this->MultiCell(20,4,"Kurang Baik\n(KB)",0,'C');
		
		$this->SetXY(400,$y);
		$this->MultiCell(50,20,"",1,'C');
		$this->SetXY(400,$y+8);
		$this->MultiCell(50,5,"Keterangan",0,'C');
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

function ttd3($tanggal)
{
	
	$r1  = 10;
	$y1  = $this->h - 72;
	$y=$this->GetY();
	if($this->GetY() >= 230)
        $this->AddPage($this->CurOrientation);

	$this->SetFont("Arial", "", 11);
	$this->SetXY($r1+330,$y1);
	$this->Cell(90,5,$tanggal,0,'LR', 'C');
	$this->Ln();
	$this->SetXY($r1+330,$y1+5);
	$this->MultiCell(90,5,"Plt. GUBERNUR SUMATERA UTARA",0,'C');
	
	$this->Ln(25);
	$this->SetXY($r1+330,$y1+40);
	$this->SetFont('Arial','UB',11);
	$this->Cell(90,5,'DAHLAN HASAN NASUTION',0,'B', 'C'); 
	
	$this->Ln();
	$this->SetFont('Arial','',11);
	$this->Cell(100,5,'',0,'B', 'C');

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
    $this->Cell(0,9,'DAFTAR BARANG INTRAKOMPTABEL',0,0,'C');
	$this->Ln();
	$this->SetFont('Arial','B','10');
	$this->SetWidths(array(10,60,40,30,30,40,25,35,20,40,20,20,20,50));
	$this->RowJudul(array("1", "2", "3", "4","5", "6", "7", "8","9", "10", "11", "12","13", "14"));

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