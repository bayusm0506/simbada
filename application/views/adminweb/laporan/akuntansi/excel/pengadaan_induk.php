<style type="text/css">
.title {
	font-weight: bold;
	font-size: 24px;
}
.ttd {
	text-align: center;
}
.ttd {
	text-align: center;
}
</style>
<table width="100%">
<tr>
<td></td>
<td height="40" align="center" class="title">DAFTAR PENGADAAN</td>
</tr>
<tr>
  <td></td>
  <td>
  <p align="center"><strong><?php echo $periode; ?></strong></p>
  <table border="0" cellspacing="0" cellpadding="0" width="498">
    <tr>
      <td width="174" valign="top"><p><strong>PROVINSI</strong></p></td>
      <td width="324" valign="top"><p><strong>: SUMATERA UTARA</strong></p></td>
      </tr>
    <tr>
      <td width="174" valign="top"><p><strong>KABUPATEN/KOTA</strong></p></td>
      <td width="324" valign="top"><p><strong>: MEDAN</strong></p></td>
      </tr>
    <tr>
      <td width="174" valign="bottom"><p>NO. KODE LOKASI</p></td>
      <td width="324" valign="bottom"><p>: 12.02.14.05.01.01.01</p></td>
    </tr>
  </table>
  </td>
</tr>
<tr>
<td></td>
<td>
  <table width="100%" border="1" cellpadding="8" style="border-collapse:collapse;">      
      <tr height="40" style="background-color:#9AE456;">
        <td width="20" rowspan="2" style="text-align: center; font-weight: bold;">NO</td>
        <td width="249" rowspan="2" style="text-align: center; font-weight: bold;"><strong>JENIS BARANG YANG DIBELI</strong></td>
        <td colspan="2" style="text-align: center; font-weight: bold;">SPK/ PERJANJIAN/ KONTRAK</td>
        <td colspan="2" style="text-align: center; font-weight: bold;">DPA/SPM/KUITANSI</td>
        <td colspan="3" style="text-align: center; font-weight: bold;">JUMLAH</td>
        <td colspan="2" style="text-align: center; font-weight: bold;">DIPERGUNAKAN PADA UNIT</td>
        <td width="150" rowspan="2" style="text-align: center; font-weight: bold;">KET.</td>
      </tr>
      <tr height="40" style="background-color:#9AE456;">
        <td width="163" style="text-align: center; font-weight: bold;">TANGGAL</td>
        <td style="text-align: center; font-weight: bold;">NOMOR</td>
        <td width="163" style="text-align: center; font-weight: bold;">TANGGAL</td>
        <td style="text-align: center; font-weight: bold;">NOMOR</td>
        <td width="163" style="text-align: center; font-weight: bold;">BANYAKNYA BARANG</td>
        <td width="233" style="text-align: center; font-weight: bold;">HARGA SATUAN</td>
        <td width="233" style="text-align: center; font-weight: bold;">JUMLAH HARGA</td>
        <td width="163" style="text-align: center; font-weight: bold;">SKPD</td>
        <td style="text-align: center; font-weight: bold;">UNIT</td>
        </tr>
      
      <tr>
        <td align="center" valign="top" bgcolor="#FDF8F4" style="text-align: center">1</td>
        <td valign="top" bgcolor="#FDF8F4" style="text-align: center">2</td>
        <td valign="top" bgcolor="#FDF8F4" style="text-align: center">3</td>
        <td align='right' valign="top" bgcolor="#FDF8F4" style="text-align: center">4</td>
        <td valign="top" bgcolor="#FDF8F4" style="text-align: center">5</td>
        <td align='right' valign="top" bgcolor="#FDF8F4" style="text-align: center">6</td>
        <td valign="top" bgcolor="#FDF8F4" style="text-align: center">7</td>
        <td align='right' valign="top" bgcolor="#FDF8F4" style="text-align: center">8</td>
        <td align='right' valign="top" bgcolor="#FDF8F4" style="text-align: center">9</td>
        <td valign="top" bgcolor="#FDF8F4" style="text-align: center">10</td>
        <td align='right' valign="top" bgcolor="#FDF8F4" style="text-align: center">11</td>
        <td valign="top" bgcolor="#FDF8F4" style="text-align: center">12</td>
      </tr>
	<?php
		$no=1;
		$jumlah=0;
		$h_satuan =0;
		$harga =0;
		foreach($data_view->result() as $dp)
		{
	?>
      <tr>
        <td valign="top" align="center"><?php echo $no; ?></td>
        <td valign="top"><?php echo $dp->Nm_Aset5; ?></td>
        <td valign="top" align='center'><?php echo tgl_indo($dp->Tgl_Kontrak); ?></td>
        <td align='center' valign="top"><?php echo $dp->No_Kontrak; ?></td>
        <td valign="top" align='center'>&nbsp;</td>
        <td align='center' valign="top">&nbsp;</td>
        <td valign="top" align='center'><?php echo $dp->Jumlah; ?></td>
        <td align='right' valign="top"><?php echo rp($dp->Harga); ?></td>
        <td valign="top" align='right'><?php echo rp($dp->Jumlah*$dp->Harga); ?></td>
        <td valign="top"><?php echo $dp->Nm_UPB; ?></td>
        <td valign="top"><?php 
						$tag	= explode(',',$dp->Dipergunakan);
						foreach($tag as $element)
						{
							echo $element."<br/>";
						}
						?></td>
        <td valign="top"> -</td>
        </tr>
	 <?php
	 		$no++;
			$jumlah 	= $jumlah + $dp->Jumlah;
			$h_satuan 	= $harga + $dp->Harga;
			$harga 		= $harga + ($dp->Jumlah*$dp->Harga);
	 	}
	 ?>
	 <tr>
        <td valign="top" align="center">&nbsp;</td>
        <td valign="top">&nbsp;</td>
        <td valign="top"  align='right'>&nbsp;</td>
        <td  align='right' valign="top">&nbsp;</td>
        <td valign="top"  align='right'>&nbsp;</td>
        <td  align='right' valign="top">&nbsp;</td>
        <td valign="top"  align='center'><?php echo $jumlah; ?></td>
        <td valign="top"  align='right'><?php echo rp($h_satuan); ?></td>
        <td valign="top"  align='right'><?php echo rp($harga); ?></td>
        <td valign="top"  align='right'>&nbsp;</td>
        <td  align='right' valign="top">&nbsp;</td>
        <td valign="top">&nbsp;</td>
      </tr>
  </table>
 
  <table width="100%" border="0">
    <tr>
      <td width="24%">&nbsp;</td>
      <td width="49%">&nbsp;</td>
      <td width="27%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="ttd">Mengetahui</td>
      <td>&nbsp;</td>
      <td class="ttd"><?php echo $tanggal; ?></td>
    </tr>
    <tr>
      <td class="ttd">JABATAN PIMPINAN</td>
      <td>&nbsp;</td>
      <td class="ttd">PENGURUS BARANG</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="ttd">Nama Pimpinan</td>
      <td>&nbsp;</td>
      <td class="ttd">nama pengurus</td>
    </tr>
    <tr>
      <td class="ttd">NIP :</td>
      <td>&nbsp;</td>
      <td class="ttd">NIP :</td>
    </tr>
  </table>
  <table width="100%" border="0">
    <tr>
      <td width="24%">&nbsp;</td>
      <td width="49%">&nbsp;</td>
      <td width="27%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="ttd">Mengetahui</td>
      <td>&nbsp;</td>
      <td class="ttd"><?php echo $tanggal; ?></td>
    </tr>
    <tr>
      <td class="ttd">JABATAN PIMPINAN</td>
      <td>&nbsp;</td>
      <td class="ttd">PENGURUS BARANG</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="ttd">Nama Pimpinan</td>
      <td>&nbsp;</td>
      <td class="ttd">nama pengurus</td>
    </tr>
    <tr>
      <td class="ttd">NIP :</td>
      <td>&nbsp;</td>
      <td class="ttd">NIP :</td>
    </tr>
  </table>
  <p>&nbsp;</p></td>
</tr>
</table>
