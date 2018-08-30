<?php
    header("Content-type: application/vnd.ms-excel; name='excel'");
    header("Content-Disposition: attachment; filename=KIBB.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
?>

<table  width="1724" cellspacing="0" cellpadding="0">
    <tr>
        <td rowspan="5" colspan="2" width="119" align="left"><img src="<?php echo base_url().'asset/img/logo.png'; ?>" width="80"></td>
        <td align="center" colspan="12">KARTU INVENTARIS BARANG (KIB)</td>  
    </tr>
    <tr>
        <td align="center" colspan="12">B. PERALATAN DAN MESIN</td>
    </tr>
    <tr>
        <td align="center" colspan="12">PEMERINTAH PROVINSI SUMATERA UTARA</td>
    </tr>
    <tr>
        <td align="center" colspan="12"><i>TAHUN ANGGARAN</i></td>
    </tr>
    <tr>
        <td align="center" colspan="12"><i>1 Jan 2015 s/d 31 Dec 2015</i></td>
    </tr>
</table >

<table width="1724" cellspacing="0" cellpadding="0">
    <tr>
        <td align="left" colspan="2">PROVINSI</td>
        <td align="left" colspan="12">: SUMATERA UTARA</td>
    </tr>
    <tr>
        <td align="left" colspan="2">KABUPATEN/KOTA</td>
        <td align="left" colspan="12">: PEMERINTAH PROVINSI SUMATERA UTARA</td>
    </tr>
    <tr>
        <td align="left" colspan="2">BIDANG</td>
        <td align="left" colspan="12">: Sekretariat Daerah</td>
    </tr>
    <tr>
        <td align="left" colspan="2">UNIT ORGANISASI</td>
        <td align="left" colspan="12">: Sekretariat Daerah</td>
    </tr>
    <tr>
        <td align="left" colspan="2">SUB UNIT ORGANISASI</td>
        <td align="left" colspan="12">: Biro Perlengkapan dan Pengelolaan Aset</td>
    </tr>
    <tr>
        <td align="left" colspan="2">UPB</td>
        <td align="left" colspan="12">: Biro Perlengkapan Dan Pengelolaan Aset</td>
    </tr>
    <tr>
        <td align="Right" colspan="14">NO KODE LOKASI : 11.02.0.04.01.11.01</td>
    </tr>
</table>

<table border="1" width="1812" cellspacing="0" cellpadding="0">
<tbody>
<tr style="font-weight: bold; text-align: center;">
    <td rowspan="2" width="41" height="57">No.</td>
    <td rowspan="2" width="178">Kode Barang</td>
    <td rowspan="2" width="237">Jenis Barang/ Nama Barang</td>
    <td rowspan="2" width="119">Nomor Register</td>
    <td rowspan="2" width="119">Merk / Type</td>
    <td rowspan="2" width="109">Ukuran / CC</td>
    <td rowspan="2" width="97">Bahan</td>
    <td rowspan="2" width="97">Tahun Pembelian</td>
    <td colspan="5" width="396">Nomor</td>
    <td rowspan="2" width="119">Asal Usul</td>
    <td rowspan="2" width="140">Harga (ribuan Rp)</td>
    <td rowspan="2" width="160">Keterangan</td>
</tr>
<tr style="font-weight: bold; text-align: center;">
    <td width="84" height="37">Pabrik</td>
    <td width="76">Rangka</td>
    <td width="76">Mesin</td>
    <td width="84">Polisi</td>
    <td width="76">BPKB</td>
</tr>
<tr style="font-weight: bold; text-align: center;">
    <td width="41" height="37">1</td>
    <td width="178">2</td>
    <td width="237">3</td>
    <td width="119">4</td>
    <td width="119">5</td>
    <td width="109">6</td>
    <td width="97">7</td>
    <td width="97">8</td>
    <td width="84">9</td>
    <td width="76">10</td>
    <td width="76">11</td>
    <td width="84">12</td>
    <td width="76">13</td>
    <td width="119">14</td>
    <td width="140">15</td>
    <td width="160">16</td>
</tr>
<?php
    $no=1;
    if ($jumlah > 0){
    foreach ($kibb->result() as $row){
        $kd1 = sprintf ("%02u", $row->Kd_Aset1);
        $kd2 = sprintf ("%02u", $row->Kd_Aset2);
        $kd3 = sprintf ("%02u", $row->Kd_Aset3);
        $kd4 = sprintf ("%02u", $row->Kd_Aset4);
        $kd5 = sprintf ("%02u", $row->Kd_Aset5);
        $kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;

        $min_register = sprintf ("%04u", $row->min_register);
        $max_register = sprintf ("%04u", $row->max_register);
        if ($row->jumlah_register > 1){
            $register = $min_register." s/d ".$max_register;
        }else{
            $register = $min_register;
        }
        if (empty($row->Merk) AND empty($row->Type)){
            $merk = '-';
        }else{
            $merk = $row->Merk.' / '.$row->Type;
        }
?>
<tr>
    <td width="41" height="18" style="text-align: center;"><?php echo $no; ?></td>
    <td width="178" style="text-align: center;"><?php echo $kodebarang; ?></td>
    <td width="237" style="text-align: left;"><?php echo $row->Nm_Aset5; ?></td>
    <td width="119" style="text-align: center;"><?php echo $register; ?></td>
    <td width="119" style="text-align: center;"><?php echo $merk; ?></td>
    <td width="109" style="text-align: center;"><?php echo  $row->CC; ?></td>
    <td width="97" style="text-align: center;"><?php echo  $row->Bahan; ?></td>
    <td width="97" style="text-align: center;"><?php echo $row->Tahun; ?></td>
    <td width="84" style="text-align: center;"><?php echo $row->Nomor_Pabrik; ?></td>
    <td width="76" style="text-align: center;"><?php echo $row->Nomor_Rangka; ?></td>
    <td width="76" style="text-align: center;"><?php echo $row->Nomor_Mesin; ?></td>
    <td width="84" style="text-align: center;"><?php echo $row->Nomor_Polisi; ?></td>
    <td width="76" style="text-align: center;"><?php echo $row->Nomor_BPKB; ?></td>
    <td width="119" style="text-align: center;"><?php echo $row->Asal_usul; ?></td>
    <td width="140" style="text-align: right;"><?php echo nilai($row->Harga); ?></td>
    <td width="160" style="text-align: left;"><?php echo $row->Keterangan; ?></td>
</tr>
<?php
        $no++;
        }
    }else{
       echo '<tr>
                <td width="41" height="20" colspan="16">N I H I L</td>
            </tr>';
    }
?>
<tr>
<td width="41" height="74">&nbsp;</td>
<td width="178">&nbsp;</td>
<td width="237">&nbsp;</td>
<td width="119">1 Unit</td>
<td width="119">&nbsp;</td>
<td width="109">&nbsp;</td>
<td width="97">&nbsp;</td>
<td width="97">&nbsp;</td>
<td width="84">&nbsp;</td>
<td width="76">&nbsp;</td>
<td width="76">&nbsp;</td>
<td width="84">&nbsp;</td>
<td width="76">&nbsp;</td>
<td width="119">&nbsp;</td>
<td width="140">162,367,010</td>
<td width="160">&nbsp;</td>
</tr>
<!--EndFragment--></tbody>
</table>