<?php
    header("Content-type: application/vnd.ms-excel; name='excel'");
    header("Content-Disposition: attachment; filename=KIBC.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
?>

<table  width="1724" cellspacing="0" cellpadding="0">
    <tr>
        <td rowspan="5" colspan="2" width="119" align="left"><img src="<?php echo base_url().'asset/img/logo.png'; ?>" width="80"></td>
        <td align="center" colspan="12">KARTU INVENTARIS BARANG (KIB)</td>  
    </tr>
    <tr>
        <td align="center" colspan="12">C. GEDUNG DAN BANGUNAN</td>
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

<table border="1" width="1724" cellspacing="0" cellpadding="0">
<!--StartFragment--> 
<colgroup><col width="41" /> <col width="237" /> <col width="153" /> <col width="84" /> <col width="119" /> <col width="65" /> <col width="160" /> <col width="140" /> <col width="119" /> <col width="76" /> <col width="160" /> <col span="2" width="119" /> <col width="132" /> </colgroup>
<tbody>
<tr style="font-weight: bold; text-align: center;">
    <td rowspan="3" width="41" height="105">No.</td>
    <td rowspan="3" width="237">Jenis Barang/ Nama Barang</td>
    <td colspan="2" width="237">Nomor</td>
    <td rowspan="3" width="119">Luas (M2)</td>
    <td rowspan="3" width="65">Tahun Pengada an</td>
    <td rowspan="3" width="160">Letak / Alamat</td>
    <td colspan="3" width="335">Status Tanah</td>
    <td rowspan="3" width="160">Penggunaaan</td>
    <td rowspan="3" width="119">Asal Usul</td>
    <td rowspan="3" width="119">Harga (ribuan Rp)</td>
    <td rowspan="3" width="132">Keterangan</td>
</tr>
<tr style="font-weight: bold; text-align: center;">
    <td rowspan="2" width="153" height="48">Kode Barang</td>
    <td rowspan="2" width="84">Register</td>
    <td rowspan="2" width="140">Hak</td>
    <td colspan="2" width="195">Sertifikat</td>
</tr>
<tr style="font-weight: bold; text-align: center;">
    <td width="119" height="23">Tanggal</td>
    <td width="76">Nomor</td>
</tr>
<tr style="font-weight: bold; text-align: center;">
    <td width="41" height="30">1</td>
    <td width="237">2</td>
    <td width="153">3</td>
    <td width="84">4</td>
    <td width="119">5</td>
    <td width="65">6</td>
    <td width="160">7</td>
    <td width="140">8</td>
    <td width="119">9</td>
    <td width="76">10</td>
    <td width="160">11</td>
    <td width="119">12</td>
    <td width="119">13</td>
    <td width="132">14</td>
</tr>
<?php
    $no=1;
    if ($jumlah > 0){
    foreach ($kiba->result() as $row){
        $kd1 = sprintf ("%02u", $row->Kd_Aset1);
        $kd2 = sprintf ("%02u", $row->Kd_Aset2);
        $kd3 = sprintf ("%02u", $row->Kd_Aset3);
        $kd4 = sprintf ("%02u", $row->Kd_Aset4);
        $kd5 = sprintf ("%02u", $row->Kd_Aset5);
        $kodebarang = $kd1.'.'.$kd2.'.'.$kd3.'.'.$kd4.'.'.$kd5;
        $tgl_sertifikat         = tgl_dmy($row->Sertifikat_Tanggal);

        $min_register = sprintf ("%04u", $row->min_register);
        $max_register = sprintf ("%04u", $row->max_register);
        if ($row->jumlah_register > 1){
            $register = $min_register." s/d ".$max_register;
        }else{
            $register = $min_register;
        }
?>
<tr>
    <td width="41" height="20"><?php echo $no; ?></td>
    <td width="237"><?php echo $row->Nm_Aset5; ?></td>
    <td width="153"><?php echo $kodebarang; ?></td>
    <td width="84"><?php echo $register; ?></td>
    <td width="119"><?php echo rp($row->Luas_M2); ?></td>
    <td width="65"><?php echo $row->Tahun; ?></td>
    <td width="160"><?php echo $row->Alamat; ?></td>
    <td width="140"><?php echo $row->Hak_Tanah; ?></td>
    <td width="119"><?php echo $tgl_sertifikat; ?></td>
    <td width="76"><?php echo $row->Sertifikat_Nomor; ?></td>
    <td width="160"><?php echo $row->Penggunaan; ?></td>
    <td width="119"><?php echo $row->Asal_usul; ?></td>
    <td width="119"><?php echo rp($row->Harga); ?></td>
    <td width="132"><?php echo $row->Keterangan; ?></td>
</tr>
<?php
        $no++;
        }
    }else{
       echo '<tr>
                <td width="41" height="20" colspan="14">N I H I L</td>
            </tr>';
    }
?>
<tr>
    <td width="41" height="37">&nbsp;</td>
    <td width="237">&nbsp;</td>
    <td width="153">&nbsp;</td>
    <td width="84">1 Unit</td>
    <td width="119">&nbsp;</td>
    <td width="65">&nbsp;</td>
    <td width="160">&nbsp;</td>
    <td width="140">&nbsp;</td>
    <td width="119">&nbsp;</td>
    <td width="76">&nbsp;</td>
    <td width="160">&nbsp;</td>
    <td width="119">&nbsp;</td>
    <td width="119">558,700,000</td>
    <td width="132">&nbsp;</td>
</tr>
<!--EndFragment--></tbody>
</table>